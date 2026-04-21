@extends('layouts.app')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    :root {
        --navy:        #0b1f3a;
        --navy-mid:    #152c4e;
        --navy-light:  #1e3a62;
        --gold:        #f5a623;
        --gold-light:  #ffc14d;
        --gold-dim:    rgba(245,166,35,0.12);
        --gold-border: rgba(245,166,35,0.3);
        --green:       #00c48c;
        --green-dim:   rgba(0,196,140,0.1);
        --white:       #ffffff;
        --off-white:   #f4f6fa;
        --muted:       #8a9ab8;
        --border:      rgba(255,255,255,0.08);
        --card-bg:     rgba(255,255,255,0.04);
        --text:        #e8edf5;
        --text-mid:    #b0bdd4;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Sora', sans-serif;
        background: var(--navy);
        color: var(--text);
        min-height: 100vh;
    }

    /* ── Background mesh ── */
    .pay-bg {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
    }
    .pay-bg::before {
        content: '';
        position: absolute;
        top: -20%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(245,166,35,0.07) 0%, transparent 65%);
        border-radius: 50%;
    }
    .pay-bg::after {
        content: '';
        position: absolute;
        bottom: -10%;
        left: -5%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(21,44,78,0.8) 0%, transparent 70%);
        border-radius: 50%;
    }

    /* ── Layout ── */
    .pay-page {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 2rem;
        max-width: 1100px;
        margin: 0 auto;
        padding: 2.5rem 1.5rem 5rem;
        align-items: start;
    }

    /* ── Page header ── */
    .pay-header {
        grid-column: 1 / -1;
        margin-bottom: 0.5rem;
    }

    .pay-ref {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.5rem;
    }

    .pay-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--white);
        letter-spacing: -0.5px;
    }

    .pay-title span { color: var(--gold); }

    .pay-subtitle {
        font-size: 0.85rem;
        color: var(--muted);
        margin-top: 0.4rem;
    }

    /* ── Step bar ── */
    .step-bar {
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 0.5rem;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--muted);
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .step.done { color: var(--green); }
    .step.active { color: var(--white); }

    .step-dot {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid currentColor;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        flex-shrink: 0;
        transition: all 0.3s;
    }

    .step.done .step-dot {
        background: var(--green);
        border-color: var(--green);
        color: #fff;
    }

    .step.active .step-dot {
        background: var(--gold);
        border-color: var(--gold);
        color: var(--navy);
    }

    .step-line {
        flex: 1;
        height: 2px;
        background: var(--border);
        margin: 0 0.5rem;
        max-width: 80px;
    }

    .step-line.done { background: var(--green); }

    /* ── Left column ── */
    .pay-left { display: flex; flex-direction: column; gap: 1.25rem; }

    /* ── Card base ── */
    .pay-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.5rem;
        backdrop-filter: blur(8px);
        animation: fadeUp 0.4s ease both;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .pay-card:nth-child(2) { animation-delay: 0.08s; }
    .pay-card:nth-child(3) { animation-delay: 0.16s; }

    /* ── Section label ── */
    .section-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.67rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 1rem;
    }

    .section-tag::before {
        content: '';
        display: block;
        width: 14px;
        height: 2px;
        background: var(--gold);
        border-radius: 2px;
    }

    /* ── Trip info strip ── */
    .trip-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .trip-item {}
    .trip-item-label {
        font-size: 0.68rem;
        font-weight: 500;
        color: var(--muted);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.3rem;
    }
    .trip-item-val {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--white);
    }

    .trip-divider {
        width: 1px;
        background: var(--border);
        align-self: stretch;
    }

    /* ── Traveler list ── */
    .traveler-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.85rem 1rem;
        border-radius: 10px;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--border);
        margin-bottom: 0.6rem;
        transition: border-color 0.2s;
    }

    .traveler-row:last-child { margin-bottom: 0; }
    .traveler-row:hover { border-color: rgba(255,255,255,0.14); }

    .traveler-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--navy-light), var(--navy-mid));
        border: 1.5px solid var(--gold-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        font-weight: 800;
        color: var(--gold);
        flex-shrink: 0;
        letter-spacing: 0.05em;
    }

    .traveler-info { flex: 1; }
    .traveler-name {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--white);
        margin-bottom: 0.15rem;
    }
    .traveler-meta {
        font-size: 0.72rem;
        color: var(--muted);
    }

    .traveler-docs {
        display: flex;
        gap: 0.35rem;
        flex-wrap: wrap;
    }

    .doc-pill {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        padding: 0.22rem 0.6rem;
        border-radius: 20px;
        background: var(--green-dim);
        border: 1px solid rgba(0,196,140,0.25);
        color: var(--green);
        text-transform: uppercase;
    }

    /* ── Add-ons ── */
    .addon-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.1rem;
        border-radius: 12px;
        border: 1.5px solid var(--border);
        background: rgba(255,255,255,0.02);
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        position: relative;
        overflow: hidden;
    }

    .addon-row:last-child { margin-bottom: 0; }

    .addon-row:hover {
        border-color: rgba(245,166,35,0.3);
        background: rgba(245,166,35,0.04);
    }

    .addon-row.free-addon {
        border-color: rgba(0,196,140,0.3);
        background: rgba(0,196,140,0.04);
    }

    .addon-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        background: rgba(255,255,255,0.06);
    }

    .addon-info { flex: 1; }
    .addon-name {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 0.18rem;
    }
    .addon-desc {
        font-size: 0.72rem;
        color: var(--muted);
    }
    .addon-desc strong { color: var(--text-mid); }

    .addon-price {
        font-family: 'DM Mono', monospace;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--gold);
        white-space: nowrap;
    }

    .addon-free-badge {
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        background: var(--green);
        color: var(--navy);
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
    }

    .addon-add-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1.5px solid var(--gold-border);
        background: transparent;
        color: var(--gold);
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
        flex-shrink: 0;
    }
    .addon-add-btn:hover { background: var(--gold-dim); }

    /* ── Right: Summary card ── */
    .pay-summary {
        position: sticky;
        top: 1.5rem;
        animation: fadeUp 0.4s ease 0.2s both;
    }

    .summary-card {
        background: var(--white);
        border-radius: 20px;
        padding: 1.75rem;
        color: var(--navy);
    }

    .summary-amount-label {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #8a9ab8;
        margin-bottom: 0.35rem;
    }

    .summary-amount {
        font-size: 2.4rem;
        font-weight: 800;
        color: var(--navy);
        letter-spacing: -1px;
        margin-bottom: 1.5rem;
        font-family: 'Sora', sans-serif;
    }

    .summary-amount sup {
        font-size: 1.1rem;
        font-weight: 600;
        vertical-align: super;
        letter-spacing: 0;
    }

    /* Timeline rows */
    .summary-timeline {
        position: relative;
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .tl-line {
        position: absolute;
        left: 7px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        background: linear-gradient(to bottom, var(--gold), #e0e8f0);
    }

    .tl-item {
        position: relative;
        margin-bottom: 1.1rem;
    }
    .tl-item:last-child { margin-bottom: 0; }

    .tl-dot {
        position: absolute;
        left: -1.5rem;
        top: 3px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid var(--gold);
        background: white;
        z-index: 1;
    }

    .tl-item:last-child .tl-dot {
        border-color: #c8d5e8;
        background: #f0f4fa;
    }

    .tl-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 0.15rem;
    }

    .tl-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--navy);
    }

    .tl-item:last-child .tl-label { color: #6b7a99; }

    .tl-amount {
        font-family: 'DM Mono', monospace;
        font-size: 0.88rem;
        font-weight: 500;
        color: var(--navy);
    }

    .tl-sub {
        font-size: 0.72rem;
        color: #8a9ab8;
    }

    .summary-divider {
        height: 1px;
        background: #e8edf5;
        margin: 1.2rem 0;
    }

    .summary-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .summary-total-label {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--navy);
    }

    .summary-total-val {
        font-family: 'DM Mono', monospace;
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--navy);
    }

    /* Pay button */
    .pay-btn {
        width: 100%;
        padding: 1rem;
        border-radius: 12px;
        background: var(--gold);
        color: var(--navy);
        font-family: 'Sora', sans-serif;
        font-size: 0.95rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 6px 24px rgba(245,166,35,0.35);
    }

    .pay-btn:hover {
        background: var(--gold-light);
        box-shadow: 0 8px 32px rgba(245,166,35,0.5);
    }

    .pay-btn:active { transform: scale(0.98); }

    .pay-secure {
        text-align: center;
        margin-top: 0.75rem;
        font-size: 0.7rem;
        color: #8a9ab8;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
    }

    /* ── Guarantee strip ── */
    .guarantee-strip {
        background: var(--green-dim);
        border: 1px solid rgba(0,196,140,0.2);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-top: 1.25rem;
    }

    .guarantee-icon {
        font-size: 1.2rem;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .guarantee-text { font-size: 0.75rem; color: var(--navy); line-height: 1.55; }
    .guarantee-text strong { color: #007a58; display: block; font-size: 0.8rem; margin-bottom: 0.15rem; }

    @media (max-width: 900px) {
        .pay-page { grid-template-columns: 1fr; }
        .pay-summary { position: static; }
        .trip-strip { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 540px) {
        .trip-strip { grid-template-columns: 1fr 1fr; }
        .step-line { max-width: 40px; }
    }
</style>
@endpush

@section('content')

<div class="pay-bg"></div>

<div class="pay-page">

    {{-- ── Header ── --}}
    <div class="pay-header">
        {{-- <div class="pay-ref">Ref: {{ session('application_ref', 'VA-' . now()->format('Ymd') . '-XXXX') }}</div> --}}
        <h1 class="pay-title">Complete Your <span>Payment</span></h1>
        <p class="pay-subtitle">Review your application details and proceed to secure payment.</p>
    </div>

    {{-- ── Step bar ── --}}
    <div class="step-bar">
        <div class="step done">
            <div class="step-dot">✓</div>
            <span>Destination</span>
        </div>
        <div class="step-line done"></div>
        <div class="step done">
            <div class="step-dot">✓</div>
            <span>Documents</span>
        </div>
        <div class="step-line done"></div>
        <div class="step active">
            <div class="step-dot">3</div>
            <span>Payment</span>
        </div>
    </div>

    {{-- ════════════════ LEFT COLUMN ════════════════ --}}
    <div class="pay-left">

        {{-- Trip Details --}}
        <div class="pay-card">
            <div class="section-tag">Trip Details</div>
            @php
                $country = $application->country;
                $visaFee = $country->visa_fee ?? 0;
                $serviceFee = $country->service_fee ?? 0;
                $travelersCount = $application->travelers->count();
            @endphp
            <div class="trip-strip">
                <div class="trip-item">
                    <div class="trip-item-label">Destination</div>
                    <div class="trip-item-val">{{ $country->country_name }}</div>
                </div>
                <div class="trip-divider"></div>
                <div class="trip-item">
                    <div class="trip-item-label">Stay Duration</div>
                    <div class="trip-item-val">{{ $country->stay_duration ?? '—' }} days</div>
                </div>
                <div class="trip-divider"></div>
                <div class="trip-item">
                    <div class="trip-item-label">Validity</div>
                    <div class="trip-item-val">{{ $country->validity_days ?? '—' }} days</div>
                </div>
                <div class="trip-divider"></div>
                <div class="trip-item">
                    <div class="trip-item-label">Travelers</div>
                    <div class="trip-item-val">{{ $travelersCount }} {{ Str::plural('person', $travelersCount) }}</div>
                </div>
            </div>
        </div>

        {{-- Travelers --}}
        <div class="pay-card">
            <div class="section-tag">Travelers</div>
            @foreach($application->travelers as $i => $traveler)
                @php
                    $name = $traveler->full_name ?: 'Traveler ' . ($i + 1);
                    $initials = collect(preg_split('/\s+/', trim($name)))
                        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                        ->implode('');
                    $initials = substr($initials, 0, 2);
                    $docCount = $traveler->documents->count();
                @endphp
                <div class="traveler-row">
                    <div class="traveler-avatar">{{ $initials }}</div>
                    <div class="traveler-info">
                        <div class="traveler-name">{{ $name }}</div>
                        <div class="traveler-meta">
                            @if($traveler->passport_number)
                                Passport: {{ $traveler->passport_number }}
                                @if($traveler->nationality) · {{ $traveler->nationality }} @endif
                            @else
                                Traveler {{ $i + 1 }}
                            @endif
                        </div>
                    </div>
                    <div class="traveler-docs">
                        @if($docCount > 0)
                            <span class="doc-pill">{{ $docCount }} {{ Str::plural('doc', $docCount) }}</span>
                        @endif
                        <span class="doc-pill">Ready</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Add-ons --}}
        {{-- <div class="pay-card">
            <div class="section-tag">Essential Add-Ons</div>

            {{-- Free protection (always included) --}}
            {{-- <div class="addon-row free-addon">
                <div class="addon-icon">🛡️</div>
                <div class="addon-info">
                    <div class="addon-name">Visa Protection</div>
                    <div class="addon-desc">
                        Visa Delayed: <strong>No Service Fee</strong> &nbsp;·&nbsp;
                        Visa Rejected: <strong>100% Fee Back</strong>
                    </div>
                </div>
                <span class="addon-free-badge">Free</span>
            </div> --}}

            {{-- Optional travel insurance --}}
            {{-- <div class="addon-row" id="addon-insurance">
                <div class="addon-icon">✈️</div>
                <div class="addon-info">
                    <div class="addon-name">Travel Insurance</div>
                    <div class="addon-desc">Baggage loss &nbsp;·&nbsp; Flight delay &nbsp;·&nbsp; Medical cover</div>
                </div>
                <span class="addon-price" id="insurance-price">+ ₹576</span>
                <button class="addon-add-btn" onclick="toggleInsurance(this)" title="Add">+</button>
            </div>
        </div> --}} 

    </div>{{-- /pay-left --}}

    {{-- ════════════════ RIGHT: SUMMARY ════════════════ --}}
    <div class="pay-summary">
        <div class="summary-card">
            <div class="summary-amount-label">You Pay Now</div>
            <div class="summary-amount" id="display-amount">
                <sup>₹</sup><span id="amount-now">{{ number_format($visaFee * $travelersCount, 0) }}</span>
            </div>

            <div class="summary-timeline">
                <div class="tl-line"></div>

                <div class="tl-item">
                    <div class="tl-dot"></div>
                    <div class="tl-row">
                        <span class="tl-label">Pay Now</span>
                        <span class="tl-amount" id="tl-pay-now">₹{{ number_format($visaFee * $travelersCount, 0) }}</span>
                    </div>
                    <div class="tl-sub">
                        Visa Fee × {{ $travelersCount }} traveler{{ $travelersCount > 1 ? 's' : '' }}
                        @if($country->processing_days) · {{ $country->processing_days }} working days @endif
                    </div>
                </div>

                <div class="tl-item">
                    <div class="tl-dot"></div>
                    <div class="tl-row">
                        <span class="tl-label" style="color:#6b7a99">Service Fee</span>
                        <span class="tl-amount" style="color:#6b7a99">₹{{ number_format($serviceFee  * $travelersCount, 0) }}</span>
                        <span class="tl-amount" style="color:#6b7a99">Pay After Visa Approval</span>
                    </div>
                    <div class="tl-sub">Included — no hidden charges</div>
                </div>
            </div>

            <div class="summary-divider"></div>

            <div class="summary-total-row">
                <span class="summary-total-label">Total Amount</span>
                <span class="summary-total-val" id="tl-total">₹{{ number_format($visaFee * $travelersCount, 0) }}</span>
            </div>

            <form method="POST" action="{{-- route('payment.process') --}}">
                @csrf
                <input type="hidden" name="application_id"  value="{{ $application->id }}">
                <input type="hidden" name="application_ref" value="{{ $application->application_ref }}">
                <input type="hidden" name="amount"          id="hidden-amount" value="{{ $visaFee * $travelersCount }}">
                <input type="hidden" name="insurance"       id="hidden-insurance" value="0">

                <button type="button" id="pay-btn" class="pay-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <rect x="1" y="4" width="22" height="16" rx="2"/>
                        <line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    Pay ₹<span id="btn-amount">{{ number_format($visaFee * $travelersCount, 0) }}</span> to Submit
                </button>
            </form>

            <div class="pay-secure">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Secured by 256-bit SSL encryption
            </div>

            <div class="guarantee-strip">
                <div class="guarantee-icon">✅</div>
                <div class="guarantee-text">
                    <strong>100% Money-Back Guarantee</strong>
                    If your visa is rejected due to a processing error, we refund the full amount — no questions asked.
                </div>
            </div>
        </div>
    </div>

</div>{{-- /pay-page --}}

<script>
const BASE_AMOUNT     = {{ $visaFee * $travelersCount }};
const INSURANCE_PRICE = 576;
let   insuranceAdded  = false;

function toggleInsurance(btn) {
    insuranceAdded = !insuranceAdded;
    btn.textContent    = insuranceAdded ? '−' : '+';
    btn.style.background   = insuranceAdded ? 'rgba(245,166,35,0.15)' : '';
    btn.style.borderColor  = insuranceAdded ? 'var(--gold)' : '';

    const total = BASE_AMOUNT + (insuranceAdded ? INSURANCE_PRICE : 0);
    const fmt   = n => n.toLocaleString('en-IN');

    document.getElementById('amount-now').textContent    = fmt(total);
    document.getElementById('tl-pay-now').textContent    = '₹' + fmt(total);
    document.getElementById('tl-total').textContent      = '₹' + fmt(total);
    document.getElementById('btn-amount').textContent    = fmt(total);
    document.getElementById('hidden-amount').value       = total;
    document.getElementById('hidden-insurance').value    = insuranceAdded ? INSURANCE_PRICE : 0;
}
</script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
document.getElementById('pay-btn').onclick = async function () {

    let amount = document.getElementById('hidden-amount').value;
    let applicationId = document.querySelector('[name="application_id"]').value;
    // ✅ Create order from backend
    let response = await fetch('/create-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ amount: amount, application_id: applicationId    })
    });

    let order = await response.json();

    let options = {
        key: "{{ config('services.razorpay.key') }}",
        amount: order.amount,
        currency: "INR",
        name: "Visa Application",
        description: "Visa Fee Payment",
        order_id: order.id,

        handler: function (response) {

            // ✅ Send payment success to backend
            fetch('/payment-success', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature,

                    application_id: document.querySelector('[name="application_id"]').value,
                    amount: amount
                })
            })
            .then(res => res.json())
            .then(data => {
                alert("Payment Successful 🎉");
               window.location.href = "/thank-you?app_id=" + applicationId;
            });
        }
    };

    let rzp = new Razorpay(options);
    rzp.open();

    rzp.on('payment.failed', function () {
        alert("Payment Failed ❌");
    });
};
</script>

@endsection