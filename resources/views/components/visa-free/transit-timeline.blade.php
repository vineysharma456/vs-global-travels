{{-- resources/views/components/visa-free/transit-timeline.blade.php --}}
{{-- Usage: <x-visa-free.transit-timeline :country="$country" /> --}}

@props(['country'])

@php
    // Default timeline steps — override via $country->transit_steps JSON column if you add one later
    $steps = [
        [
            'title' => 'Arrive in ' . $country->country_name,
            'description' => 'Gather your belongings and head to immigration.',
            'icon' => '<path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5z"/>',
        ],
        [
            'title' => 'Keep your passport in hand',
            'description' => 'Your passport must be valid for at least six months.',
            'icon' => '<rect x="2" y="3" width="20" height="14" rx="2"/><rect x="8" y="17" width="8" height="4"/><line x1="6" y1="21" x2="18" y2="21"/>',
        ],
        [
            'title' => 'Head to Immigration',
            'description' => 'Show your passport and travel documents to immigration.',
            'icon' => '<circle cx="12" cy="8" r="5"/><path d="M3 21v-2a7 7 0 0 1 14 0v2"/>',
        ],
        [
            'title' => 'Collect your baggage',
            'description' => 'Proceed to the baggage claim area and collect your luggage.',
            'icon' => '<rect x="6" y="2" width="12" height="20" rx="2"/><line x1="6" y1="10" x2="18" y2="10"/><circle cx="12" cy="6" r="1"/>',
        ],
        [
            'title' => 'You\'re all set!',
            'description' => 'Welcome to ' . $country->country_name . '. Enjoy your stay!',
            'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        ],
    ];
@endphp

<section class="vf-timeline">
    <h2 class="vf-section__title">Your Transit Timeline</h2>
    <div class="vf-section__divider"></div>

    <div class="vf-timeline__track">
        @foreach($steps as $index => $step)
        <div class="vf-timeline__item">
            <div class="vf-timeline__dot {{ $loop->last ? 'vf-timeline__dot--last' : '' }}">
                <span class="vf-timeline__dot-inner"></span>
                @if(!$loop->last)
                    <span class="vf-timeline__line"></span>
                @endif
            </div>
            <div class="vf-timeline__card">
                <span class="vf-timeline__card-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        {!! $step['icon'] !!}
                    </svg>
                </span>
                <div>
                    <p class="vf-timeline__card-title">{{ $step['title'] }}</p>
                    <p class="vf-timeline__card-desc">{{ $step['description'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>