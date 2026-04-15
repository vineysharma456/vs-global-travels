<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class VisaApplyController extends Controller
{
    public function startApplication(Country $country){

        return view('admin.visa-apply.e-visa-apply',compact('country'));
    }

public function scan(Request $request)
{
    try {
        Log::info('Scan API called');

        $base64 = $request->image;

        if (!$base64) {
            Log::warning('No image received in request');
            return response()->json([
                'status' => 'error',
                'message' => 'No image'
            ]);
        }

        Log::info('Image received, starting processing');

        // Clean base64 properly (handles jpg/png)
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $image = str_replace(' ', '+', $image);

        Log::info('Sending image to OCR.Space');

        $response = \Http::asForm()->post('https://api.ocr.space/parse/image', [
            'apikey' => env('OCR_SPACE_API_KEY'),
            'base64Image' => 'data:image/jpeg;base64,' . $image,
            'language' => 'eng',
            'isOverlayRequired' => 'false', // ✅ MUST be string
            'OCREngine' => '2', // ✅ better accuracy
        ]);

        if (!$response->successful()) {
            Log::error('HTTP request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'OCR API request failed'
            ]);
        }

        $result = $response->json();

        Log::info('OCR.Space response', ['response' => $result]);

        // ✅ Handle OCR API errors
        if (($result['IsErroredOnProcessing'] ?? false) === true) {
            Log::error('OCR API error', [
                'error' => $result['ErrorMessage'] ?? 'Unknown error'
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $result['ErrorMessage'][0] ?? 'OCR processing error'
            ]);
        }

        if (!isset($result['ParsedResults'][0]['ParsedText'])) {
            Log::error('OCR parsing failed', ['response' => $result]);

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to extract text from image'
            ]);
        }

        $text = trim($result['ParsedResults'][0]['ParsedText']);

        Log::info('OCR completed', [
            'text_preview' => substr($text, 0, 500) // prevent huge logs
        ]);

        // ✅ Passport validation
        if (!Str::contains($text, 'P<')) {
            Log::warning('Invalid passport detected', ['text' => $text]);

            return response()->json([
                'status' => 'error',
                'message' => 'Not a valid passport. Try again.'
            ]);
        }

        Log::info('Valid passport detected');

        // ✅ Parse MRZ
        $data = $this->parseMRZ($text);

        Log::info('MRZ parsed successfully', ['data' => $data]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'raw' => $text
        ]);

    } catch (\Exception $e) {

        Log::error('Scan failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Scan failed'
        ]);
    }
}


private function parseMRZ($text)
{
    $lines = array_values(array_filter(array_map('trim', explode("\n", $text))));

    $mrzLines = [];

    // ✅ Collect ONLY valid MRZ lines (length ~44 chars)
    foreach ($lines as $line) {
        $clean = preg_replace('/[^A-Z0-9<]/', '', strtoupper($line));

        if (strlen($clean) >= 40 && strpos($clean, '<') !== false) {
            $mrzLines[] = $clean;
        }
    }

    if (count($mrzLines) < 2) {
        return ['error' => 'MRZ not found'];
    }

    // ✅ Last 2 lines are MRZ
    $mrz1 = $mrzLines[count($mrzLines) - 2];
    $mrz2 = $mrzLines[count($mrzLines) - 1];

    /* ───────── NAME ───────── */
    $namePart = explode('<<', substr($mrz1, 5));
    $lastName  = str_replace('<', ' ', $namePart[0] ?? '');
    $firstName = str_replace('<', ' ', $namePart[1] ?? '');

    /* ───────── PASSPORT NUMBER ───────── */
    $passportNumber = str_replace('<', '', substr($mrz2, 0, 9));

    /* ───────── COUNTRY ───────── */
    $countryCode = substr($mrz2, 10, 3);

    /* ───────── DOB ───────── */
    $dobRaw = substr($mrz2, 13, 6);
    $dob = $this->formatDateFixed($dobRaw, 'dob');

    /* ───────── SEX ───────── */
    $sexChar = substr($mrz2, 20, 1);
    $sex = $sexChar === 'M' ? 'Male' : ($sexChar === 'F' ? 'Female' : 'Other');

    /* ───────── EXPIRY ───────── */
    $expiryRaw = substr($mrz2, 21, 6);
    $expiry = $this->formatDateFixed($expiryRaw, 'expiry');

    /* ───────── PLACE OF ISSUE ───────── */
    $placeOfIssue = null;

    foreach ($lines as $index => $line) {

        $normalized = strtolower($line);

        // Match ANY variation like:
        // place, plce, pace, issue, ssue, etc.
        if (
            str_contains($normalized, 'issue') ||
            str_contains($normalized, 'ssu') ||   // OCR broken case
            str_contains($normalized, 'pace') ||  // OCR misread "place"
            str_contains($normalized, 'plce')
        )  {

            $nextLine = trim($lines[$index + 1] ?? '');

            if ($nextLine && strlen($nextLine) > 2) {
                $placeOfIssue = strtoupper($nextLine);
                break;
            }
        }
    }
    return [
        'first_name'      => trim($firstName),
        'last_name'       => trim($lastName),
        'passport_number' => $passportNumber,
        'dob'             => $dob,
        'expiry'          => $expiry,
        'sex'             => $sex,
        'country'         => $countryCode,
        'place_of_issue'  => $placeOfIssue,
    ];
}


private function formatDateFixed($date, $type = 'dob')
{
    if (strlen($date) !== 6) return null;

    $yy = substr($date, 0, 2);
    $mm = substr($date, 2, 2);
    $dd = substr($date, 4, 2);

    $year = (int)$yy;
    $currentYear = (int)date('y');

    if ($type === 'expiry') {
        // ✅ Expiry should always be in future
        $year = 2000 + $year;
    } else {
        // ✅ DOB logic
        if ($year <= $currentYear) {
            $year = 2000 + $year;
        } else {
            $year = 1900 + $year;
        }
    }

    return "$dd-$mm-$year";
}

public function saveTravelers(Request $request)
{
    $travelers = $request->travelers;

    // ✅ Option 1: Save in session (fast & simple)
    session(['travelers' => $travelers]);

    // ✅ Option 2 (better): Save in DB (recommended later)

    return response()->json([
        'status' => 'success'
    ]);
}

public function payment()
{
    $travelers = session('travelers');
    dd($travelers);
    return view('payment', compact('travelers'));
}
public function savePassport(Request $request)
{
    $request->validate([
        'full_name' => 'required',
        'passport_number' => 'required',
        'mobile' => 'required|digits:10',
        'email' => 'required|email',
    ]);

    // // Example save (adjust table/columns)
    // Passport::create([
    //     'full_name' => $request->full_name,
    //     'passport_number' => $request->passport_number,
    //     'sex' => $request->sex,
    //     'dob' => $request->dob,
    //     'expiry' => $request->expiry,
    //     'country' => $request->country,
    //     'place_of_issue' => $request->place_of_issue,
    //     'mobile' => $request->mobile,
    //     'email' => $request->email,
    // ]);

    return response()->json(['success' => true]);
}
}
