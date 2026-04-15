@extends('layouts.app')

@section('content')

{{-- ─────────────────────────────────────────── --}}
{{--  PAGE                                        --}}
{{-- ─────────────────────────────────────────── --}}
<div class="vd-page">

    {{-- Top progress bar --}}
    @include('components.sidebar')

    {{-- Main content --}}
    <main class="vd-main">
        <h1 class="page-title">Traveler Documents</h1>
        <p class="page-sub">Upload a passport photo and passport pages for each traveler.</p>

        {{-- Dynamic traveler cards rendered by JS --}}
        <div id="travelers-list"></div>
        {{-- <x-upload-row /> --}}

        {{-- Bottom actions --}}
        <div class="actions">
            <button class="btn-add" onclick="addTraveler()">+ Add Traveler</button>
            <button class="btn-next" id="nextBtn" disabled onclick="goNext()">
                Continue to Payment →
            </button>
        </div>
    </main>
</div>

{{-- ─────────────────────────────────────────── --}}
{{--  UPLOAD MODAL                               --}}
{{-- ─────────────────────────────────────────── --}}
<div class="modal-overlay" id="modal-overlay">
    <div class="modal-box">

        {{-- Header --}}
        <div class="modal-title-row">
            <span class="modal-title-text" id="modal-title-text">Upload Document</span>
            <button class="modal-close" onclick="closeModal()">✕</button>
        </div>

        {{-- Tabs --}}
        <div class="modal-tabs">
            <button class="tab-btn active" id="tab-camera" onclick="switchTab('camera')">📷 Camera</button>
            <button class="tab-btn"        id="tab-file"   onclick="switchTab('file')">📁 Upload File</button>
        </div>

        {{-- Passport photo rules (shown only for photo type) --}}
        <div class="passport-rules" id="passport-rules">
            <strong>Passport photo requirements:</strong><br>
            Plain white or light background &nbsp;·&nbsp; Face must be centered and fully visible &nbsp;·&nbsp;
            No glasses, hats, or accessories &nbsp;·&nbsp; Neutral expression, mouth closed &nbsp;·&nbsp;
            Recent photo (within 6 months)
        </div>

        {{-- Camera / preview area --}}
        <div class="preview-area" id="preview-area">
            <video id="live-video" autoplay playsinline muted></video>
            <img id="preview-img" alt="Document preview" />
            <div class="face-guide-overlay" id="guide-overlay">
                <div class="face-oval"></div>
            </div>
        </div>

        {{-- Analyzing loader --}}
        <div class="loading-bar" id="loading-bar">
            <div class="loading-fill"></div>
        </div>

        {{-- Validation checklist --}}
        <div class="quality-panel" id="quality-panel">
            <div class="quality-title">Photo Validation</div>

            <div class="check-item">
                <div class="check-dot" id="dot-face"></div>
                <span class="check-label">Face detected</span>
                <span class="check-val" id="val-face">—</span>
            </div>
            <div class="check-item" id="row-single">
                <div class="check-dot" id="dot-single"></div>
                <span class="check-label">Single face only</span>
                <span class="check-val" id="val-single">—</span>
            </div>
            <div class="check-item" id="row-center">
                <div class="check-dot" id="dot-center"></div>
                <span class="check-label">Face centered in frame</span>
                <span class="check-val" id="val-center">—</span>
            </div>
            <div class="check-item" id="row-size">
                <div class="check-dot" id="dot-size"></div>
                <span class="check-label">Face fills frame (30–70%)</span>
                <span class="check-val" id="val-size">—</span>
            </div>
            <div class="check-item">
                <div class="check-dot" id="dot-blur"></div>
                <span class="check-label">Image sharpness</span>
                <span class="check-val" id="val-blur">—</span>
            </div>
            <div class="check-item" id="row-bright">
                <div class="check-dot" id="dot-bright"></div>
                <span class="check-label">Good lighting</span>
                <span class="check-val" id="val-bright">—</span>
            </div>
        </div>

        {{-- Hidden file input --}}
        <input type="file" id="file-input" accept="image/*" onchange="handleFileUpload(event)" />

        {{-- Action buttons --}}
        <div class="modal-actions">
            <button class="btn-capture" id="btn-capture" onclick="capturePhoto()">Capture Photo</button>
            <button class="btn-retake"  id="btn-retake"  onclick="retakePhoto()">Retake</button>
            <button class="btn-confirm" id="btn-confirm" onclick="confirmUpload()">Use This Photo</button>
        </div>

    </div>
</div>

{{-- ─────────────────────────────────────────── --}}
{{--  PASSPORT PREVIEW MODAL                     --}}
{{-- ─────────────────────────────────────────── --}}
@include('components.passport-preview-modal')

{{-- ─────────────────────────────────────────── --}}
{{--  JS HTML TEMPLATES                          --}}
{{--  Edit card / box markup here, not in JS     --}}
{{-- ─────────────────────────────────────────── --}}
@include('components.js-templates')

{{-- ─────────────────────────────────────────── --}}
{{--  SCRIPTS                                    --}}
{{-- ─────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

@endsection

@push('scripts')
<script src="{{ asset('js/camera.js') }}"></script>
<script src="{{ asset('js/ocr.js') }}"></script>
<script src="{{ asset('js/traveler.js') }}"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/traveller.css') }}">
@endpush