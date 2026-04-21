@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/traveller.css') }}">
@endpush

@section('content')

{{-- ───────────────────────────────────────────
     Page
─────────────────────────────────────────── --}}
<div class="vd-page">

    @include('components.sidebar')

    <main class="vd-main">
        <h1 class="page-title">Traveler Documents</h1>
        <p class="page-sub">Upload required documents for each traveler.</p>

        {{-- ── Traveler cards ── --}}
        <div id="travelers-list">
            @foreach($travelers as $ti => $traveler)
                <x-traveler-card
                    :index="$ti"
                    :traveler="$traveler"
                    :photoDoc="$photoDoc"
                    :passportDocs="$passportDocs"
                    :otherDocs="$otherDocs"
                />
            @endforeach
        </div>

        {{-- ── Actions ── --}}
        <div class="actions">
            <button class="btn-add" id="btn-add-traveler">+ Add Traveler</button>
            <button class="btn-next" id="nextBtn" disabled>
                Continue to Payment →
            </button>
        </div>
    </main>
</div>

{{-- ───────────────────────────────────────────
     Upload Modal  (single instance, reused)
─────────────────────────────────────────── --}}
<x-upload-modal />

{{-- ───────────────────────────────────────────
     Passport Preview Modal
─────────────────────────────────────────── --}}
@include('components.passport-preview-modal')

{{-- ───────────────────────────────────────────
     Data bridge  — PHP → JS
     JS only reads config; never builds HTML
─────────────────────────────────────────── --}}
<script>
window.VD = {
    addTravelerUrl:  "{{ route('visa.add-traveler') }}",
    saveTravelersUrl:"{{ route('visa.save-travelers') }}",
    csrfToken:       "{{ csrf_token() }}",
    countryId:       {{ $country->id }},

    travelers: {!! json_encode(
        collect($travelers)->map(function($t) {
            return [
                'name'     => $t['name'] ?? '',
                'uploads'  => $t['uploads'] ?? ['photo'=>null,'front'=>null,'back'=>null],
                'passport' => $t['passport'] ?? null,
            ];
        })->values()
    ) !!},
};
</script>

@endsection

@push('scripts')
{{-- ─────────────────────────────────────────────────────────
     CRITICAL: Load order matters.
       1. face-api      — loaded first so faceapi global exists when ocr.js runs
       2. traveler.js   — declares `travelers`, `currentTraveler`, `currentType`,
                          `capturedData`, and all shared helpers (setCheck etc.)
                          MUST be before camera.js and ocr.js
       3. camera.js     — reads `currentType`, `capturedData` from traveler.js
       4. ocr.js        — reads `travelers`, `currentTraveler` from traveler.js
─────────────────────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script src="{{ asset('js/traveler.js') }}"></script>
<script src="{{ asset('js/camera.js') }}"></script>
<script src="{{ asset('js/ocr.js') }}"></script>
@endpush