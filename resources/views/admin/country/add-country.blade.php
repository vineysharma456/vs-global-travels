@extends('layouts.sidenav')

@section('title', 'Admin — Add Country')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --navy:       #0d2750;
        --navy-deep:  #091d3d;
        --navy-mid:   #14305e;
        --pink:       #cc00cc;
        --pink-light: #e500e5;
        --pink-dim:   rgba(204,0,204,0.1);
        --pink-border:rgba(204,0,204,0.3);
        --page-bg:    #f0f2f7;
        --white:      #ffffff;
        --border:     #d8dde8;
        --text:       #0d2750;
        --text-mid:   #4a5568;
        --text-muted: #8a9ab8;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    .page-wrap {
        background: var(--page-bg);
        min-height: 100vh;
        padding: 2rem 2.5rem 4rem;
        font-family: 'DM Sans', sans-serif;
    }

    /* ── Page Header ── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .is-invalid {
        border-color: red !important;
    }
    .page-breadcrumb {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .page-breadcrumb span { color: var(--pink); }

    .page-title {
        font-family: 'Nunito', sans-serif;
        font-size: 1.75rem;
        font-weight: 900;
        color: var(--navy);
        letter-spacing: -0.4px;
    }

    .page-title span { color: var(--pink); }

    .page-subtitle {
        font-size: 0.85rem;
        color: var(--text-mid);
        margin-top: 0.3rem;
    }

    /* Preview card (mimics screenshot) */
    .preview-card {
        width: 160px;
        height: 220px;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        background: #1a2a4a;
        flex-shrink: 0;
        box-shadow: 0 8px 32px rgba(13,39,80,0.2);
    }

    .preview-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.85;
        transition: opacity 0.3s;
    }

    .preview-card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.1) 55%, transparent 100%);
    }

    .preview-card-body {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1rem 0.85rem 0.85rem;
        text-align: center;
    }

    .preview-flag {
        font-size: 1.4rem;
        display: block;
        margin-bottom: 0.4rem;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4));
    }

    .preview-name {
        font-family: 'Nunito', sans-serif;
        font-size: 0.78rem;
        font-weight: 900;
        color: #fff;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        display: block;
        margin-bottom: 0.5rem;
    }

    .preview-visa {
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.75);
        border-top: 1px solid rgba(255,255,255,0.2);
        padding-top: 0.45rem;
        display: block;
    }

    .preview-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: rgba(255,255,255,0.3);
        gap: 0.5rem;
    }

    .preview-placeholder svg { opacity: 0.4; }
    .preview-placeholder span { font-size: 0.7rem; letter-spacing: 0.1em; text-transform: uppercase; }

    /* ── Layout: form + preview ── */
    .form-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.5rem;
        align-items: start;
    }

    /* ── Cards / Sections ── */
    .form-card {
        background: var(--white);
        border-radius: 14px;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 12px rgba(13,39,80,0.05);
    }

    .form-card:last-child { margin-bottom: 0; }

    .card-header {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: var(--white);
    }

    .card-header-icon {
        width: 34px;
        height: 34px;
        background: var(--pink-dim);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .card-header-icon svg { stroke: var(--pink); }

    .card-header-title {
        font-family: 'Nunito', sans-serif;
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--navy);
    }

    .card-header-sub {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 1px;
    }

    .card-body { padding: 1.5rem; }

    /* ── Form Fields ── */
    .field-row {
        display: grid;
        gap: 1.2rem;
        margin-bottom: 1.2rem;
    }

    .field-row.cols-2 { grid-template-columns: 1fr 1fr; }
    .field-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
    .field-row.cols-1 { grid-template-columns: 1fr; }

    .field-row:last-child { margin-bottom: 0; }

    .field { display: flex; flex-direction: column; gap: 0.45rem; }

    .field-label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--navy);
    }

    .field-label .req { color: var(--pink); margin-left: 2px; }

    .field-hint {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-top: -0.2rem;
    }

    /* Input base */
    .field input,
    .field select,
    .field textarea {
        width: 100%;
        background: var(--page-bg);
        border: 1.5px solid var(--border);
        color: var(--text);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        padding: 0.72rem 1rem;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        -webkit-appearance: none;
    }

    .field input::placeholder,
    .field textarea::placeholder { color: var(--text-muted); }

    .field input:focus,
    .field select:focus,
    .field textarea:focus {
        border-color: var(--pink);
        background: var(--white);
        box-shadow: 0 0 0 3px rgba(204,0,204,0.08);
    }

    .field select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238a9ab8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.85rem center;
        padding-right: 2.2rem;
        cursor: pointer;
    }

    .field textarea { resize: vertical; min-height: 90px; line-height: 1.6; }

    /* Input with icon */
    .input-wrap { position: relative; }
    .input-wrap input { padding-left: 2.6rem; }
    .input-icon {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
        transition: color 0.2s;
    }
    .input-wrap:focus-within .input-icon { color: var(--pink); }

    /* ── Image Upload ── */
    .upload-zone {
        border: 2px dashed var(--border);
        border-radius: 10px;
        padding: 2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        position: relative;
        background: var(--page-bg);
    }

    .upload-zone:hover,
    .upload-zone.drag-over {
        border-color: var(--pink);
        background: var(--pink-dim);
    }

    .upload-zone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
        padding: 0;
        border: none;
        background: none;
    }

    .upload-zone input[type="file"]:focus { box-shadow: none; }

    .upload-icon {
        width: 48px;
        height: 48px;
        background: var(--pink-dim);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
    }

    .upload-title {
        font-family: 'Nunito', sans-serif;
        font-size: 0.9rem;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 0.3rem;
    }

    .upload-sub {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .upload-sub strong { color: var(--pink); }

    /* Image preview strip */
    .img-preview-wrap {
        margin-top: 1rem;
        display: none;
    }

    .img-preview-wrap.visible { display: block; }

    .img-preview-inner {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    .img-thumb {
        width: 80px;
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid var(--pink-border);
    }

    /* ── Badge / Chip selects ── */
    .chip-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .chip {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.38rem 0.85rem;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--text-mid);
        cursor: pointer;
        transition: border-color 0.18s, background 0.18s, color 0.18s;
        background: var(--page-bg);
        user-select: none;
    }

    .chip:hover {
        border-color: var(--pink-border);
        color: var(--pink);
    }

    .chip.selected {
        border-color: var(--pink);
        background: var(--pink-dim);
        color: var(--pink);
    }

    .chip input[type="checkbox"] { display: none; }

    /* ── Visa type multi-select list ── */
    .visa-list {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .visa-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.7rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        cursor: pointer;
        transition: border-color 0.18s, background 0.18s;
        background: var(--page-bg);
    }

    .visa-item:hover { border-color: var(--pink-border); background: var(--white); }

    .visa-item.selected { border-color: var(--pink); background: var(--pink-dim); }

    .visa-item input[type="checkbox"] { display: none; }

    .visa-item-check {
        width: 18px;
        height: 18px;
        border: 2px solid var(--border);
        border-radius: 4px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color 0.18s, background 0.18s;
    }

    .visa-item.selected .visa-item-check {
        border-color: var(--pink);
        background: var(--pink);
    }

    .visa-item.selected .visa-item-check svg { display: block; }
    .visa-item-check svg { display: none; }

    .visa-item-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text);
        flex: 1;
    }

    .visa-item.selected .visa-item-label { color: var(--pink); }

    .visa-item-sub {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-top: 1px;
    }

    /* ── Status toggle ── */
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.8rem 1rem;
        background: var(--page-bg);
        border: 1.5px solid var(--border);
        border-radius: 10px;
    }

    .toggle-info { flex: 1; }
    .toggle-title { font-size: 0.85rem; font-weight: 600; color: var(--navy); }
    .toggle-sub { font-size: 0.72rem; color: var(--text-muted); margin-top: 1px; }

    .toggle-switch {
        position: relative;
        width: 42px;
        height: 23px;
        flex-shrink: 0;
    }

    .toggle-switch input { opacity: 0; width: 0; height: 0; }

    .toggle-slider {
        position: absolute;
        inset: 0;
        background: var(--border);
        border-radius: 20px;
        cursor: pointer;
        transition: background 0.25s;
    }

    .toggle-slider::before {
        content: '';
        position: absolute;
        left: 3px;
        top: 3px;
        width: 17px;
        height: 17px;
        background: white;
        border-radius: 50%;
        transition: transform 0.25s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.15);
    }

    .toggle-switch input:checked + .toggle-slider { background: var(--pink); }
    .toggle-switch input:checked + .toggle-slider::before { transform: translateX(19px); }

    /* ── Sticky right panel ── */
    .sticky-panel { position: sticky; top: 1.5rem; }

    .preview-section .form-card { margin-bottom: 1rem; }

    .preview-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 0.5rem;
    }

    .preview-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--text-muted);
    }

    /* ── Action bar ── */
    .action-bar {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.82rem 1.6rem;
        border-radius: 8px;
        font-family: 'Nunito', sans-serif;
        font-size: 0.85rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        cursor: pointer;
        border: none;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--navy);
        color: #fff;
        box-shadow: 0 4px 14px rgba(13,39,80,0.25);
        flex: 1;
        justify-content: center;
    }

    .btn-primary:hover {
        background: var(--navy-mid);
        box-shadow: 0 6px 20px rgba(13,39,80,0.35);
    }

    .btn-primary:active { transform: scale(0.98); }

    .btn-outline {
        background: var(--white);
        color: var(--text-mid);
        border: 1.5px solid var(--border);
    }

    .btn-outline:hover { border-color: var(--navy); color: var(--navy); }

    .btn-pink {
        background: var(--pink);
        color: #fff;
        box-shadow: 0 4px 14px rgba(204,0,204,0.3);
        width: 100%;
        justify-content: center;
    }

    .btn-pink:hover { background: var(--pink-light); box-shadow: 0 6px 20px rgba(204,0,204,0.4); }
    .btn-pink:active { transform: scale(0.98); }

    /* Divider */
    .section-divider {
        height: 1px;
        background: var(--border);
        margin: 1.4rem 0;
    }

    /* Tag input for "also known as" */
    .tag-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        padding: 0.55rem 0.75rem;
        background: var(--page-bg);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        cursor: text;
        min-height: 44px;
        align-items: center;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .tag-wrap:focus-within {
        border-color: var(--pink);
        background: var(--white);
        box-shadow: 0 0 0 3px rgba(204,0,204,0.08);
    }

    .tag {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        background: var(--pink-dim);
        border: 1px solid var(--pink-border);
        border-radius: 20px;
        padding: 0.18rem 0.55rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--pink);
    }

    .tag-remove {
        cursor: pointer;
        color: var(--pink);
        font-size: 0.9rem;
        line-height: 1;
        opacity: 0.7;
        transition: opacity 0.15s;
    }

    .tag-remove:hover { opacity: 1; }

    .tag-input {
        border: none;
        outline: none;
        background: transparent;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.88rem;
        color: var(--text);
        min-width: 120px;
        padding: 0.1rem 0;
        flex: 1;
    }

    @media (max-width: 1100px) {
        .form-layout { grid-template-columns: 1fr; }
        .sticky-panel { position: static; }
        .field-row.cols-3 { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 640px) {
        .page-wrap { padding: 1.25rem 1rem 3rem; }
        .field-row.cols-2,
        .field-row.cols-3 { grid-template-columns: 1fr; }
    }
</style>

<div class="page-wrap">

    {{-- Page header --}}
    <div class="page-header">
        <div>
            <div class="page-breadcrumb">
                Countries <span>/</span> Add New
            </div>
            <h1 class="page-title">Add <span>Country</span></h1>
            <p class="page-subtitle">Fill in the details to create a new destination card on the platform.</p>
        </div>
    </div>
        @if ($errors->any())
        <div style="margin-bottom:1rem; padding:1rem; border-radius:8px; background:#ffe5e5; border:1px solid #ffb3b3; color:#b30000;">
            <strong>⚠️ Please fix the following errors:</strong>
            <ul style="margin-top:0.5rem; padding-left:1.2rem;">
                @foreach ($errors->all() as $error)
                    <li style="font-size:0.85rem;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div style="margin-bottom:1rem; padding:1rem; border-radius:8px; background:#ffe5e5; border:1px solid #ffb3b3; color:#b30000;">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div style="margin-bottom:1rem; padding:1rem; border-radius:8px; background:#e6ffed; border:1px solid #b3ffcc; color:#006622;">
            {{ session('success') }}
        </div>
    @endif
    <form method="POST" action="{{ route('admin.countries.store') }}" enctype="multipart/form-data" id="addCountryForm">
        @csrf

        <div class="form-layout">

            {{-- ═══ Left: Main Form ═══ --}}
            <div class="form-main">

                {{-- 1. Basic Info --}}
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-header-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke="currentColor">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="2" y1="12" x2="22" y2="12"/>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-header-title">Basic Information</div>
                            <div class="card-header-sub">Country identity & metadata</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="field-row cols-2">
                            <div class="field">
                                <label class="field-label" for="country_name">Country Name <span class="req">*</span></label>
                                <div class="input-wrap">
                                    <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                    </svg>
                                    <input type="text" id="country_name" name="country_name"
                                           placeholder="e.g. Maldives"
                                           value="{{ old('country_name') }}"
                                           oninput="updatePreview()"
                                           required>
                                </div>
                            </div>
                            {{-- <div class="field">
                                <label class="field-label" for="country_code">Country Code <span class="req">*</span></label>
                                <div class="input-wrap">
                                    <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    <input type="text" id="country_code" name="country_code"
                                           placeholder="e.g. MV" maxlength="3"
                                           value="{{ old('country_code') }}"
                                           style="text-transform:uppercase"
                                           required>
                                </div>
                            </div> --}}
                        </div>

                        <div class="field-row cols-2">
                            <div class="field">
                                <label class="field-label" for="flag_emoji">Flag Emoji <span class="req">*</span></label>
                                <div class="input-wrap">
                                    <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                        <line x1="4" y1="22" x2="4" y2="15"/>
                                    </svg>
                                    <input type="text" id="flag_emoji" name="flag_emoji"
                                           placeholder="e.g. 🇲🇻"
                                           value="{{ old('flag_emoji') }}"
                                           oninput="updatePreview()"
                                           required>
                                </div>
                                <span class="field-hint">Paste a flag emoji directly</span>
                            </div>
                            {{-- <div class="field">
                                <label class="field-label" for="continent">Continent <span class="req">*</span></label>
                                <select id="continent" name="continent" required>
                                    <option value="" disabled {{ old('continent') ? '' : 'selected' }}>Select continent</option>
                                    @foreach(['Africa','Antarctica','Asia','Europe','North America','Oceania','South America'] as $c)
                                        <option value="{{ $c }}" {{ old('continent') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                        </div>

                        {{-- <div class="field-row cols-1">
                            <div class="field">
                                <label class="field-label" for="aliases">Also Known As / Aliases</label>
                                <div class="tag-wrap" id="tagWrap" onclick="document.getElementById('tagInput').focus()">
                                    <input type="text" id="tagInput" class="tag-input" placeholder="Type and press Enter...">
                                    <input type="hidden" name="aliases" id="aliasesHidden" value="{{ old('aliases') }}">
                                </div>
                                <span class="field-hint">Alternate names used in search (e.g. Maldive Islands)</span>
                            </div>
                        </div> --}}

                        {{-- <div class="field-row cols-1">
                            <div class="field">
                                <label class="field-label" for="description">Short Description</label>
                                <textarea id="description" name="description" rows="3"
                                    placeholder="Brief description shown on the destination card...">{{ old('description') }}</textarea>
                            </div>
                        </div> --}}
                    </div>
                </div>

                {{-- 2. Card Image --}}
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-header-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-header-title">Card Image</div>
                            <div class="card-header-sub">Background photo for the destination card</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="field-row cols-1">
                            <div class="field">
                                <label class="field-label">Upload Image <span class="req">*</span></label>
                                <div class="upload-zone" id="uploadZone">
                                    <input type="file" name="card_image" id="cardImage"
                                           accept="image/*" onchange="handleImagePreview(this)">
                                    <div class="upload-icon">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--pink)" stroke-width="2">
                                            <polyline points="16 16 12 12 8 16"/>
                                            <line x1="12" y1="12" x2="12" y2="21"/>
                                            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                                        </svg>
                                    </div>
                                    <div class="upload-title">Click or drag & drop image</div>
                                    <div class="upload-sub">
                                        <strong>JPG, PNG, WebP</strong> — Recommended: 800×1200px (portrait)
                                    </div>
                                </div>
                                <div class="img-preview-wrap" id="imgPreviewWrap">
                                    <div class="img-preview-inner" id="imgPreviewInner"></div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="field-row cols-1">
                            <div class="field">
                                <label class="field-label" for="image_alt">Image Alt Text</label>
                                <input type="text" id="image_alt" name="image_alt"
                                       placeholder="e.g. Aerial view of Maldives crystal waters"
                                       value="{{ old('image_alt') }}">
                                <span class="field-hint">Used for accessibility and SEO</span>
                            </div>
                        </div> --}}
                    </div>
                </div>

                {{-- 3. Visa Settings --}}
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-header-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-header-title">Visa Information</div>
                            <div class="card-header-sub">Visa status, types and requirements</div>
                        </div>
                    </div>
                    <div class="card-body">

                        {{-- Visa Status chips --}}
                        <div class="field-row cols-1">
                            <div class="field">
                                <label class="field-label">Visa Status <span class="req">*</span></label>
                                <div class="chip-group" id="visaStatusGroup">
                                    @php
                                        $statuses = [
                                            'No Visa Required'  => '🟢',
                                            'Visa on Arrival'   => '🟡',
                                            'e-Visa'            => '🔵',
                                            'Visa Required'     => '🔴',
                                        ];
                                    @endphp
                                    @foreach($statuses as $label => $icon)
                                        <label class="chip {{ old('visa_status') == $label ? 'selected' : '' }}">
                                            <input type="radio" name="visa_status" value="{{ $label }}"
                                                   {{ old('visa_status') == $label ? 'checked' : '' }}
                                                   onchange="updatePreview(); document.querySelectorAll('.chip').forEach(c=>c.classList.remove('selected')); this.closest('.chip').classList.add('selected')">
                                            {{ $icon }} {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        {{-- Visa Types --}}
                        {{-- <div class="field-row cols-1">
                            <div class="field">
                                <label class="field-label">Available Visa Types</label>
                                <div class="visa-list">
                                    @foreach($visa_type as $vt)
                                    <label class="visa-item {{ in_array($vt->id, old('visa_types', [])) ? 'selected' : '' }}"
                                           onclick="this.classList.toggle('selected'); this.querySelector('input').checked = !this.querySelector('input').checked">
                                        <input type="checkbox" name="visa_types[]"
                                               value="{{ $vt->id }}"
                                               {{ in_array($vt->id, old('visa_types', [])) ? 'checked' : '' }}>
                                        <span class="visa-item-check">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                        </span>
                                        <span>
                                            <span class="visa-item-label">{{ $vt->name }}</span>
                                            @if($vt->description ?? null)
                                            <span class="visa-item-sub">{{ $vt->description }}</span>
                                            @endif
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div> --}}

                        <div class="section-divider"></div>

                        {{-- Required Documents --}}
                        <div class="field-row cols-1">
                            <div class="field">
                                <label class="field-label">Required Documents</label>
                                <div class="chip-group">
                                    @foreach($visa_type_document as $doc)
                                    <label class="chip {{ in_array($doc->id, old('documents', [])) ? 'selected' : '' }}"
                                           onclick="this.classList.toggle('selected'); this.querySelector('input').checked = !this.querySelector('input').checked">
                                        <input type="checkbox" name="documents[]"
                                               value="{{ $doc->id }}"
                                               {{ in_array($doc->id, old('documents', [])) ? 'checked' : '' }}>
                                        📄 {{ $doc->name }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        <div class="field-row cols-2">
                            <div class="field">
                                <label class="field-label" for="visa_fee">Visa Fee (USD)</label>
                                <div class="input-wrap">
                                    <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <line x1="12" y1="1" x2="12" y2="23"/>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                    </svg>
                                    <input type="number" id="visa_fee" name="visa_fee"
                                           placeholder="0.00" min="0" step="0.01"
                                           value="{{ old('visa_fee') }}">
                                </div>
                                <span class="field-hint">Enter 0 for free / no visa</span>
                            </div>
                            <div class="field">
                                <label class="field-label" for="processing_days">Processing Time (days)</label>
                                <div class="input-wrap">
                                    <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    <input type="number" id="processing_days" name="processing_days"
                                           placeholder="e.g. 5" min="0"
                                           value="{{ old('processing_days') }}">
                                </div>
                            </div>
                        </div>

                        <div class="field-row cols-2">
                            <div class="field">
                                <label class="field-label" for="stay_duration">Max Stay Duration (days)</label>
                                <div class="input-wrap">
                                    <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    <input type="number" id="stay_duration" name="stay_duration"
                                           placeholder="e.g. 30" min="1"
                                           value="{{ old('stay_duration') }}">
                                </div>
                            </div>
                            <div class="field">
                                <label class="field-label" for="validity_months">Visa Validity (days)</label>
                                <div class="input-wrap">
                                    <svg class="input-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    </svg>
                                    <input type="number" id="validity_months" name="validity_days"
                                           placeholder="e.g. 6" min="1"
                                           value="{{ old('validity_days') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

              

            </div>{{-- /form-main --}}

            {{-- ═══ Right: Sticky Preview & Publish ═══ --}}
            <div class="sticky-panel preview-section">

                {{-- Live Preview --}}
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-header-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-header-title">Card Preview</div>
                            <div class="card-header-sub">Live preview of destination card</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="preview-wrap">
                            <div class="preview-card" id="livePreview">
                                <div class="preview-placeholder" id="previewPlaceholder">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                    <span>Upload image</span>
                                </div>
                                <img id="previewImg" src="" alt="" class="preview-card-img" style="display:none">
                                <div class="preview-card-overlay" id="previewOverlay" style="display:none"></div>
                                <div class="preview-card-body" id="previewBody" style="display:none">
                                    <span class="preview-flag" id="previewFlag">🏳️</span>
                                    <span class="preview-name" id="previewName">COUNTRY NAME</span>
                                    <span class="preview-visa" id="previewVisa">VISA STATUS</span>
                                </div>
                            </div>
                            <span class="preview-label">Destination card preview</span>
                        </div>
                    </div>
                </div>

                {{-- Publish settings --}}
                <div class="form-card">
                    <div class="card-header">
                        <div class="card-header-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-header-title">Publish Settings</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="display:flex;flex-direction:column;gap:.65rem;">

                            <div class="toggle-row">
                                <div class="toggle-info">
                                    <div class="toggle-title">Publish Immediately</div>
                                    <div class="toggle-sub">Make visible on the site</div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="is_published" value="1"
                                           {{ old('is_published') ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="toggle-row">
                                <div class="toggle-info">
                                    <div class="toggle-title">Feature on Homepage</div>
                                    <div class="toggle-sub">Show in featured destinations</div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="is_featured" value="1"
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="toggle-row">
                                <div class="toggle-info">
                                    <div class="toggle-title">Visa Free Eligible</div>
                                    <div class="toggle-sub">Appears in "Visa Free" filter</div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="is_visa_free" value="1"
                                           {{ old('is_visa_free') ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                        </div>

                        <div style="height:1rem"></div>

                        <div class="action-bar" style="flex-direction:column;gap:.65rem">
                            <button type="submit" class="btn btn-pink">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                Save Country
                            </button>
                            <div class="action-bar">
                                <button type="button" class="btn btn-outline"
                                        onclick="document.getElementById('addCountryForm').reset(); resetPreview()">
                                    Reset
                                </button>
                                <a href="#" class="btn btn-outline" style="flex:1;justify-content:center">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /sticky-panel --}}

        </div>{{-- /form-layout --}}
    </form>
</div>

<script>
    /* ── Live Preview ── */
    function updatePreview() {
        const name  = document.getElementById('country_name').value.trim();
        const flag  = document.getElementById('flag_emoji').value.trim();
        const vEl   = document.querySelector('input[name="visa_status"]:checked');
        const visa  = vEl ? vEl.value.toUpperCase() : '';

        const hasContent = name || flag;
        document.getElementById('previewPlaceholder').style.display = hasContent ? 'none' : 'flex';
        document.getElementById('previewBody').style.display = hasContent ? 'block' : 'none';
        document.getElementById('previewOverlay').style.display = hasContent ? 'block' : 'none';

        if (name) document.getElementById('previewName').textContent = name.toUpperCase();
        if (flag) document.getElementById('previewFlag').textContent = flag;
        if (visa) document.getElementById('previewVisa').textContent = visa;
    }

    function resetPreview() {
        document.getElementById('previewPlaceholder').style.display = 'flex';
        document.getElementById('previewBody').style.display = 'none';
        document.getElementById('previewOverlay').style.display = 'none';
        document.getElementById('previewImg').style.display = 'none';
        document.getElementById('imgPreviewWrap').classList.remove('visible');
        document.getElementById('imgPreviewInner').innerHTML = '';
        tags = [];
        renderTags();
    }

    /* ── Image Preview ── */
    function handleImagePreview(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = document.getElementById('previewImg');
            img.src = e.target.result;
            img.style.display = 'block';
            document.getElementById('previewPlaceholder').style.display = 'none';
            document.getElementById('previewOverlay').style.display = 'block';
            document.getElementById('previewBody').style.display = 'block';

            // Thumb strip
            const wrap = document.getElementById('imgPreviewWrap');
            const inner = document.getElementById('imgPreviewInner');
            inner.innerHTML = '';
            const thumb = document.createElement('img');
            thumb.src = e.target.result;
            thumb.className = 'img-thumb';
            inner.appendChild(thumb);
            wrap.classList.add('visible');
        };
        reader.readAsDataURL(file);
    }

    /* ── Drag & Drop ── */
    const zone = document.getElementById('uploadZone');
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        const dt = e.dataTransfer;
        if (dt.files.length) {
            document.getElementById('cardImage').files = dt.files;
            handleImagePreview(document.getElementById('cardImage'));
        }
    });

    /* ── Tag / Alias input ── */
    let tags = [];

    function renderTags() {
        const wrap = document.getElementById('tagWrap');
        const input = document.getElementById('tagInput');
        // Remove all tags (keep the input)
        wrap.querySelectorAll('.tag').forEach(t => t.remove());
        tags.forEach((tag, i) => {
            const el = document.createElement('span');
            el.className = 'tag';
            el.innerHTML = `${tag}<span class="tag-remove" onclick="removeTag(${i})">×</span>`;
            wrap.insertBefore(el, input);
        });
        document.getElementById('aliasesHidden').value = tags.join(',');
    }

    function removeTag(i) { tags.splice(i, 1); renderTags(); }

    document.getElementById('tagInput').addEventListener('keydown', function(e) {
        if ((e.key === 'Enter' || e.key === ',') && this.value.trim()) {
            e.preventDefault();
            tags.push(this.value.trim());
            this.value = '';
            renderTags();
        } else if (e.key === 'Backspace' && !this.value && tags.length) {
            tags.pop();
            renderTags();
        }
    });

    /* ── Auto-slug from name ── */
    document.getElementById('country_name').addEventListener('input', function() {
        const slug = document.getElementById('slug');
        if (!slug.dataset.manual) {
            slug.value = this.value.toLowerCase().trim().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
        }
        updatePreview();
    });

    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.manual = 'true';
    });
</script>

@endsection