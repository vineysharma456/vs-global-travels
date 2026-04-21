{{-- ─────────────────────────────────────────────────────────
     components/upload-modal.blade.php
     Single modal instance — reused for every upload box.
     JS populates currentTraveler / currentType before opening.
─────────────────────────────────────────────────────────── --}}

<div class="modal-overlay" id="modal-overlay">
    <div class="modal-box">

        {{-- Header --}}
        <div class="modal-title-row">
            <span class="modal-title-text" id="modal-title-text">Upload Document</span>
            <button class="modal-close" onclick="VD_closeModal()">✕</button>
        </div>

        {{-- Tabs --}}
        <div class="modal-tabs">
            <button class="tab-btn active" id="tab-camera" onclick="switchTab('camera')">📷 Camera</button>
            <button class="tab-btn"        id="tab-file"   onclick="switchTab('file')">📁 Upload File</button>
        </div>

        {{-- Passport photo rules (shown only for type=photo) --}}
        <div class="passport-rules" id="passport-rules" style="display:none">
            <strong>Passport photo requirements:</strong><br>
            Plain white or light background &nbsp;·&nbsp;
            Face must be centered and fully visible &nbsp;·&nbsp;
            No glasses, hats, or accessories &nbsp;·&nbsp;
            Neutral expression, mouth closed &nbsp;·&nbsp;
            Recent photo (within 6 months)
        </div>

        {{-- Camera / preview area --}}
        <div class="preview-area" id="preview-area">
            <video id="live-video" autoplay playsinline muted></video>
            <img  id="preview-img" alt="Document preview" style="display:none" />
            <div  class="face-guide-overlay" id="guide-overlay" style="display:none">
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
                <span class="check-val"  id="val-face">—</span>
            </div>
            <div class="check-item" id="row-single">
                <div class="check-dot" id="dot-single"></div>
                <span class="check-label">Single face only</span>
                <span class="check-val"  id="val-single">—</span>
            </div>
            <div class="check-item" id="row-center">
                <div class="check-dot" id="dot-center"></div>
                <span class="check-label">Face centered in frame</span>
                <span class="check-val"  id="val-center">—</span>
            </div>
            <div class="check-item" id="row-size">
                <div class="check-dot" id="dot-size"></div>
                <span class="check-label">Face fills frame (30–70%)</span>
                <span class="check-val"  id="val-size">—</span>
            </div>
            <div class="check-item">
                <div class="check-dot" id="dot-blur"></div>
                <span class="check-label">Image sharpness</span>
                <span class="check-val"  id="val-blur">—</span>
            </div>
            <div class="check-item" id="row-bright">
                <div class="check-dot" id="dot-bright"></div>
                <span class="check-label">Good lighting</span>
                <span class="check-val"  id="val-bright">—</span>
            </div>
        </div>

        {{-- Hidden file input --}}
        <input type="file" id="file-input" accept="image/*" onchange="handleFileUpload(event)" />

        {{-- Action buttons --}}
        <div class="modal-actions">
            <button class="btn-capture" id="btn-capture" onclick="capturePhoto()">Capture Photo</button>
            <button class="btn-retake"  id="btn-retake"  onclick="retakePhoto()">Retake</button>
            <button class="btn-confirm" id="btn-confirm" onclick="VD_confirmUpload()">Use This Photo</button>
        </div>

    </div>
</div>