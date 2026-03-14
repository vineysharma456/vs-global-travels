@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

<style>
  :root {
    --brand-navy:  #0d2750;
    --brand-pink:  #cc00cc;
    --brand-blue:  #4f6ef7;
    --brand-blue-hover: #3a56e0;
    --input-bg:    #f0f3fb;
    --input-border:#e2e8f5;
    --label-color: #4a5568;
  }

  /* ── Card ─────────────────────────────── */
  .booking-card {
    background: #fff;
    border-radius: 18px;
    border: 1.5px solid #e8edf8;
    box-shadow: 0 8px 40px rgba(13,39,80,.08);
    padding: 2rem 2.2rem 2.4rem;
    max-width: 700px;
    margin: 3rem auto;
    font-family: 'DM Sans', 'Segoe UI', sans-serif;
  }

  /* ── Trip Type Tabs ───────────────────── */
  .trip-tabs {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    margin-bottom: 1.8rem;
    flex-wrap: wrap;
  }
  .trip-tab {
    background: transparent;
    border: 2px solid transparent;
    border-radius: 50px;
    padding: 0.3rem 1.1rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: #5a6a82;
    cursor: pointer;
    transition: all .2s;
  }
  .trip-tab:hover { color: var(--brand-blue); }
  .trip-tab.active {
    border-color: var(--brand-blue);
    color: var(--brand-blue);
    background: rgba(79,110,247,.06);
  }

  /* ── Labels ───────────────────────────── */
  .form-label-custom {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--label-color);
    margin-bottom: 0.45rem;
    display: block;
  }

  /* ── Inputs & Selects ─────────────────── */
  .form-control-custom,
  .form-select-custom {
    background: var(--input-bg);
    border: 1.5px solid var(--input-border);
    border-radius: 10px;
    padding: 0.62rem 1rem;
    font-size: 0.9rem;
    color: #2a2a2a;
    width: 100%;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    appearance: none;
    -webkit-appearance: none;
  }
  .form-control-custom::placeholder { color: #a0aec0; }
  .form-control-custom:focus,
  .form-select-custom:focus {
    border-color: var(--brand-blue);
    box-shadow: 0 0 0 3px rgba(79,110,247,.13);
    background: #fff;
  }

  /* Select wrapper (custom arrow) */
  .select-wrap {
    position: relative;
  }
  .select-wrap::after {
    content: '';
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 0; height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 6px solid #a0aec0;
    pointer-events: none;
  }
  .select-wrap select { padding-right: 2.2rem; cursor: pointer; }

  /* Date input wrapper (calendar icon) */
  .date-wrap {
    position: relative;
  }
  .date-wrap input[type="date"] {
    padding-right: 2.5rem;
    cursor: pointer;
  }
  .date-wrap input[type="date"]::-webkit-calendar-picker-indicator {
    opacity: 0;
    position: absolute;
    right: 0; top: 0;
    width: 100%; height: 100%;
    cursor: pointer;
  }
  .date-wrap .cal-icon {
    position: absolute;
    right: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    font-size: 1rem;
    pointer-events: none;
  }

  /* ── Divider ──────────────────────────── */
  .form-divider {
    border: none;
    border-top: 1.5px solid #edf0f8;
    margin: 1.6rem 0;
  }

  /* ── Next Button ──────────────────────── */
  .btn-next {
    background: var(--brand-blue);
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 0.62rem 3rem;
    font-size: 0.95rem;
    font-weight: 700;
    font-family: 'Nunito', 'Segoe UI', sans-serif;
    letter-spacing: 0.02em;
    cursor: pointer;
    transition: background .2s, box-shadow .2s, transform .15s;
    display: block;
    margin: 0 auto;
  }
  .btn-next:hover {
    background: var(--brand-blue-hover);
    box-shadow: 0 6px 20px rgba(79,110,247,.35);
    transform: translateY(-1px);
  }
  .btn-next:active { transform: translateY(0); }

  /* ── Round Trip extra fields ──────────────── */
  .return-date-row { display: none; }
  .return-date-row.show { display: flex; }
</style>

{{-- Booking Form Card --}}
<div class="booking-card">

  {{-- Trip Type Tabs --}}
  <div class="trip-tabs" id="tripTabs">
    <button class="trip-tab active" data-type="one-way">One Way</button>
    <button class="trip-tab" data-type="round-trip">Round-Trip</button>
  </div>

  <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
    @csrf
    <input type="hidden" name="trip_type" id="tripTypeInput" value="one-way">

    {{-- ── Row 0: Name / Email ──────────── --}}
    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <label class="form-label-custom">Full Name</label>
        <input type="text" name="name"
               class="form-control-custom"
               placeholder="Enter your full name"
               value="{{ old('name') }}"
               required>
        @error('name')
          <div style="color:#e53e3e;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label-custom">Email Address</label>
        <input type="email" name="email"
               class="form-control-custom"
               placeholder="you@example.com"
               value="{{ old('email') }}"
               required>
        @error('email')
          <div style="color:#e53e3e;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
        @enderror
      </div>
    </div>

    {{-- ── Row 1: From / To ─────────────── --}}
    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <label class="form-label-custom">From</label>
        <div class="select-wrap">
          <select name="from" class="form-select-custom form-control-custom" required>
            <option value="">Select Airport</option>
            @foreach($airports as $airport)
              <option value="{{ $airport->iata_code }}" {{ old('from') == $airport->iata_code ? 'selected' : '' }}>
                {{ $airport->label }}
              </option>
            @endforeach
          </select>
        </div>
        @error('from')
          <div style="color:#e53e3e;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label-custom">To</label>
        <div class="select-wrap">
          <select name="to" class="form-select-custom form-control-custom" required>
            <option value="">Select Airport</option>
            @foreach($airports as $airport)
              <option value="{{ $airport->iata_code }}" {{ old('to') == $airport->iata_code ? 'selected' : '' }}>
                {{ $airport->label }}
              </option>
            @endforeach
          </select>
        </div>
        @error('to')
          <div style="color:#e53e3e;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
        @enderror
      </div>
    </div>

    {{-- ── Row 2: Departure / Class ─────── --}}
    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <label class="form-label-custom">Departure</label>
        <div class="date-wrap">
          <input type="date" name="departure_date"
                 class="form-control-custom"
                 value="{{ old('departure_date') }}"
                 min="{{ date('Y-m-d') }}" required>
          <i class="bi bi-calendar3 cal-icon"></i>
        </div>
        @error('departure_date')
          <div style="color:#e53e3e;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label-custom">Class</label>
        <div class="select-wrap">
          <select name="class" class="form-select-custom form-control-custom">
            <option value="economy"         {{ old('class') == 'economy'         ? 'selected' : '' }}>Economy</option>
            <option value="premium_economy" {{ old('class') == 'premium_economy' ? 'selected' : '' }}>Premium Economy</option>
            <option value="business"        {{ old('class') == 'business'        ? 'selected' : '' }}>Business</option>
            <option value="first"           {{ old('class') == 'first'           ? 'selected' : '' }}>First Class</option>
          </select>
        </div>
      </div>
    </div>

    {{-- ── Return Date (Round-Trip only) ── --}}
    <div class="row g-3 mb-3 return-date-row {{ old('trip_type') == 'round-trip' ? 'show' : '' }}" id="returnDateRow">
      <div class="col-md-6">
        <label class="form-label-custom">Return Date</label>
        <div class="date-wrap">
          <input type="date" name="return_date"
                 class="form-control-custom"
                 value="{{ old('return_date') }}"
                 min="{{ date('Y-m-d') }}">
          <i class="bi bi-calendar3 cal-icon"></i>
        </div>
        @error('return_date')
          <div style="color:#e53e3e;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <hr class="form-divider">

    {{-- ── Row 3: Country Code / Contact ── --}}
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label class="form-label-custom">Country Code</label>
        <div class="select-wrap">
          <select name="country_code" class="form-select-custom form-control-custom" required>
            <option value="" disabled {{ old('country_code') ? '' : 'selected' }}>Select Country</option>
            <option value="+91"  {{ old('country_code') == '+91'  ? 'selected' : '' }}>🇮🇳 India (+91)</option>
            <option value="+1"   {{ old('country_code') == '+1'   ? 'selected' : '' }}>🇺🇸 USA / Canada (+1)</option>
            <option value="+44"  {{ old('country_code') == '+44'  ? 'selected' : '' }}>🇬🇧 UK (+44)</option>
            <option value="+971" {{ old('country_code') == '+971' ? 'selected' : '' }}>🇦🇪 UAE (+971)</option>
            <option value="+65"  {{ old('country_code') == '+65'  ? 'selected' : '' }}>🇸🇬 Singapore (+65)</option>
            <option value="+61"  {{ old('country_code') == '+61'  ? 'selected' : '' }}>🇦🇺 Australia (+61)</option>
            <option value="+49"  {{ old('country_code') == '+49'  ? 'selected' : '' }}>🇩🇪 Germany (+49)</option>
            <option value="+33"  {{ old('country_code') == '+33'  ? 'selected' : '' }}>🇫🇷 France (+33)</option>
          </select>
        </div>
        @error('country_code')
          <div style="color:#e53e3e;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label-custom">Contact Number</label>
        <input type="tel" name="contact_number"
               class="form-control-custom"
               placeholder="Enter Contact Number"
               value="{{ old('contact_number') }}"
               pattern="[0-9]{7,15}" required>
        @error('contact_number')
          <div style="color:#e53e3e;font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
        @enderror
      </div>
    </div>

    {{-- ── Next Button ──────────────────── --}}
    <button type="submit" class="btn-next">Next</button>

  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const tabs      = document.querySelectorAll('.trip-tab');
  const tripInput = document.getElementById('tripTypeInput');
  const returnRow = document.getElementById('returnDateRow');

  // Restore active tab from old() on validation failure
  const oldType = '{{ old('trip_type', 'one-way') }}';
  tabs.forEach(t => {
    t.classList.toggle('active', t.dataset.type === oldType);
  });
  tripInput.value = oldType;

  tabs.forEach(tab => {
    tab.addEventListener('click', function () {
      tabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');

      const type = this.dataset.type;
      tripInput.value = type;

      returnRow.classList.toggle('show', type === 'round-trip');

      if (type !== 'round-trip') {
        const rd = document.querySelector('input[name="return_date"]');
        if (rd) rd.value = '';
      }
    });
  });

});
</script>

@endsection