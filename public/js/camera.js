/* ─────────────────────────────────────────────────────────
   camera.js  —  Camera / file-upload / preview / retake
───────────────────────────────────────────────────────── */

/* ─── Tab switch ─── */
function switchTab(tab) {
    currentTab = tab;
    document.getElementById('tab-camera').classList.toggle('active', tab === 'camera');
    document.getElementById('tab-file').classList.toggle('active',   tab === 'file');

    if (tab === 'file') {
        stopCamera();
        document.getElementById('live-video').style.display    = 'none';
        document.getElementById('guide-overlay').style.display = 'none';
        document.getElementById('btn-capture').textContent     = 'Choose File';
        document.getElementById('btn-capture').style.display   = 'block';
        document.getElementById('btn-capture').onclick         = () => {
            document.getElementById('file-input').value = '';
            document.getElementById('file-input').click();
        };
    } else {
        document.getElementById('live-video').style.display    = 'block';
        document.getElementById('guide-overlay').style.display = currentType === 'photo' ? 'flex' : 'none';
        document.getElementById('btn-capture').textContent     = 'Capture Photo';
        document.getElementById('btn-capture').style.display   = 'block';
        document.getElementById('btn-capture').onclick         = capturePhoto;
        startCamera();
    }
}

/* ─── Start / stop camera ─── */
function startCamera() {
    navigator.mediaDevices
        .getUserMedia({ video: { facingMode: 'user', width: 640, height: 480 } })
        .then(stream => {
            currentStream = stream;
            const v = document.getElementById('live-video');
            v.srcObject     = stream;
            v.style.display = 'block';
        })
        .catch(err => {
            console.warn('Camera unavailable, falling back to file upload:', err);
            switchTab('file');
        });
}

function stopCamera() {
    if (currentStream) {
        currentStream.getTracks().forEach(t => t.stop());
        currentStream = null;
    }
}

/* ─── Capture from live video ─── */
function capturePhoto() {
    const video = document.getElementById('live-video');
    if (!video.videoWidth) {
        alert('Camera not ready yet. Please wait a moment.');
        return;
    }
    const canvas = document.createElement('canvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    capturedData = canvas.toDataURL('image/jpeg', 0.92);
    stopCamera();
    showCaptured(capturedData);
    analyzeImage(capturedData);
}

/* ─── Handle file upload ─── */
function handleFileUpload(e) {
    const file = e.target.files[0];
    if (!file) return;

    // Validate it is an image
    if (!file.type.startsWith('image/')) {
        alert('Please select an image file.');
        return;
    }

    const reader = new FileReader();
    reader.onload = ev => {
        capturedData = ev.target.result;
        showCaptured(capturedData);
        analyzeImage(capturedData);
    };
    reader.onerror = () => alert('Failed to read file.');
    reader.readAsDataURL(file);
}

/* ─── Show the captured / uploaded image ─── */
function showCaptured(src) {
    document.getElementById('live-video').style.display    = 'none';
    document.getElementById('guide-overlay').style.display = 'none';

    const img = document.getElementById('preview-img');
    img.src           = src;
    img.style.display = 'block';

    document.getElementById('btn-capture').style.display = 'none';
    document.getElementById('btn-retake').classList.add('show');
    document.getElementById('loading-bar').classList.add('active');
    document.getElementById('quality-panel').classList.add('open');
}

/* ─── Retake / re-upload ─── */
function retakePhoto() {
    capturedData = null;

    const img = document.getElementById('preview-img');
    img.style.display = 'none';
    img.src           = '';

    document.getElementById('btn-retake').classList.remove('show');
    document.getElementById('btn-confirm').classList.remove('show');
    document.getElementById('quality-panel').classList.remove('open');
    document.getElementById('loading-bar').classList.remove('active');

    // Reset all check dots
    ['face', 'single', 'center', 'size', 'blur', 'bright'].forEach(id => setCheck(id, '', '—'));

    if (currentTab === 'camera') {
        document.getElementById('btn-capture').style.display   = 'block';
        document.getElementById('btn-capture').textContent     = 'Capture Photo';
        document.getElementById('btn-capture').onclick         = capturePhoto;
        document.getElementById('live-video').style.display    = 'block';
        document.getElementById('guide-overlay').style.display = currentType === 'photo' ? 'flex' : 'none';
        startCamera();
    } else {
        document.getElementById('btn-capture').style.display = 'block';
        document.getElementById('btn-capture').textContent   = 'Choose File';
        document.getElementById('btn-capture').onclick       = () => {
            document.getElementById('file-input').value = '';
            document.getElementById('file-input').click();
        };
    }
}