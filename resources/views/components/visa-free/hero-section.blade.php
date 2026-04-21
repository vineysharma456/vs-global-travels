{{-- resources/views/components/visa-free/hero-section.blade.php --}}
{{-- Usage: <x-visa-free.hero-section :country="$country" /> --}}

@props(['country'])

@php
    $images = $country->images->sortBy('sequence')->values();
    $heroImage = $images->get(0)?->image; // only from country_images table
    $gridImages = $images->skip(1)->take(4);
@endphp
{{-- @dd($country->images); --}}
<section class="vf-hero">
    <div class="vf-hero__grid">

        {{-- Main large image --}}
        <div class="vf-hero__main">
            @if($heroImage)
                <img src="{{ asset('storage/' . $heroImage) }}"
                     alt="{{ $country->country_name }}"
                     class="vf-hero__img" />
            @else
                <div class="vf-hero__img vf-hero__img--placeholder"></div>
            @endif
            <div class="vf-hero__overlay">
                <h1 class="vf-hero__title">
                    {{ $country->country_name }} Visa
                    @if($country->is_visa_free)
                        <span class="vf-hero__badge">Visa Free</span>
                    @endif
                </h1>
            </div>
        </div>

        {{-- Right 2x2 grid --}}
        <div class="vf-hero__aside">
            @forelse($gridImages as $img)
                <div class="vf-hero__thumb">
                    <img src="{{ asset('storage/' . $img->image) }}"
                         alt="{{ $country->country_name }} photo {{ $loop->iteration + 1 }}"
                         class="vf-hero__img" />
                </div>
            @empty
                @for($i = 0; $i < 4; $i++)
                    <div class="vf-hero__thumb">
                        <div class="vf-hero__img vf-hero__img--placeholder"></div>
                    </div>
                @endfor
            @endforelse
        </div>

    </div>

    {{-- Trust bar --}}
    <div class="vf-hero__trust">
        <span class="vf-trust__item vf-trust__item--star">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="#00b67a"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
            Trustpilot
        </span>
        <span class="vf-trust__divider"></span>
        <span class="vf-trust__item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
            App Store
        </span>
        <span class="vf-trust__divider"></span>
        <span class="vf-trust__item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="#3ddc84"><path d="M3.18 23.76c.35.2.76.19 1.15-.03l12.18-7.03-2.61-2.61-10.72 9.67zM.49 1.27C.18 1.64 0 2.18 0 2.88v18.24c0 .7.18 1.24.49 1.61l.08.08 10.21-10.21v-.24L.57 1.19l-.08.08zM20.49 10.27l-2.61-1.51-2.92 2.92 2.92 2.93 2.62-1.52c.75-.43.75-1.38-.01-1.82zM4.33.27L16.51 7.3l-2.61 2.61L3.18.24c.39-.22.8-.23 1.15.03z"/></svg>
            Google Play
        </span>
    </div>
</section>