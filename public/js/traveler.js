/* ─────────────────────────────────────────────────────────
   traveler.js  —  State, rendering, modal, navigation
   HTML markup lives in components/js-templates.blade.php
───────────────────────────────────────────────────────── */

/* ─── Shared state ─── */
let travelers       = [{ name: '', uploads: { photo: null, front: null, back: null }, passport: null }];
let currentTraveler = 0;
let currentType     = '';
let currentStream   = null;
let capturedData    = null;
let currentTab      = 'camera';
let modelsReady     = false;

/* ─────────────────────────────────────────────────────────
   Template helper — clone a <template> by id
───────────────────────────────────────────────────────── */
function cloneTemplate(id) {
    const tmpl = document.getElementById(id);
    if (!tmpl) {
        console.error(`Template #${id} not found. Did you include js-templates.blade.php?`);
        return document.createDocumentFragment();
    }
    return tmpl.content.cloneNode(true);
}

/* ─────────────────────────────────────────────────────────
   Render all traveler cards
───────────────────────────────────────────────────────── */
function renderTravelers() {
    const list = document.getElementById('travelers-list');
    list.innerHTML = '';

    travelers.forEach((t, i) => {
        const okCount = Object.values(t.uploads).filter(u => u && u.status === 'ok').length;
        const total   = 3;
        const pillCls = okCount === total ? 'done' : okCount > 0 ? 'partial' : '';
        const pillTxt = okCount === total ? 'Complete' : `${okCount}/${total} docs`;

        const initials = t.name
            ? t.name.trim().split(/\s+/).map(w => w[0]).join('').toUpperCase().slice(0, 2)
            : String(i + 1);

        /* ── Clone card template ── */
        const frag = cloneTemplate('tmpl-traveler-card');
        const card = frag.querySelector('.traveler-card');

        /* ── Head: avatar ── */
        const avatar = card.querySelector('[data-avatar]');
        avatar.id          = `avatar-${i}`;
        avatar.textContent = initials;

        /* ── Head: name + progress ── */
        const nameEl = card.querySelector('[data-tc-name]');
        nameEl.id          = `tcname-${i}`;
        nameEl.textContent = t.name || `Traveler ${i + 1}`;

        card.querySelector('[data-tc-prog]').textContent =
            okCount === total ? 'All documents uploaded ✓' : 'Upload required documents';

        /* ── Head: pill ── */
        const pill = card.querySelector('[data-pill]');
        pill.className   = `tc-pill ${pillCls}`;
        pill.textContent = pillTxt;

        /* ── Head: chevron + toggle ── */
        const chevron = card.querySelector('[data-chevron]');
        chevron.id = `chev-${i}`;
        if (i === 0) chevron.classList.add('open');

        const head = card.querySelector('[data-toggle-card]');
        head.addEventListener('click', () => toggleCard(i));

        /* ── Body: open state ── */
        const body = card.querySelector('[data-body]');
        body.id = `body-${i}`;
        if (i === 0) body.classList.add('open');

        /* ── Body: name input ── */
        const nameInput = card.querySelector('[data-name-input]');
        nameInput.value = t.name || '';
        nameInput.addEventListener('input', e => updateName(i, e.target.value));

        /* ── Body: upload boxes ── */
        const uploadRow = card.querySelector('[data-upload-row]');
        [
            { type: 'photo', icon: '👤', label: 'Passport Photo', hint: 'Portrait / face photo' },
            { type: 'front', icon: '📄', label: 'Passport Front', hint: 'Bio data page'         },
            { type: 'back',  icon: '📋', label: 'Passport Back',  hint: 'Last / signature page' },
        ].forEach(({ type, icon, label, hint }) => {
            uploadRow.appendChild(buildUploadBox(i, type, icon, label, hint));
        });

        /* ── Body: remove button ── */
        const removeSlot = card.querySelector('[data-remove-btn]');
        if (i > 0) {
            const btnFrag = cloneTemplate('tmpl-remove-btn');
            const btn     = btnFrag.querySelector('[data-remove-traveler]');
            btn.addEventListener('click', () => removeTraveler(i));
            removeSlot.appendChild(btnFrag);
        }

        list.appendChild(card);
    });

    checkNextButton();
}

/* ─── Build one upload box from templates ─── */
function buildUploadBox(ti, type, icon, label, hint) {
    const u       = travelers[ti].uploads[type];
    const isOk    = u && u.status === 'ok';
    const isInval = u && u.status !== 'ok';

    /* Choose the right template */
    const tmplId = isOk ? 'tmpl-ubox-uploaded' : 'tmpl-ubox';
    const frag   = cloneTemplate(tmplId);
    const box    = frag.querySelector('[data-ubox]');

    /* Invalid state adds a CSS class */
    if (isInval) box.classList.add('invalid');

    /* Populate common fields */
    box.querySelector('[data-ubox-icon]').textContent  = icon;
    box.querySelector('[data-ubox-hint]').textContent  = hint;

    const labelEl = box.querySelector('[data-ubox-label]');
    labelEl.textContent = isOk ? 'Uploaded ✓' : isInval ? 'Invalid — tap to retry' : label;

    /* Thumbnail src for uploaded state */
    if (isOk) {
        box.querySelector('[data-ubox-preview]').src = u.src;
        box.querySelector('[data-ubox-preview]').alt = label;
    }

    /* Passport details button — front page only, when passport data exists */
    if (type === 'front' && travelers[ti].passport) {
        const btnFrag = cloneTemplate('tmpl-passport-btn');
        const btn     = btnFrag.querySelector('[data-passport-btn]');
        btn.addEventListener('click', e => {
            e.stopPropagation();
            openPreviewModal(travelers[ti].passport);
        });
        box.appendChild(btnFrag);
    }

    /* Click to open upload modal */
    box.addEventListener('click', () => openModal(type, ti));

    return box;
}

/* ─── Toggle card open/close ─── */
function toggleCard(i) {
    document.getElementById(`body-${i}`).classList.toggle('open');
    document.getElementById(`chev-${i}`).classList.toggle('open');
}

/* ─── Update traveler name live (no full re-render) ─── */
function updateName(i, val) {
    travelers[i].name = val;
    const initials = val.trim()
        ? val.trim().split(/\s+/).map(w => w[0]).join('').toUpperCase().slice(0, 2)
        : String(i + 1);
    const av = document.getElementById(`avatar-${i}`);
    const nm = document.getElementById(`tcname-${i}`);
    if (av) av.textContent = initials;
    if (nm) nm.textContent = val || 'Traveler ' + (i + 1);
}

/* ─── Add / remove travelers ─── */
function addTraveler() {
    travelers.push({ name: '', uploads: { photo: null, front: null, back: null }, passport: null });
    renderTravelers();
    const idx = travelers.length - 1;
    setTimeout(() => {
        const body = document.getElementById(`body-${idx}`);
        const chev = document.getElementById(`chev-${idx}`);
        if (body) body.classList.add('open');
        if (chev)  chev.classList.add('open');
    }, 30);
}

function removeTraveler(i) {
    if (travelers.length <= 1) return;
    travelers.splice(i, 1);
    if (currentTraveler >= travelers.length) currentTraveler = travelers.length - 1;
    renderTravelers();
}

/* ─── Enable / disable Next button ─── */
function checkNextButton() {
    const allDone = travelers.every(t =>
        Object.values(t.uploads).every(u => u && u.status === 'ok')
    );
    const btn = document.getElementById('nextBtn');
    if (btn) btn.disabled = !allDone;
}

/* ─── Navigate to payment ─── */
async function goNext() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        const res = await fetch('/save-travelers', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ travelers }),
        });

        if (!res.ok) {
            console.error('Save error:', res.status);
            alert('Failed to save data');
            return;
        }

        const data = await res.json();
        if (data.status === 'success') {
            window.location.href = '/payment';
        } else {
            alert('Something went wrong');
        }
    } catch (err) {
        console.error(err);
        alert('Network error');
    }
}

/* ─────────────────────────────────────────────────────────
   Upload Modal — open / close / reset
───────────────────────────────────────────────────────── */
function openModal(type, ti) {
    /* If front page already uploaded and passport data exists,
       clicking the box re-opens the passport preview directly */
    if (type === 'front' && travelers[ti].passport && travelers[ti].uploads.front?.status === 'ok') {
        openPreviewModal(travelers[ti].passport);
        return;
    }

    currentType     = type;
    currentTraveler = ti;
    capturedData    = null;

    const titles = {
        photo: 'Upload Passport Photo',
        front: 'Passport Front Page',
        back:  'Passport Back Page',
    };
    document.getElementById('modal-title-text').textContent  = titles[type] || 'Upload Document';
    document.getElementById('passport-rules').style.display = type === 'photo' ? 'block' : 'none';

    resetModal();
    switchTab('camera');

    document.getElementById('modal-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    stopCamera();
    document.getElementById('modal-overlay').classList.remove('open');
    document.body.style.overflow = '';
}

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

    ['face', 'single', 'center', 'size', 'blur', 'bright'].forEach(id => setCheck(id, '', '—'));

    if (currentType !== 'photo') {
        hideRow('row-single');
        hideRow('row-center');
        hideRow('row-size');
        hideRow('row-bright');
    } else {
        showRow('row-single');
        showRow('row-center');
        showRow('row-size');
        showRow('row-bright');
    }
}

/* ─── Confirm upload and save to traveler ─── */
function confirmUpload() {
    if (!capturedData) return;

    travelers[currentTraveler].uploads[currentType] = {
        src:    capturedData,
        status: 'ok',
    };

    closeModal();
    renderTravelers();

    if (currentType === 'front') {
        scanPassportOCR(capturedData);
    }
}

/* ─────────────────────────────────────────────────────────
   Passport Preview Modal
───────────────────────────────────────────────────────── */
function closePreview() {
    document.getElementById('passport-preview').classList.remove('open');
    document.body.style.overflow = 'auto';
}

/* ─────────────────────────────────────────────────────────
   UI helpers
───────────────────────────────────────────────────────── */
function setCheck(id, status, val) {
    const dot = document.getElementById('dot-' + id);
    const v   = document.getElementById('val-' + id);
    if (!dot || !v) return;
    dot.className = 'check-dot' + (status ? ' ' + status : '');
    v.className   = 'check-val'  + (status ? ' ' + status : '');
    v.textContent = val;
}

function showRow(id) {
    const r = document.getElementById(id);
    if (r) r.style.display = 'flex';
}

function hideRow(id) {
    const r = document.getElementById(id);
    if (r) r.style.display = 'none';
}

function escapeHtml(str) {
    return (str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

function escapeAttr(str) {
    return (str || '')
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

/* ─── Close modal when clicking backdrop ─── */
document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('modal-overlay');
    if (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
    }

    const preview = document.getElementById('passport-preview');
    if (preview) {
        preview.addEventListener('click', function (e) {
            if (e.target === this) {
                e.stopPropagation(); // 🔒 DON'T CLOSE on backdrop click
            }
        });
    }
});

/* ─── Boot ─── */
renderTravelers();