{{-- ─────────────────────────────────────────────────────────
     components/traveler-card.blade.php

     Props:
       $index        int
       $traveler     array  { name, uploads:{photo,front,back}, passport }
       $photoDoc     object|null
       $passportDocs Collection
       $otherDocs    Collection
─────────────────────────────────────────────────────────── --}}
@props([
    'index',
    'traveler',
    'photoDoc'     => null,
    'passportDocs' => collect(),
    'otherDocs'    => collect(),
])

@php
    $name     = $traveler['name'] ?? '';
    $uploads  = $traveler['uploads'] ?? [];
    $passport = $traveler['passport'] ?? null;

    $okCount = collect($uploads)->filter(fn($u) => ($u['status'] ?? '') === 'ok')->count();
    $total   = count($uploads); // photo + front + back = 3

    $pillCls = $okCount === $total ? 'done' : ($okCount > 0 ? 'partial' : '');
    $pillTxt = $okCount === $total ? 'Complete' : "{$okCount}/{$total} docs";

    $initials = $name
        ? collect(preg_split('/\s+/', trim($name)))
            ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
            ->implode('')
        : (string)($index + 1);
    $initials = mb_substr($initials, 0, 2);
@endphp

<div class="traveler-card" id="traveler-card-{{ $index }}" data-traveler-index="{{ $index }}">

    {{-- ── Head ── --}}
    <div class="tc-head" data-toggle="{{ $index }}">
        <div class="tc-avatar" id="avatar-{{ $index }}">{{ $initials }}</div>
        <div class="tc-meta">
            <div class="tc-name" id="tcname-{{ $index }}">{{ $name ?: 'Traveler '.($index+1) }}</div>
            <div class="tc-prog">
                @if($okCount === $total) All documents uploaded ✓
                @else Upload required documents
                @endif
            </div>
        </div>
        <span class="tc-pill {{ $pillCls }}">{{ $pillTxt }}</span>
        <span class="tc-chevron {{ $index === 0 ? 'open' : '' }}" id="chev-{{ $index }}">▼</span>
    </div>

    {{-- ── Body ── --}}
    <div class="tc-body {{ $index === 0 ? 'open' : '' }}" id="body-{{ $index }}">

        {{-- Name input --}}
        <input
            class="name-input"
            placeholder="Full name as on passport"
            value="{{ $name }}"
            data-name-input="{{ $index }}"
        />

        <div class="section-label">Documents</div>

        <div class="upload-row">

            {{-- ── Passport Photo ── --}}
            @if($photoDoc)
                <x-upload-box
                    :travelerIndex="$index"
                    :docId="$photoDoc->visa_type_document_id"
                    type="photo"
                    icon="👤"
                    :label="$photoDoc->document"
                    hint="Passport size portrait photo"
                    :upload="$uploads['photo'] ?? null"
                />
            @endif

            {{-- ── Passport Front ── --}}
            @php $frontDoc = $passportDocs->first(); @endphp
            @if($frontDoc)
                <x-upload-box
                    :travelerIndex="$index"
                    :docId="$frontDoc->visa_type_document_id"
                    type="front"
                    icon="🛂"
                    :label="$frontDoc->document . ' — Front'"
                    hint="Bio-data page"
                    :upload="$uploads['front'] ?? null"
                    :passportData="$passport"
                />
            @endif

            {{-- ── Passport Back ── --}}
            @php $backDoc = $passportDocs->count() > 1 ? $passportDocs->get(1) : $passportDocs->first(); @endphp
            @if($backDoc)
                <x-upload-box
                    :travelerIndex="$index"
                    :docId="$backDoc->visa_type_document_id"
                    type="back"
                    icon="📋"
                    :label="$backDoc->document . ' — Back'"
                    hint="Last / signature page"
                    :upload="$uploads['back'] ?? null"
                />
            @endif

            {{-- ── Other Documents (ITR, bank statement, etc.) ── --}}
            @foreach($otherDocs as $doc)
                <x-upload-box
                    :travelerIndex="$index"
                    :docId="$doc->visa_type_document_id"
                    :type="'doc-'.$doc->visa_type_document_id"
                    icon="📄"
                    :label="$doc->document"
                    :hint="'Upload '.$doc->document"
                    :upload="$uploads['doc-'.$doc->visa_type_document_id] ?? null"
                />
            @endforeach

        </div>{{-- /.upload-row --}}

        {{-- Remove button (hidden for first traveler) --}}
        @if($index > 0)
            <button
                class="remove-traveler-btn"
                data-remove-traveler="{{ $index }}"
            >✕ Remove Traveler</button>
        @endif

    </div>{{-- /.tc-body --}}
</div>