@extends('layouts.sidenav')

@section('title', 'Manage Country Images')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap');

    .ci-page * { box-sizing: border-box; }
    .ci-page { font-family: 'DM Sans', sans-serif; padding: 2rem; max-width: 900px; margin: 0 auto; }

    /* Header */
    .ci-header { display: flex; align-items: baseline; gap: 12px; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb; }
    .ci-header h3 { font-size: 20px; font-weight: 500; color: #111827; margin: 0; }
    .ci-header .ci-count { font-size: 13px; color: #6b7280; background: #f3f4f6; padding: 3px 10px; border-radius: 20px; border: 1px solid #e5e7eb; }

    /* Section label */
    .ci-section-label { font-size: 11px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase; color: #9ca3af; margin-bottom: 12px; }

    /* Upload card */
    .ci-upload-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem; }
    .ci-col-header { display: grid; grid-template-columns: 1fr 110px 36px; gap: 10px; padding: 0 12px; margin-bottom: 6px; }
    .ci-col-header span { font-size: 11px; color: #9ca3af; font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase; }

    .ci-upload-rows { display: flex; flex-direction: column; gap: 10px; margin-bottom: 14px; }

    .ci-upload-row { display: grid; grid-template-columns: 1fr 110px 36px; gap: 10px; align-items: center; padding: 10px 12px; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; transition: border-color 0.15s; }
    .ci-upload-row:hover { border-color: #d1d5db; }

    .ci-file-label { display: flex; align-items: center; gap: 8px; cursor: pointer; overflow: hidden; }
    .ci-file-icon { width: 28px; height: 28px; background: #eff6ff; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ci-file-icon svg { width: 14px; height: 14px; fill: #3b82f6; }
    .ci-file-name { font-size: 13px; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ci-file-input { display: none; }

    .ci-seq-input { width: 100%; padding: 6px 10px; font-size: 13px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff; color: #111827; text-align: center; font-family: inherit; transition: border-color 0.15s, box-shadow 0.15s; }
    .ci-seq-input:focus { outline: none; border-color: #6b7280; box-shadow: 0 0 0 2px rgba(0,0,0,0.06); }

    .ci-remove-btn { width: 28px; height: 28px; border: 1px solid #e5e7eb; background: transparent; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #9ca3af; transition: background 0.15s, color 0.15s, border-color 0.15s; padding: 0; }
    .ci-remove-btn:hover { background: #fef2f2; border-color: #fca5a5; color: #ef4444; }
    .ci-remove-btn svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; }

    .ci-row-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .ci-add-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; font-size: 13px; font-family: inherit; background: transparent; border: 1px solid #d1d5db; border-radius: 8px; cursor: pointer; color: #6b7280; transition: background 0.15s; }
    .ci-add-btn:hover { background: #f3f4f6; }
    .ci-submit-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 20px; font-size: 13px; font-weight: 500; font-family: inherit; background: #111827; color: #fff; border: none; border-radius: 8px; cursor: pointer; transition: opacity 0.15s; }
    .ci-submit-btn:hover { opacity: 0.85; }

    /* Divider */
    .ci-divider { height: 1px; background: #e5e7eb; margin: 2rem 0; }

    /* Grid section */
    .ci-grid-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
    .ci-hint { font-size: 12px; color: #9ca3af; }

    /* Save order button */
    .ci-save-order-btn { display: none; align-items: center; gap: 6px; padding: 6px 14px; font-size: 12px; font-weight: 500; font-family: inherit; background: #111827; color: #fff; border: none; border-radius: 8px; cursor: pointer; transition: opacity 0.15s; }
    .ci-save-order-btn.visible { display: inline-flex; }
    .ci-save-order-btn:hover { opacity: 0.85; }

    /* Image grid */
    .ci-img-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 14px; }

    .ci-img-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; cursor: grab; transition: box-shadow 0.2s, transform 0.15s, border-color 0.15s; user-select: none; position: relative; }
    .ci-img-card:active { cursor: grabbing; }
    .ci-img-card:hover { border-color: #d1d5db; }
    .ci-img-card.ci-dragging { opacity: 0.35; transform: scale(0.96); }
    .ci-img-card.ci-drag-over { border: 2px dashed #3b82f6; background: #eff6ff; }

    .ci-img-thumb { width: 100%; height: 110px; object-fit: cover; display: block; pointer-events: none; }
    .ci-img-placeholder { width: 100%; height: 110px; background: #f9fafb; display: flex; align-items: center; justify-content: center; }
    .ci-img-placeholder svg { width: 28px; height: 28px; fill: #d1d5db; }

    .ci-img-footer { padding: 8px 10px; display: flex; align-items: center; justify-content: space-between; }
    .ci-seq-badge { display: inline-block; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 20px; padding: 2px 8px; font-size: 11px; font-weight: 500; color: #6b7280; }
    .ci-drag-handle { display: flex; align-items: center; color: #d1d5db; }
    .ci-drag-handle svg { width: 12px; height: 12px; fill: currentColor; }

    /* Delete button on card */
    .ci-img-delete { position: absolute; top: 6px; right: 6px; width: 24px; height: 24px; background: rgba(17,24,39,0.6); border: none; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.15s; }
    .ci-img-card:hover .ci-img-delete { opacity: 1; }
    .ci-img-delete svg { width: 11px; height: 11px; stroke: #fff; fill: none; stroke-width: 2; stroke-linecap: round; }

    /* Empty state */
    .ci-empty { text-align: center; padding: 3rem 1rem; color: #9ca3af; font-size: 13px; }

    /* Toast */
    .ci-toast { position: fixed; bottom: 24px; right: 24px; background: #111827; color: #fff; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-family: 'DM Sans', sans-serif; opacity: 0; transition: opacity 0.3s, transform 0.3s; pointer-events: none; z-index: 9999; transform: translateY(8px); }
    .ci-toast.ci-show { opacity: 1; transform: translateY(0); }

    /* Alert */
    .ci-alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-bottom: 1rem; }
    .ci-alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-bottom: 1rem; }
</style>

<div class="ci-page">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="ci-alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="ci-alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Header --}}
    <div class="ci-header">
        <h3>{{ $country->country_name }} — Images</h3>
        <span class="ci-count">{{ $country->images->count() }} {{ Str::plural('image', $country->images->count()) }}</span>
    </div>

    {{-- Upload Form --}}
    <div class="ci-upload-card">
        <p class="ci-section-label">Upload new images</p>

        <form action="{{ route('admin.country.images.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <input type="hidden" name="country_id" value="{{ $country->id }}">

            <div class="ci-col-header">
                <span>File</span>
                <span>Sequence</span>
                <span></span>
            </div>

            <div class="ci-upload-rows" id="uploadRows">
                <div class="ci-upload-row" id="row-1">
                    <label class="ci-file-label" for="ci-f1">
                        <div class="ci-file-icon">
                            <svg viewBox="0 0 16 16"><path d="M2 2h7l3 3v9H2V2z"/><path fill="white" d="M9 2v3h3"/></svg>
                        </div>
                        <span class="ci-file-name" id="ci-fn-1">Click to choose image…</span>
                    </label>
                    <input class="ci-file-input" type="file" name="images[]" id="ci-f1" accept="image/*" required onchange="ciUpdateName(this, 'ci-fn-1')">
                    <input class="ci-seq-input" type="number" name="sequence[]" placeholder="1" min="1" required>
                    <button type="button" class="ci-remove-btn" onclick="ciRemoveRow('row-1')" title="Remove">
                        <svg viewBox="0 0 14 14"><line x1="2" y1="2" x2="12" y2="12"/><line x1="12" y1="2" x2="2" y2="12"/></svg>
                    </button>
                </div>
            </div>

            <div class="ci-row-actions">
                <button type="button" class="ci-add-btn" onclick="ciAddRow()">+ Add more</button>
                <button type="submit" class="ci-submit-btn">Upload images</button>
            </div>
        </form>
    </div>

    <div class="ci-divider"></div>

    {{-- Existing Images --}}
    <div class="ci-grid-top">
        <p class="ci-section-label" style="margin-bottom:0">Existing images</p>
        <div style="display:flex;align-items:center;gap:10px">
            <span class="ci-hint">Drag cards to reorder sequence</span>
            <button class="ci-save-order-btn" id="ciSaveOrderBtn" onclick="ciSaveOrder()">Save order</button>
        </div>
    </div>

    {{-- Hidden form to save sequence order --}}
    <form action="{{-- route('admin.country.images.reorder') --}}" method="POST" id="ciReorderForm">
        @csrf
        @method('PATCH')
        <input type="hidden" name="country_id" value="{{ $country->id }}">
        <div id="ciOrderInputs"></div>
    </form>

    <div class="ci-img-grid" id="ciImgGrid">
        @forelse($country->images->sortBy('sequence') as $image)
            <div class="ci-img-card" draggable="true" data-id="{{ $image->id }}" data-seq="{{ $image->sequence }}">
                <img
                    src="{{ asset('storage/' . $image->image) }}"
                    alt="Image {{ $image->sequence }}"
                    class="ci-img-thumb"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                >
                <div class="ci-img-placeholder" style="display:none">
                    <svg viewBox="0 0 24 24"><path d="M21 19V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2zM8.5 13.5l2.5 3 3.5-4.5 4.5 6H5l3.5-4.5z"/></svg>
                </div>

                {{-- Delete button --}}
                <form action="{{ route('admin.country.images.destroy', $image->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this image?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="ci-img-delete" title="Delete image">
                        <svg viewBox="0 0 14 14"><line x1="2" y1="2" x2="12" y2="12"/><line x1="12" y1="2" x2="2" y2="12"/></svg>
                    </button>
                </form>

                <div class="ci-img-footer">
                    <div class="ci-drag-handle">
                        <svg viewBox="0 0 12 12"><circle cx="3" cy="3" r="1"/><circle cx="3" cy="6" r="1"/><circle cx="3" cy="9" r="1"/><circle cx="9" cy="3" r="1"/><circle cx="9" cy="6" r="1"/><circle cx="9" cy="9" r="1"/></svg>
                    </div>
                    <span class="ci-seq-badge">Seq {{ $image->sequence }}</span>
                </div>
            </div>
        @empty
            <div class="ci-empty" style="grid-column: 1/-1">No images uploaded yet.</div>
        @endforelse
    </div>

</div>

<div class="ci-toast" id="ciToast"></div>

<script>
    let ciRowCount = 1;
    let ciDragSrc = null;
    let ciOrderChanged = false;

    /* ── Upload rows ── */
    function ciUpdateName(input, labelId) {
        const f = input.files[0];
        document.getElementById(labelId).textContent = f ? f.name : 'Click to choose image…';
    }

    function ciAddRow() {
        ciRowCount++;
        const id = 'row-' + ciRowCount;
        const fnId = 'ci-fn-' + ciRowCount;
        const div = document.createElement('div');
        div.className = 'ci-upload-row';
        div.id = id;
        div.innerHTML = `
            <label class="ci-file-label" for="ci-f${ciRowCount}">
                <div class="ci-file-icon">
                    <svg viewBox="0 0 16 16"><path d="M2 2h7l3 3v9H2V2z"/><path fill="white" d="M9 2v3h3"/></svg>
                </div>
                <span class="ci-file-name" id="${fnId}">Click to choose image…</span>
            </label>
            <input class="ci-file-input" type="file" name="images[]" id="ci-f${ciRowCount}" accept="image/*" required onchange="ciUpdateName(this,'${fnId}')">
            <input class="ci-seq-input" type="number" name="sequence[]" placeholder="${ciRowCount}" min="1" required>
            <button type="button" class="ci-remove-btn" onclick="ciRemoveRow('${id}')" title="Remove">
                <svg viewBox="0 0 14 14"><line x1="2" y1="2" x2="12" y2="12"/><line x1="12" y1="2" x2="2" y2="12"/></svg>
            </button>
        `;
        document.getElementById('uploadRows').appendChild(div);

        // div.querySelector('.ci-file-label').addEventListener('click', () => {
        //     div.querySelector('input[type=file]').click();
        // });
    }

    function ciRemoveRow(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    /* Click-to-browse for first row */
    // document.querySelector('#uploadRows').addEventListener('click', function(e) {
    //     const label = e.target.closest('.ci-file-label');
    //     if (label) {
    //         const input = label.parentElement.querySelector('input[type=file]');
    //         if (input) input.click();
    //     }
    // });

    /* ── Toast ── */
    function ciShowToast(msg) {
        const t = document.getElementById('ciToast');
        t.textContent = msg;
        t.classList.add('ci-show');
        setTimeout(() => t.classList.remove('ci-show'), 2400);
    }

    /* ── Reindex seq badges ── */
    function ciReindex() {
        const cards = document.querySelectorAll('#ciImgGrid .ci-img-card');
        cards.forEach((c, i) => {
            c.dataset.seq = i + 1;
            c.querySelector('.ci-seq-badge').textContent = 'Seq ' + (i + 1);
        });
    }

    /* ── Drag & Drop ── */
    const grid = document.getElementById('ciImgGrid');

    grid.addEventListener('dragstart', e => {
        ciDragSrc = e.target.closest('.ci-img-card');
        if (!ciDragSrc) return;
        setTimeout(() => ciDragSrc.classList.add('ci-dragging'), 0);
        e.dataTransfer.effectAllowed = 'move';
    });

    grid.addEventListener('dragend', e => {
        const card = e.target.closest('.ci-img-card');
        if (card) card.classList.remove('ci-dragging');
        document.querySelectorAll('.ci-img-card').forEach(c => c.classList.remove('ci-drag-over'));
        ciReindex();
        ciOrderChanged = true;
        document.getElementById('ciSaveOrderBtn').classList.add('visible');
        ciShowToast('Sequence updated — click "Save order" to persist');
    });

    grid.addEventListener('dragover', e => {
        e.preventDefault();
        const target = e.target.closest('.ci-img-card');
        if (!target || target === ciDragSrc) return;
        document.querySelectorAll('.ci-img-card').forEach(c => c.classList.remove('ci-drag-over'));
        target.classList.add('ci-drag-over');
        e.dataTransfer.dropEffect = 'move';
    });

    grid.addEventListener('dragleave', e => {
        const target = e.target.closest('.ci-img-card');
        if (target) target.classList.remove('ci-drag-over');
    });

    grid.addEventListener('drop', e => {
        e.preventDefault();
        const target = e.target.closest('.ci-img-card');
        if (!target || !ciDragSrc || target === ciDragSrc) return;
        target.classList.remove('ci-drag-over');
        const cards = [...grid.querySelectorAll('.ci-img-card')];
        const si = cards.indexOf(ciDragSrc);
        const ti = cards.indexOf(target);
        if (si < ti) grid.insertBefore(ciDragSrc, target.nextSibling);
        else grid.insertBefore(ciDragSrc, target);
    });

    /* ── Save order via form submit ── */
    function ciSaveOrder() {
        const cards = document.querySelectorAll('#ciImgGrid .ci-img-card');
        const container = document.getElementById('ciOrderInputs');
        container.innerHTML = '';
        cards.forEach((card, i) => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'order[]';
            inp.value = card.dataset.id;
            container.appendChild(inp);
        });
        document.getElementById('ciReorderForm').submit();
    }
</script>

@endsection