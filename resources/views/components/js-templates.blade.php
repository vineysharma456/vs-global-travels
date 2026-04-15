{{-- ─────────────────────────────────────────────────────────
     components/js-templates.blade.php
     HTML templates consumed by traveler.js, camera.js, ocr.js
     Edit markup here — no need to touch the JS files.
─────────────────────────────────────────────────────────── --}}

{{-- ══════════════════════════════════════════════
     TRAVELER CARD
     Placeholders: __INITIALS__ __INDEX__ __NAME__ __PROG__
                   __PILL_CLS__ __PILL_TXT__
                   __CHEVRON_OPEN__ __BODY_OPEN__
                   __UPLOAD_ROW__ __REMOVE_BTN__
════════════════════════════════════════════════ --}}
<template id="tmpl-traveler-card">
    <div class="traveler-card">
        <div class="tc-head" data-toggle-card>
            <div class="tc-avatar" data-avatar></div>
            <div class="tc-meta">
                <div class="tc-name"  data-tc-name></div>
                <div class="tc-prog"  data-tc-prog></div>
            </div>
            <span class="tc-pill"    data-pill></span>
            <span class="tc-chevron" data-chevron>▼</span>
        </div>
        <div class="tc-body" data-body>
            <input
                class="name-input"
                placeholder="Full name as on passport"
                data-name-input
            />
            <div class="section-label">Documents</div>
            <div class="upload-row" data-upload-row></div>
            <div data-remove-btn></div>
            <div style="clear:both"></div>
        </div>
    </div>
</template>

{{-- ══════════════════════════════════════════════
     REMOVE TRAVELER BUTTON
     Placeholders: data-index set by JS
════════════════════════════════════════════════ --}}
<template id="tmpl-remove-btn">
    <button class="remove-traveler-btn" data-remove-traveler>✕ Remove</button>
</template>

{{-- ══════════════════════════════════════════════
     UPLOAD BOX  —  empty / invalid state
     Placeholders: data attributes set by JS
════════════════════════════════════════════════ --}}
<template id="tmpl-ubox">
    <div class="ubox" data-ubox>
        <span class="ubox-icon"  data-ubox-icon></span>
        <span class="ubox-label" data-ubox-label></span>
        <span class="ubox-hint"  data-ubox-hint></span>
    </div>
</template>

{{-- ══════════════════════════════════════════════
     UPLOAD BOX  —  uploaded (ok) state
     Includes thumbnail preview
════════════════════════════════════════════════ --}}
<template id="tmpl-ubox-uploaded">
    <div class="ubox uploaded" data-ubox>
        <img class="ubox-preview" data-ubox-preview alt="" />
        <span class="ubox-icon"  data-ubox-icon></span>
        <span class="ubox-label" data-ubox-label>Uploaded ✓</span>
        <span class="ubox-hint"  data-ubox-hint></span>
    </div>
</template>

{{-- ══════════════════════════════════════════════
     PASSPORT DETAILS BUTTON
     Injected inside the "front" upload box when passport data exists
════════════════════════════════════════════════ --}}
<template id="tmpl-passport-btn">
    <button type="button" class="ubox-passport-btn" data-passport-btn>
        📋 View Passport Details
    </button>
</template>

{{-- ══════════════════════════════════════════════
     OCR ERROR BANNER
     Injected at the top of #passport-data-box on OCR failure
════════════════════════════════════════════════ --}}
<template id="tmpl-ocr-error">
    <div class="ocr-error-banner" data-ocr-error></div>
</template>