<div id="passport-preview" class="vd-modal">
    <div class="vd-modal-content">

        <h3 style="margin-bottom:15px;">Verify Passport Details</h3>

        <form id="passport-form">
            @csrf

            <div class="vd-form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" id="full_name">
            </div>

            <div class="vd-form-group">
                <label>Passport No.</label>
                <input type="text" name="passport_number" id="passport_number">
            </div>

            <div class="vd-form-group">
                <label>Gender</label>
                <input type="text" name="sex" id="sex">
            </div>

            <div class="vd-form-group">
                <label>Date of Birth</label>
                <input type="text" name="dob" id="dob">
            </div>

            <div class="vd-form-group">
                <label>Expiry Date</label>
                <input type="text" name="expiry" id="expiry">
            </div>

            <div class="vd-form-group">
                <label>Country</label>
                <input type="text" name="country" id="country">
            </div>

            <div class="vd-form-group">
                <label>Place of Issue</label>
                <input type="text" name="place_of_issue" id="place_of_issue">
            </div>

            <!-- ✅ Required fields -->
            <div class="vd-form-group">
                <label>Mobile *</label>
                <input type="text" name="mobile" id="mobile" required>
            </div>

            <div class="vd-form-group">
                <label>Email *</label>
                <input type="email" name="email" id="email" required>
            </div>

            <button type="submit" class="vd-btn">Continue</button>
        </form>

    </div>
</div>
<style>
    .vd-modal {
    display: none; /* hidden by default */

    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    background: rgba(0, 0, 0, 0.5); /* dark overlay */

    justify-content: center;
    align-items: center;

    z-index: 9999;
}

.vd-modal.open {
    display: flex;
}

/* Modal Box */
.vd-modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    width: 400px;
    max-height: 90vh;
    overflow-y: auto;

    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.vd-form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 12px;
}

.vd-form-group label {
    font-size: 12px;
    color: #666;
    margin-bottom: 4px;
}

.vd-form-group input {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.vd-btn {
    width: 100%;
    padding: 10px;
    background: black;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
</style>
<script>
/* ─────────────────────────────────────────
   OPEN PREVIEW MODAL (LOCKED)
───────────────────────────────────────── */
function openPreviewModal(data) {
    // Fill fields
    document.getElementById('full_name').value =
        (data.first_name || '') + ' ' + (data.last_name || '');

    document.getElementById('passport_number').value = data.passport_number || '';
    document.getElementById('sex').value = data.sex || '';
    document.getElementById('dob').value = data.dob || '';
    document.getElementById('expiry').value = data.expiry || '';
    document.getElementById('country').value = data.country || '';
    document.getElementById('place_of_issue').value = data.place_of_issue || '';

    // Show modal
    const modal = document.getElementById('passport-preview');
    modal.classList.add('open');

    // 🔒 LOCK BACKGROUND
    document.body.style.overflow = 'hidden';

      // ✅ Only lock scroll (NOT pointer events)
    document.body.style.overflow = 'hidden';
}


/* ─────────────────────────────────────────
   SUBMIT FORM (ONLY EXIT WAY)
───────────────────────────────────────── */
document.getElementById('passport-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const mobile = document.getElementById('mobile').value.trim();
    const email  = document.getElementById('email').value.trim();

    // ✅ REQUIRED VALIDATION
    if (!mobile || !email) {
        alert("Mobile and Email are required!");
        return;
    }

    const mobileRegex = /^[0-9]{10}$/;
    const emailRegex  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!mobileRegex.test(mobile)) {
        alert("Enter valid 10-digit mobile number");
        return;
    }

    if (!emailRegex.test(email)) {
        alert("Enter valid email address");
        return;
    }

    const formData = new FormData(this);

    fetch('/save-passport-data', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert("Saved successfully!");

        closePreview(); // ✅ ONLY EXIT
    })
    .catch(err => {
        console.error(err);
        alert("Error saving data");
    });
});


/* ─────────────────────────────────────────
   CLOSE PREVIEW (UNLOCK)
───────────────────────────────────────── */
function closePreview() {
    const modal = document.getElementById('passport-preview');
    modal.classList.remove('open');

    document.body.style.overflow = 'auto';
}


/* ─────────────────────────────────────────
   BLOCK OUTSIDE CLICK (NO ESCAPE)
───────────────────────────────────────── */
document.getElementById('passport-preview').addEventListener('click', function(e) {
    if (e.target === this) {
        e.stopPropagation(); // ❌ block outside click
    }
});


/* ─────────────────────────────────────────
   BLOCK ESC KEY (IMPORTANT)
───────────────────────────────────────── */
document.addEventListener('keydown', function(e) {
    const modalOpen = document.getElementById('passport-preview')?.classList.contains('open');

    if (modalOpen && e.key === "Escape") {
        e.preventDefault();
    }
});
</script>