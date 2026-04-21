<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\VisaTypeDocument;
use DB;
use App\Models\VisaApplication;
use App\Models\ApplicationTraveller;
use App\Models\ApplicationDocument;
use Illuminate\Support\Facades\Storage;

class VisaApplyController extends Controller
{
    // public function startApplication(Country $country){
    //     $documents = DB::table('country_document as cd')
    //                  ->where('cd.country_id',$country->id)
    //                  ->leftjoin('visa_type_documents as vtd','cd.visa_type_document_id','=','vtd.id' )
    //                  ->select('cd.*','vtd.name as documne')
    //                 //  ->wher(vtd.id,visa_type_document_id)
    //                  ->get();
    // $documents=DB::table('country_document as cd')
    //               ->where('cd.country_id',$country->id)
    //               ->leftJoin('visa_type_documents as vtd','cd.visa_type_document_id','=','vtd.id')
    //               ->select('cd.*','vtd.name as document')
    //               ->get();
                  
    //      $photoDoc = $documents->firstWhere('document', 'Photo');

    //     $passportDocs = $documents->filter(function ($doc) {
    //         return str_contains(strtolower($doc->document), 'passport');
    //     });

    //     $otherDocs = $documents->reject(function ($doc) {
    //         return str_contains(strtolower($doc->document), 'passport')
    //             || strtolower($doc->document) === 'photo';
    //     });          
    //     return view('admin.visa-apply.e-visa-apply',compact('country','passportDocs','otherDocs'));
    // }

      /* ─────────────────────────────────────────────────────
       Shared helper — fetch & classify documents for a country
    ───────────────────────────────────────────────────── */
    private function getDocuments(Country $country): array
    {
        $documents = DB::table('country_document as cd')
            ->where('cd.country_id', $country->id)
            ->leftJoin('visa_type_documents as vtd', 'cd.visa_type_document_id', '=', 'vtd.id')
            ->select('cd.*', 'vtd.name as document')
            ->get();
 
        $photoDoc = $documents->firstWhere('document', 'Photo');
 
        $passportDocs = $documents->filter(
            fn($d) => str_contains(strtolower($d->document), 'passport')
        );
 
        $otherDocs = $documents->reject(
            fn($d) => str_contains(strtolower($d->document), 'passport')
                   || strtolower($d->document) === 'photo'
        );
 
        return compact('photoDoc', 'passportDocs', 'otherDocs');
    }
 
    /* ─────────────────────────────────────────────────────
       GET  /visa-apply/{country}/travelers
       Initial page load — renders full Blade view
    ───────────────────────────────────────────────────── */
    public function travelerDocuments(Country $country)
    {
        // ✅ STORE country_id in session (THIS WAS MISSING)
        session(['country_id' => $country->id]);

        ['photoDoc' => $photoDoc, 'passportDocs' => $passportDocs, 'otherDocs' => $otherDocs]
            = $this->getDocuments($country);

        $travelers = session("travelers.{$country->id}", [
            [
                'name'     => '',
                'uploads'  => ['photo' => null, 'front' => null, 'back' => null],
                'passport' => null,
            ],
        ]);

        return view('admin.visa-apply.traveler-documents', compact(
            'country', 'travelers', 'photoDoc', 'passportDocs', 'otherDocs'
        ));
    }
 
    /* ─────────────────────────────────────────────────────
       POST  /visa-apply/add-traveler
       AJAX — returns a rendered <x-traveler-card> fragment
    ───────────────────────────────────────────────────── */
    public function addTraveler(Request $request)
    {
        $validated = $request->validate([
            'index'     => 'required|integer|min:1',
            'countryId' => 'required|integer|exists:countries,id',
        ]);
 
        $country = Country::findOrFail($validated['countryId']);
 
        ['photoDoc' => $photoDoc, 'passportDocs' => $passportDocs, 'otherDocs' => $otherDocs]
            = $this->getDocuments($country);
 
        $index    = $validated['index'];
        $traveler = [
            'name'     => '',
            'uploads'  => ['photo' => null, 'front' => null, 'back' => null],
            'passport' => null,
        ];
 
        // Add a dynamic slot for every "other" doc so the JS state tracks them too
        foreach ($otherDocs as $doc) {
            $traveler['uploads']['doc-' . $doc->visa_type_document_id] = null;
        }
 
        return view('partials.traveler-card-fragment', compact(
            'index', 'traveler', 'photoDoc', 'passportDocs', 'otherDocs'
        ));
    }
 
    /* ─────────────────────────────────────────────────────
       POST  /visa-apply/save-travelers
       AJAX — persist upload state to session / DB
    ───────────────────────────────────────────────────── */
    // public function saveTravelers(Request $request)
    // {
    //     $validated = $request->validate([
    //         'travelers'              => 'required|array|min:1',
    //         'travelers.*.name'       => 'nullable|string|max:255',
    //         'travelers.*.uploads'    => 'required|array',
    //         'travelers.*.passport'   => 'nullable|array',
    //     ]);
 
    //     // Persist to session (swap for DB write as needed)
    //     $countryId = $request->input('countryId');
    //     session(["travelers.{$countryId}" => $validated['travelers']]);
 
    //     return response()->json(['status' => 'success']);
    // }
    public function saveTravelers(Request $request)
    {
        $data = $request->validate([
            'travelers'                => 'required|array',
            'travelers.*.name'         => 'nullable|string|max:255',
            'travelers.*.uploads'      => 'nullable|array',
            'travelers.*.passport'     => 'nullable|array',
        ]);
    
        session(['travelers' => $data['travelers']]);
    
        return response()->json(['status' => 'success']);
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

// public function saveTravelers(Request $request)
// {
//     $travelers = $request->travelers;

//     // ✅ Option 1: Save in session (fast & simple)
//     session(['travelers' => $travelers]);

//     // ✅ Option 2 (better): Save in DB (recommended later)

//     return response()->json([
//         'status' => 'success'
//     ]);
// }


 public function payment(Request $request)
    {
        $rawTravelers = session('travelers');
    
        if (empty($rawTravelers)) {
            return redirect()->route('visa.apply')->with('error', 'Session expired. Please start again.');
        }
    
        // Flatten the odd  "" => [ [...] ]  structure JS sends
        // travelers is either  [ traveler, ... ]  or  [ "" => [ traveler, ... ] ]
        $travelersFlat = [];
        foreach ($rawTravelers as $key => $value) {
            if (is_array($value) && isset($value[0]) && is_array($value[0])) {
                // nested: { "": [ {...}, {...} ] }
                foreach ($value as $t) {
                    $travelersFlat[] = $t;
                }
            } else {
                $travelersFlat[] = $value;
            }
        }
    
        $countryId = session('country_id'); // set earlier in your apply flow
    
        try {
            $application = DB::transaction(function () use ($travelersFlat, $countryId, $request) {
    
                // 1. Create the master application record
                $application = VisaApplication::create([
                    'application_ref' => VisaApplication::generateRef(),
                    'country_id'      => $countryId,
                    'payment_status'  => 'pending',
                    'total_amount'    => 0, // update after pricing logic
                    'ip_address'      => $request->ip(),
                ]);
    
                foreach ($travelersFlat as $index => $travelerData) {
                    $passport = $travelerData['passport'] ?? null;
    
                    // 2. Create traveler row
                    // dd($travelerData);
                   $traveler = ApplicationTraveller::create([
                        'visa_application_id' => $application->id,
                        'traveler_index'      => $index + 1,

                        // Basic
                        'full_name'           => $travelerData['name'] ?? null,

                        // ✅ Correct mapping from passport array
                        'passport_number'     => $passport['passport_number'] ?? null,
                        'nationality'         => $passport['country'] ?? null,
                        'date_of_birth'       => self::safeDate($passport['dob'] ?? null),
                        'passport_expiry'     => self::safeDate($passport['expiry'] ?? null),
                        'gender'              => $passport['sex'] ?? null,

                        // MRZ (not coming currently → fine)
                        'mrz_line1'           => $passport['mrz_line1'] ?? null,
                        'mrz_line2'           => $passport['mrz_line2'] ?? null,

                        // ❌ These don't exist in your data yet
                        'mobile'              => $passport['mobile'] ?? null,
                        'email'               => $passport['email'] ?? null,

                        // ✅ This works
                        'place_of_issue'      => $passport['place_of_issue'] ?? null,
                    ]);
    
                    // 3. Save each uploaded document
                    $uploads = $travelerData['uploads'] ?? [];
                    foreach ($uploads as $docType => $upload) {
                        // dd(array_keys($uploads));
                        if (empty($upload['src']) || ($upload['status'] ?? '') !== 'ok') {
                            continue;
                        }
    
                        $filePath = self::storeBase64(
                            $upload['src'],
                            $application->application_ref,
                            $index,
                            $docType
                        );
    
                        if (!$filePath) continue;
    
                        // Resolve the visa_type_document_id from the doc type key
                        $docId = self::resolveDocId($docType);
    
                        ApplicationDocument::create([
                            'application_travellers' => $traveler->id,
                            'visa_type_document_id'   => $docId,
                            'doc_type'                => $docType,
                            'file_path'               => $filePath,
                            'mime_type'               => self::mimeFromBase64($upload['src']),
                        ]);
                    }
                }
    
                return $application;
            });
    
            // Store application ref in session for the payment page
            session(['application_ref' => $application->application_ref]);
            session(['application_id'  => $application->id]);
    
            // Clear the heavy base64 data from session now that it's on disk
            session()->forget('travelers');
    
            return view('admin.visa-apply.payment', [
                'application' => $application->load('travelers.documents'),
            ]);
    
        } catch (\Throwable $e) {
            \Log::error('Visa application save failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong saving your application. Please try again.');
        }
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
// ─────────────────────────────────────────────────────────
//  Private helpers
// ─────────────────────────────────────────────────────────
 
/**
 * Decode a base64 data-URI and store it on the private disk.
 * Returns the stored path or null on failure.
 *
 * Path:  visa-docs/{ref}/{traveler-index}/{docType}-{uuid}.jpg
 */
private static function storeBase64(string $dataUri, string $ref, int $index, string $docType): ?string
{
    try {
        if (!str_contains($dataUri, ',')) return null;
 
        [$meta, $base64] = explode(',', $dataUri, 2);
 
        // Derive extension from the data URI header
        preg_match('/data:image\/(\w+);/', $meta, $m);
        $ext = $m[1] ?? 'jpg';
        if ($ext === 'jpeg') $ext = 'jpg';
 
        $filename = Str::slug($docType) . '-' . Str::uuid() . '.' . $ext;
        $path     = "visa-docs/{$ref}/traveler-{$index}/{$filename}";
 
        Storage::disk('private')->put($path, base64_decode($base64));
 
        return $path;
    } catch (\Throwable $e) {
        \Log::warning('Failed to store document', ['error' => $e->getMessage()]);
        return null;
    }
}
 
/**
 * Extract MIME type from a base64 data URI.
 */
private static function mimeFromBase64(string $dataUri): string
{
    preg_match('/data:([a-zA-Z0-9]+\/[a-zA-Z0-9\-.+]+);/', $dataUri, $m);
    return $m[1] ?? 'image/jpeg';
}
 
/**
 * Parse a date string safely — returns null if invalid.
 */
private static function safeDate(?string $date): ?string
{
    if (!$date) return null;
    try {
        return \Carbon\Carbon::parse($date)->toDateString();
    } catch (\Throwable) {
        return null;
    }
}
 
/**
 * For doc-{id} types extract the numeric id; for named types return null
 * (you can map photo/front/back to their real IDs if needed).
 */
private static function resolveDocId(string $docType): ?int
{
    // ✅ Case 1: dynamic docs (doc-2, doc-3...)
    if (str_starts_with($docType, 'doc-')) {
        return (int) str_replace('doc-', '', $docType);
    }

    // ✅ Case 2: static mapping
    $map = [
        'photo' => 7, // Photo
        'front' => 1, // Passport
        'back'  => 1, // Passport
    ];

    return $map[$docType] ?? null;
}

}