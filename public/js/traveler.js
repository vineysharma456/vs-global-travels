/* ---------------------------------------------------------
   traveler.js  —  Minimal JS: state sync, modal, navigation
   All HTML is rendered server-side by Blade components.
   This file NEVER builds DOM strings.
--------------------------------------------------------- */

/* --- Runtime state (mirrors server-rendered HTML) --- */
let currentTraveler = 0;
let currentType     = '';
let currentDocId    = null;
let capturedData    = null;

/* --- Cached upload data (photo, front, back, doc-{id}) --- */
//  Seeded from window.VD.travelers set in the Blade view
const travelers = window.VD?.travelers ?? [];

/* =======================================================
   CARD TOGGLE  (pure DOM, no re-render)
======================================================= */
document.addEventListener('click', function (e) {
    const head = e.target.closest('[data-toggle]');
    if (!head) return;
    const i = head.dataset.toggle;
    document.getElementById(`body-${i}`)?.classList.toggle('open');
    document.getElementById(`chev-${i}`)?.classList.toggle('open');
});

/* =======================================================
   NAME INPUT  (live avatar + title update, no re-render)
======================================================= */
document.addEventListener('input', function (e) {
    const input = e.target.closest('[data-name-input]');
    if (!input) return;
    const i   = parseInt(input.dataset.nameInput, 10);
    const val = input.value;
    travelers[i].name = val;

    const initials = val.trim()
        ? val.trim().split(/\s+/).map(w => w[0].toUpperCase()).join('').slice(0, 2)
        : String(i + 1);

    const av = document.getElementById(`avatar-${i}`);
    const nm = document.getElementById(`tcname-${i}`);
    if (av) av.textContent = initials;
    if (nm) nm.textContent = val || `Traveler ${i + 1}`;
});

/* =======================================================
   ADD TRAVELER  — asks server for a rendered card fragment
======================================================= */
document.getElementById('btn-add-traveler')?.addEventListener('click', async () => {
    const res = await fetch(window.VD.addTravelerUrl, {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.VD.csrfToken,
            'Accept':       'text/html',
        },
        body: JSON.stringify({
            index:       travelers.length,
            countryId:   window.VD.countryId,
        }),
    });

    if (!res.ok) { alert('Could not add traveler'); return; }

    const html = await res.text();
    const list = document.getElementById('travelers-list');
    list.insertAdjacentHTML('beforeend', html);

    const idx = travelers.length;
    travelers.push({ name: '', uploads: {}, passport: null });

    // Open the new card
    setTimeout(() => {
        document.getElementById(`body-${idx}`)?.classList.add('open');
        document.getElementById(`chev-${idx}`)?.classList.add('open');
    }, 30);

    checkNextButton();
});

/* =======================================================
   REMOVE TRAVELER
======================================================= */
document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-remove-traveler]');
    if (!btn) return;
    const i = parseInt(btn.dataset.removeTraveler, 10);
    if (travelers.length <= 1) return;

    travelers.splice(i, 1);
    document.getElementById(`traveler-card-${i}`)?.remove();

    // Re-index remaining cards in the DOM
    document.querySelectorAll('.traveler-card').forEach((card, newIdx) => {
        card.id                       = `traveler-card-${newIdx}`;
        card.dataset.travelerIndex    = newIdx;
        const head = card.querySelector('[data-toggle]');
        if (head) head.dataset.toggle = newIdx;
        ['avatar','tcname','chev','body'].forEach(p => {
            const el = card.querySelector(`[id^="${p}-"]`);
            if (el) el.id = `${p}-${newIdx}`;
        });
    });

    checkNextButton();
});

/* =======================================================
   UPLOAD MODAL — open
   Called from onclick="VD_openModal(this)" on each .ubox
======================================================= */
window.VD_openModal = function (boxEl) {
    const ti    = parseInt(boxEl.dataset.traveler, 10);
    const type  = boxEl.dataset.type;
    const docId = boxEl.dataset.docId;

    // If front already uploaded and passport data exists → show preview
    if (type === 'front' && travelers[ti]?.passport && travelers[ti]?.uploads?.front?.status === 'ok') {
        VD_openPassportPreview(ti);
        return;
    }

    currentTraveler = ti;
    currentType     = type;
    currentDocId    = docId;
    capturedData    = null;

    // Modal title
    const titles = { photo: 'Upload Passport Photo', front: 'Passport Front Page', back: 'Passport Back Page' };
    document.getElementById('modal-title-text').textContent =
        titles[type] ?? boxEl.querySelector('.ubox-label')?.textContent?.replace(/Uploaded ✓|Invalid.*/, '').trim() ?? 'Upload Document';

    // Show passport rules only for photo
    document.getElementById('passport-rules').style.display = type === 'photo' ? 'block' : 'none';

    resetModal();
    switchTab('camera');

    document.getElementById('modal-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
};

/* --- Close modal --- */
window.VD_closeModal = function () {
    stopCamera();
    document.getElementById('modal-overlay').classList.remove('open');
    document.body.style.overflow = '';
};

/* --- Reset modal state --- */
function resetModal() {
    capturedData = null;

    document.getElementById('quality-panel').classList.remove('open');
    document.getElementById('loading-bar').classList.remove('active');

    const img = document.getElementById('preview-img');
    img.style.display = 'none';
    img.src           = '';

    document.getElementById('live-video').style.display    = 'block';
    document.getElementById('guide-overlay').style.display = currentType === 'photo' ? 'flex' : 'none';

    document.getElementById('btn-capture').style.display = 'block';
    document.getElementById('btn-capture').textContent   = 'Capture Photo';
    document.getElementById('btn-capture').onclick       = capturePhoto;
    document.getElementById('btn-retake').classList.remove('show');
    document.getElementById('btn-confirm').classList.remove('show');

    ['face','single','center','size','blur','bright'].forEach(id => setCheck(id, '', '—'));

    const photoOnlyRows = ['row-single','row-center','row-size','row-bright'];
    photoOnlyRows.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = currentType === 'photo' ? 'flex' : 'none';
    });
}

/* =======================================================
   CONFIRM UPLOAD  — update DOM box in-place (no full re-render)
======================================================= */
window.VD_confirmUpload = function () {
    if (!capturedData) return;

    // Save to runtime state
    if (!travelers[currentTraveler]) travelers[currentTraveler] = { name:'', uploads:{}, passport:null };
    travelers[currentTraveler].uploads[currentType] = { src: capturedData, status: 'ok' };

    // Update the matching upload box in-place
    const box = document.querySelector(
        `.ubox[data-traveler="${currentTraveler}"][data-type="${currentType}"]`
    );
    if (box) {
        box.classList.remove('invalid');
        box.classList.add('uploaded');

        // Add / update thumbnail
        let thumb = box.querySelector('.ubox-preview');
        if (!thumb) {
            thumb = document.createElement('img');
            thumb.className = 'ubox-preview';
            thumb.alt       = '';
            box.prepend(thumb);
        }
        thumb.src = capturedData;

        box.querySelector('.ubox-label').textContent = 'Uploaded ✓';
    }

    // Update card-level pill + progress text
    _refreshCardMeta(currentTraveler);

    VD_closeModal();

    if (currentType === 'front') {
        scanPassportOCR(capturedData);          // defined in ocr.js
    }
};

/* --- Refresh pill / progress without re-rendering the card --- */
function _refreshCardMeta(i) {
    const t       = travelers[i];
    const uploads = t?.uploads ?? {};

    const card = document.querySelector(`.traveler-card[data-traveler-index="${i}"]`);
    if (!card) return;

    // Count total boxes from DOM, not from uploads keys
    const total   = card.querySelectorAll('[data-ubox]').length;
    const okCount = Object.values(uploads).filter(u => u?.status === 'ok').length;

    const pill = card.querySelector('.tc-pill');
    const prog = card.querySelector('.tc-prog');

    if (pill) {
        pill.textContent = okCount === total ? 'Complete' : `${okCount}/${total} docs`;
        pill.className   = 'tc-pill ' + (okCount === total ? 'done' : okCount > 0 ? 'partial' : '');
    }
    if (prog) {
        prog.textContent = okCount === total ? 'All documents uploaded ✓' : 'Upload required documents';
    }

    checkNextButton();
}

/* =======================================================
   PASSPORT PREVIEW
======================================================= */
window.VD_openPassportPreview = function (ti) {
    openPreviewModal(travelers[ti]?.passport);  // defined in ocr.js
};

/* =======================================================
   NEXT BUTTON
======================================================= */
function checkNextButton() {
    const allDone = travelers.every((t, i) => {
        const card = document.querySelector(`.traveler-card[data-traveler-index="${i}"]`);
        if (!card) return false;

        // Total required = number of upload boxes rendered in DOM
        const total   = card.querySelectorAll('[data-ubox]').length;
        const uploads = t?.uploads ?? {};
        const okCount = Object.values(uploads).filter(u => u?.status === 'ok').length;

        return total > 0 && okCount === total;
    });

    const btn = document.getElementById('nextBtn');
    if (btn) btn.disabled = !allDone;
}

document.getElementById('nextBtn')?.addEventListener('click', async () => {
    try {
        const res = await fetch(window.VD.saveTravelersUrl, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.VD.csrfToken,
                'Accept':       'application/json',
            },
            body: JSON.stringify({ travelers }),
        });

        if (!res.ok) { alert('Failed to save data'); return; }
        const data = await res.json();
        if (data.status === 'success') window.location.href = '/payment';
        else alert('Something went wrong');
    } catch (err) {
        console.error(err);
        alert('Network error');
    }
});

/* =======================================================
   MODAL BACKDROP CLOSE
======================================================= */
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('modal-overlay')?.addEventListener('click', function (e) {
        if (e.target === this) VD_closeModal();
    });
});

/* =======================================================
   SHARED UI HELPERS  (used by camera.js)
======================================================= */
function setCheck(id, status, val) {
    const dot = document.getElementById('dot-' + id);
    const v   = document.getElementById('val-' + id);
    if (!dot || !v) return;
    dot.className = 'check-dot' + (status ? ' ' + status : '');
    v.className   = 'check-val'  + (status ? ' ' + status : '');
    v.textContent = val;
}

function showRow(id) { const r = document.getElementById(id); if (r) r.style.display = 'flex'; }
function hideRow(id) { const r = document.getElementById(id); if (r) r.style.display = 'none';  }