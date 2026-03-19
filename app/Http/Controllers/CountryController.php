<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Country;
use App\Models\VisaType;
use App\Models\VisaTypeDocument;

class CountryController extends Controller
{
    public function addCountries()
    {
        $visa_type_document = VisaTypeDocument::get();
        $visa_type          = VisaType::get();

        return view('admin.country.add-country', compact('visa_type', 'visa_type_document'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_name'    => 'required|string|max:255',
            'flag_emoji'      => 'required|string|max:10',
            'card_image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'visa_status'     => 'required|in:No Visa Required,Visa on Arrival,e-Visa,Visa Required',
            'visa_fee'        => 'nullable|numeric|min:0',
            'processing_days' => 'nullable|integer|min:0',
            'stay_duration'   => 'nullable|integer|min:1',
            'validity_days'   => 'nullable|integer|min:1',
            'documents'       => 'nullable|array',
            'documents.*'     => 'exists:visa_type_documents,id',
            'is_published'    => 'nullable',
            'is_featured'     => 'nullable',
            'is_visa_free'    => 'nullable',
        ]);

        try {
            // ✅ 1. Store image FIRST (outside transaction)
            $imagePath = $request->file('card_image')->store('countries', 'public');

            DB::beginTransaction();

            // ✅ 2. Create country
            $country = Country::create([
                'country_name'    => $validated['country_name'],
                'flag_emoji'      => $validated['flag_emoji'],
                'card_image'      => $imagePath,
                'visa_status'     => $validated['visa_status'],
                'visa_fee'        => $validated['visa_fee'] ?? 0,
                'processing_days' => $validated['processing_days'] ?? null,
                'stay_duration'   => $validated['stay_duration'] ?? null,
                'validity_days'   => $validated['validity_days'] ?? null,
                'is_published'    => $request->boolean('is_published'),
                'is_featured'     => $request->boolean('is_featured'),
                'is_visa_free'    => $request->boolean('is_visa_free'),
            ]);

            // ✅ 3. Attach documents (safe)
            if (!empty($validated['documents'])) {
                $country->documents()->sync($validated['documents']);
            }

            DB::commit();

            return redirect()
               ->back()
                ->with('success', 'Country "' . $country->country_name . '" added successfully.');

        } catch (\Throwable $e) {

            DB::rollBack();

            // ❌ Delete file ONLY if exists
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // 🔥 IMPORTANT: show real error
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }


     public function index(Request $request)
    {
        $query = Country::with('documents')->where('is_published', true);
 
        // Filter: visa status
        if ($request->filled('type')) {
            $query->where('visa_status', $request->type);
        }
 
        // Filter: processing speed
        if ($request->filled('delivery')) {
            match ($request->delivery) {
                'fast'     => $query->where('processing_days', '<=', 3),
                'standard' => $query->whereBetween('processing_days', [4, 10]),
                default    => null,
            };
        }
 
        // Filter: required document
        if ($request->filled('document')) {
            $query->whereHas('documents', fn($q) =>
                $q->where('visa_type_documents.id', $request->document)
            );
        }
 
        $countries    = $query->latest()->paginate(12)->withQueryString();
        $documents    = VisaTypeDocument::all();
        $visaStatuses = ['No Visa Required', 'Visa on Arrival', 'e-Visa', 'Visa Required'];
 
        return view('admin.country.country-list', compact(
            'countries',
            'documents',
            'visaStatuses'
        ));
    }
}