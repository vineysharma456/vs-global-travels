{{-- ─────────────────────────────────────────────────────────
     components/upload-box.blade.php

     Props:
       $travelerIndex  int
       $docId          int          — visa_type_document_id (used as key)
       $type           string       — 'photo' | 'front' | 'back' | 'doc-{id}'
       $icon           string
       $label          string
       $hint           string
       $upload         array|null   — { src, status } from session/state
       $passportData   array|null   — only for front type
─────────────────────────────────────────────────────────── --}}
@props([
    'travelerIndex',
    'docId',
    'type',
    'icon'         => '📄',
    'label',
    'hint'         => '',
    'upload'       => null,
    'passportData' => null,
])

@php
    $isOk    = ($upload['status'] ?? '') === 'ok';
    $isInval = $upload && !$isOk;
    $boxCls  = 'ubox' . ($isOk ? ' uploaded' : '') . ($isInval ? ' invalid' : '');
@endphp

<div
    class="{{ $boxCls }}"
    data-ubox
    data-traveler="{{ $travelerIndex }}"
    data-type="{{ $type }}"
    data-doc-id="{{ $docId }}"
    onclick="VD_openModal(this)"
>
    @if($isOk)
        <img
            class="ubox-preview"
            src="{{ $upload['src'] }}"
            alt="{{ $label }}"
        />
    @endif

    <span class="ubox-icon">{{ $icon }}</span>

    <span class="ubox-label">
        @if($isOk) Uploaded ✓
        @elseif($isInval) Invalid — tap to retry
        @else {{ $label }}
        @endif
    </span>

    <span class="ubox-hint">{{ $hint }}</span>

    {{-- Passport details button — front page only, when data exists --}}
    @if($type === 'front' && $passportData)
        <button
            type="button"
            class="ubox-passport-btn"
            onclick="event.stopPropagation(); VD_openPassportPreview({{ $travelerIndex }})"
        >📋 View Passport Details</button>
    @endif
</div>