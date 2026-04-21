{{-- resources/views/components/visa-free/entry-details.blade.php --}}
{{-- Usage: <x-visa-free.entry-details :country="$country" /> --}}

@props(['country'])

@php
    $visaTypeLabels = [
        0 => 'None',
        1 => 'Tourist',
        2 => 'e-Visa',
        3 => 'Visa on Arrival',
        4 => 'Sticker Visa',
    ];
    $visaLabel = $visaTypeLabels[$country->visa_type] ?? 'None';
    $validityText = $country->validity_days ? $country->validity_days . ' Days' : '—';
    $stayText     = $country->stay_duration  ? $country->stay_duration  . ' Days' : '—';
    $feeText      = $country->visa_fee == 0 ? '₹0' : '₹' . number_format($country->visa_fee, 0);
@endphp

<section class="vf-entry">
    <h2 class="vf-section__title">{{ $country->country_name }} Entry Details</h2>
    <div class="vf-section__divider"></div>

    @if($country->is_visa_free)
    <div class="vf-entry__notice">
        <span class="vf-entry__notice-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
            </svg>
        </span>
        <div>
            <p class="vf-entry__notice-title">No Visa Required</p>
            <p class="vf-entry__notice-sub">Indian citizens do not require a visa to enter the {{ $country->country_name }}</p>
        </div>
    </div>
    @endif

    <div class="vf-entry__cards">
        <div class="vf-entry__card">
            <span class="vf-entry__card-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                </svg>
            </span>
            <div>
                <p class="vf-entry__card-label">Visa Type:</p>
               <p class="vf-entry__card-value">Visa Free</p>
            </div>      
           
        </div>

        <div class="vf-entry__card">
            <span class="vf-entry__card-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </span>
            <div>
                <p class="vf-entry__card-label">Validity:</p>
                <p class="vf-entry__card-value">{{ $validityText }}</p>
            </div>
        </div>

        

       

       
    </div>
</section>