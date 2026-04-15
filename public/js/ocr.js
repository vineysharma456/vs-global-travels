/* ─────────────────────────────────────────────────────────
   ocr.js  —  face-api analysis + passport OCR
   HTML markup lives in components/js-templates.blade.php
───────────────────────────────────────────────────────── */

/* ─── Load face-api models once ─── */
(async () => {
    try {
        const MODEL_PATH = '/models'; // serve from your public/models directory
        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_PATH),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_PATH),
        ]);
        modelsReady = true;
        console.log('face-api models loaded ✓');
    } catch (e) {
        console.warn('face-api models failed to load:', e);
        modelsReady = false;
    }
})();

/* ─────────────────────────────────────────────────────────
   analyzeImage — runs quality checks
───────────────────────────────────────────────────────── */
async function analyzeImage(src) {
    const img = document.getElementById('preview-img');

    await new Promise(res => {
        if (img.complete && img.naturalWidth) { res(); return; }
        img.onload  = res;
        img.onerror = res;
    });

    const blur       = calcBlur(img);
    const brightness = calcBrightness(img);

    /* ── Passport pages ── */
    if (currentType !== 'photo') {
        document.getElementById('loading-bar').classList.remove('active');

        hideRow('row-single');
        hideRow('row-center');
        hideRow('row-size');
        hideRow('row-bright');

        setCheck('face',   '', 'N/A');
        setCheck('single', '', 'N/A');
        setCheck('center', '', 'N/A');
        setCheck('size',   '', 'N/A');
        setCheck('blur',   blur > 18 ? 'pass' : 'fail', blur > 18 ? 'Sharp' : 'Blurry');
        setCheck('bright', '', 'N/A');

        document.getElementById('btn-confirm').classList.add('show');
        return;
    }

    /* ── Passport photo ── */
    showRow('row-single');
    showRow('row-center');
    showRow('row-size');
    showRow('row-bright');

    if (!modelsReady) {
        document.getElementById('loading-bar').classList.remove('active');

        setCheck('face',   'warn', 'Models unavailable');
        setCheck('single', '', 'N/A');
        setCheck('center', '', 'N/A');
        setCheck('size',   '', 'N/A');
        setCheck('blur',   blur > 18 ? 'pass' : 'fail', blur > 18 ? 'Sharp' : 'Blurry');
        setCheck('bright',
            brightness > 55 && brightness < 220 ? 'pass' : 'fail',
            brightness > 55 && brightness < 220 ? 'Good' : 'Poor'
        );

        document.getElementById('btn-confirm').classList.add('show');
        return;
    }

    try {
        const detections = await faceapi.detectAllFaces(
            img,
            new faceapi.TinyFaceDetectorOptions({ inputSize: 416, scoreThreshold: 0.38 })
        );

        document.getElementById('loading-bar').classList.remove('active');

        const count = detections.length;

        if (count === 0) {
            setCheck('face',   'fail', 'Not found');
            setCheck('single', '', 'N/A');
            setCheck('center', '', 'N/A');
            setCheck('size',   '', 'N/A');
        } else {
            setCheck('face', 'pass', 'Detected');

            if (count > 1) {
                setCheck('single', 'fail', `${count} faces`);
                setCheck('center', '', 'N/A');
                setCheck('size',   '', 'N/A');
            } else {
                setCheck('single', 'pass', 'Only 1');

                const box = detections[0].box;
                const iw  = img.naturalWidth  || img.width;
                const ih  = img.naturalHeight || img.height;

                const facePct  = (box.width * box.height) / (iw * ih);
                const cx       = (box.x + box.width  / 2) / iw;
                const cy       = (box.y + box.height / 2) / ih;

                const centered = cx > 0.28 && cx < 0.72 && cy > 0.22 && cy < 0.78;
                const goodSize = facePct > 0.07 && facePct < 0.72;

                setCheck('center', centered ? 'pass' : 'fail', centered ? 'Centered' : 'Off-center');
                setCheck('size',   goodSize  ? 'pass' : 'fail',
                    goodSize ? `${Math.round(facePct * 100)}%`
                             : facePct < 0.07 ? 'Too small' : 'Too close'
                );
            }
        }

        setCheck('blur', blur > 18 ? 'pass' : 'fail', blur > 18 ? 'Sharp' : 'Blurry');
        setCheck('bright',
            brightness > 55 && brightness < 220 ? 'pass' : 'fail',
            brightness > 55 && brightness < 220 ? 'Good' : 'Poor'
        );

    } catch (err) {
        console.error('face-api detection error:', err);
        document.getElementById('loading-bar').classList.remove('active');
        setCheck('face', 'warn', 'Check failed');
    }

    document.getElementById('btn-confirm').classList.add('show');
}

/* ─────────────────────────────────────────────────────────
   OCR API CALL
───────────────────────────────────────────────────────── */
async function scanPassportOCR(base64) {
    showOCRStatus('Scanning passport…');

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        const res = await fetch('/scan-passport', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ image: base64 }),
        });

        if (!res.ok) {
            console.error('OCR HTTP error', res.status);
            showOCRStatus('');
            showOCRError(`Server error (${res.status})`);
            return;
        }

        const data = await res.json();

        if (data.status === 'error') {
            showOCRStatus('');
            showOCRError(data.message || 'OCR failed');
            return;
        }

        showOCRStatus('');
        showPassportData(data.data);

    } catch (err) {
        console.error('OCR fetch error:', err);
        showOCRStatus('');
        showOCRError('Network error while scanning');
    }
}

/* ─────────────────────────────────────────────────────────
   SAVE + OPEN PREVIEW
───────────────────────────────────────────────────────── */
function showPassportData(data) {
    if (!data) {
        showOCRError('No passport data returned');
        return;
    }

    const traveler = travelers[currentTraveler];

    traveler.passport = data;

    const fullName = [data.first_name, data.last_name].filter(Boolean).join(' ').trim();
    if (fullName) traveler.name = fullName;

    renderTravelers();
    openPreviewModal(traveler.passport);
}

/* ─────────────────────────────────────────────────────────
   OCR UI HELPERS
───────────────────────────────────────────────────────── */
function showOCRStatus(msg) {
    if (msg) console.info('OCR:', msg);
}

function showOCRError(msg) {
    console.warn('OCR Error:', msg);

    if (!travelers[currentTraveler].passport) {
        travelers[currentTraveler].passport = {};
    }

    openPreviewModal(travelers[currentTraveler].passport);

    /* ── Use the Blade template for the error banner ── */
    setTimeout(() => {
        const box = document.getElementById('passport-data-box');
        if (!box) return;

        const tmpl   = document.getElementById('tmpl-ocr-error');
        const banner = tmpl
            ? tmpl.content.cloneNode(true).querySelector('[data-ocr-error]')
            : document.createElement('div');

        banner.textContent = `⚠️ ${msg}`;
        box.prepend(banner);
    }, 50);
}

/* ─────────────────────────────────────────────────────────
   Image utilities
───────────────────────────────────────────────────────── */
function calcBlur(img) {
    try {
        const c = document.createElement('canvas');
        c.width  = Math.min(img.naturalWidth  || 200, 200);
        c.height = Math.min(img.naturalHeight || 200, 200);
        const ctx = c.getContext('2d');
        ctx.drawImage(img, 0, 0, c.width, c.height);
        const d = ctx.getImageData(0, 0, c.width, c.height).data;
        let sum = 0, cnt = 0;
        for (let i = 0; i < d.length - 4; i += 16) {
            sum += Math.abs(d[i] - d[i + 4]);
            cnt++;
        }
        return cnt > 0 ? sum / cnt : 50;
    } catch (e) { return 50; }
}

function calcBrightness(img) {
    try {
        const c = document.createElement('canvas');
        c.width = c.height = 80;
        c.getContext('2d').drawImage(img, 0, 0, 80, 80);
        const d = c.getContext('2d').getImageData(0, 0, 80, 80).data;
        let s = 0;
        for (let i = 0; i < d.length; i += 4) {
            s += (d[i] + d[i + 1] + d[i + 2]) / 3;
        }
        return s / (d.length / 4);
    } catch (e) { return 128; }
}