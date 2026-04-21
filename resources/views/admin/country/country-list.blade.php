@extends('layouts.app')

@section('title', 'Explore Destinations')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --navy:        #0d2750;
        --navy-deep:   #091d3d;
        --navy-mid:    #14305e;
        --pink:        #cc00cc;
        --pink-light:  #e500e5;
        --pink-dim:    rgba(204,0,204,0.09);
        --pink-border: rgba(204,0,204,0.3);
        --page-bg:     #f0f2f7;
        --white:       #ffffff;
        --border:      #d8dde8;
        --text:        #0d2750;
        --text-mid:    #4a5568;
        --text-muted:  #8a9ab8;
        --card-radius: 18px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body { background: var(--page-bg); }

    .page-wrap {
        background: var(--page-bg);
        min-height: 100vh;
        padding: 2rem 2.5rem 4rem;
        font-family: 'DM Sans', sans-serif;
    }

    /* ── Header ── */
    .page-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        font-family: 'Nunito', sans-serif;
        font-size: 2rem;
        font-weight: 900;
        color: var(--navy);
        letter-spacing: -.4px;
    }
    .page-title span { color: var(--pink); }

    .page-subtitle {
        font-size: .88rem;
        color: var(--text-mid);
        margin-top: .3rem;
    }

    /* ── Filter bar ── */
    .filter-bar {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 50px;
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
        gap: 0;
        margin-bottom: 2rem;
        padding: .35rem .5rem;
        box-shadow: 0 2px 14px rgba(13,39,80,.07);
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        padding: .45rem 1.2rem;
        cursor: pointer;
        border-right: 1px solid var(--border);
        flex: 1;
        min-width: 140px;
        position: relative;
    }
    .filter-item:last-child { border-right: none; }

    .filter-label {
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: .15rem;
    }

    .filter-item select {
        border: none;
        background: transparent;
        font-family: 'DM Sans', sans-serif;
        font-size: .88rem;
        font-weight: 600;
        color: var(--navy);
        cursor: pointer;
        outline: none;
        -webkit-appearance: none;
        padding-right: 1.2rem;
        width: 100%;
    }

    .filter-item::after {
        content: '';
        position: absolute;
        right: 1.1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted);
        pointer-events: none;
    }

    /* Results meta */
    .results-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .results-count {
        font-size: .82rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    .results-count strong { color: var(--navy); }

    .active-filters {
        display: flex;
        gap: .4rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-chip {
        display: flex;
        align-items: center;
        gap: .3rem;
        padding: .22rem .7rem;
        background: var(--pink-dim);
        border: 1px solid var(--pink-border);
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 700;
        color: var(--pink);
        text-decoration: none;
        transition: opacity .15s;
    }
    .filter-chip:hover { opacity: .75; }
    .filter-chip svg { flex-shrink: 0; }

    /* ── Grid ── */
    .country-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.4rem;
    }

    /* ── Card ── */
    .country-card {
        border-radius: var(--card-radius);
        overflow: hidden;
        position: relative;
        aspect-ratio: 3/4;
        cursor: pointer;
        box-shadow: 0 6px 28px rgba(13,39,80,.15);
        transition: transform .28s cubic-bezier(.34,1.56,.64,1), box-shadow .28s;
        background: #1a2a4a;
        display: block;
        text-decoration: none;
    }

    .country-card:hover {
        transform: translateY(-6px) scale(1.015);
        box-shadow: 0 18px 44px rgba(13,39,80,.24);
    }

    .country-card:hover .card-img   { opacity: 1; transform: scale(1.04); }
    .country-card:hover .card-info  { transform: translateY(0); opacity: 1; }

    .card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: .85;
        transition: opacity .35s, transform .35s;
        display: block;
        position: absolute;
        inset: 0;
    }

    /* No-image placeholder */
    .card-no-img {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #0d2750 0%, #1a3a6e 60%, #14305e 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }

    .card-gradient-bottom {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to top,
            rgba(0,0,0,.92) 0%,
            rgba(0,0,0,.5)  38%,
            rgba(0,0,0,.08) 65%,
            transparent 100%
        );
        z-index: 1;
    }

    /* Flag top-right */
    .card-flag {
        position: absolute;
        top: .9rem;
        right: .9rem;
        font-size: 1.7rem;
        filter: drop-shadow(0 2px 6px rgba(0,0,0,.55));
        line-height: 1;
        z-index: 3;
    }

    /* Bottom content */
    .card-bottom {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1.1rem 1rem .95rem;
        z-index: 2;
    }

    .card-name {
        font-family: 'Nunito', sans-serif;
        font-size: 1.18rem;
        font-weight: 900;
        color: #fff;
        letter-spacing: .04em;
        text-transform: uppercase;
        margin-bottom: .6rem;
        text-shadow: 0 2px 8px rgba(0,0,0,.45);
    }

    .card-meta {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        border-top: 1px solid rgba(255,255,255,.18);
        padding-top: .6rem;
        flex-wrap: wrap;
    }

    .meta-item { display: flex; flex-direction: column; gap: 2px; }

    .meta-key {
        font-size: .55rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: rgba(255,255,255,.5);
    }

    .meta-val {
        font-size: .78rem;
        font-weight: 700;
        color: #fff;
        letter-spacing: .04em;
    }

    /* Hover reveal: documents */
    .card-info {
        position: absolute;
        top: 0; left: 0; right: 0;
        padding: 1rem;
        transform: translateY(-10px);
        opacity: 0;
        transition: transform .28s, opacity .28s;
        z-index: 4;
    }

    .card-info-inner {
        background: rgba(9,29,61,.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 10px;
        padding: .65rem .85rem;
        border: 1px solid rgba(255,255,255,.1);
    }

    .card-info-title {
        font-size: .58rem;
        font-weight: 700;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: rgba(255,255,255,.45);
        margin-bottom: .4rem;
    }

    .card-docs { display: flex; flex-wrap: wrap; gap: .3rem; }

    .card-doc-badge {
        font-size: .65rem;
        font-weight: 600;
        color: rgba(255,255,255,.9);
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 4px;
        padding: .12rem .45rem;
    }

    /* Visa status badge */
    .visa-badge {
        position: absolute;
        top: .9rem;
        left: .9rem;
        font-size: .58rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        padding: .24rem .65rem;
        border-radius: 20px;
        z-index: 3;
        backdrop-filter: blur(4px);
    }
    .badge-free     { background: rgba(16,185,129,.28); color: #6ee7b7; border: 1px solid rgba(16,185,129,.4); }
    .badge-arrival  { background: rgba(245,158,11,.25); color: #fcd34d; border: 1px solid rgba(245,158,11,.4); }
    .badge-evisa    { background: rgba(59,130,246,.25); color: #93c5fd; border: 1px solid rgba(59,130,246,.4); }
    .badge-required { background: rgba(239,68,68,.25);  color: #fca5a5; border: 1px solid rgba(239,68,68,.4); }

    /* Empty state */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 5rem 2rem;
    }
    .empty-icon {
        width: 72px; height: 72px;
        background: var(--pink-dim);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }
    .empty-state h3 {
        font-family: 'Nunito', sans-serif;
        font-size: 1.25rem;
        font-weight: 900;
        color: var(--navy);
        margin-bottom: .4rem;
    }
    .empty-state p { font-size: .85rem; color: var(--text-muted); }

    /* Pagination */
    .pagination-wrap {
        display: flex;
        justify-content: center;
        margin-top: 2.5rem;
    }

    /* Override Laravel pagination links */
    .pagination-wrap nav > div { display: flex; align-items: center; gap: .35rem; }

    .pagination-wrap .page-link,
    .pagination-wrap span > span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px; height: 36px;
        border-radius: 8px;
        font-size: .82rem;
        font-weight: 700;
        text-decoration: none;
        border: 1.5px solid var(--border);
        background: var(--white);
        color: var(--text-mid);
        padding: 0 .5rem;
        transition: border-color .18s, background .18s, color .18s;
    }

    .pagination-wrap .page-link:hover  { border-color: var(--pink); color: var(--pink); }
    .pagination-wrap span[aria-current] > span {
        background: var(--pink);
        border-color: var(--pink);
        color: #fff;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .filter-bar { flex-wrap: wrap; border-radius: 16px; }
        .filter-item { border-right: none; border-bottom: 1px solid var(--border); min-width: 100%; }
        .filter-item:last-child { border-bottom: none; }
        .filter-item::after { top: auto; bottom: .85rem; }
    }

    @media (max-width: 640px) {
        .page-wrap { padding: 1.25rem 1rem 3rem; }
        .country-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: .9rem; }
        .page-title { font-size: 1.5rem; }
    }
</style>

<div class="page-wrap">

    {{-- Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Explore <span>Destinations</span></h1>
            {{-- <p class="page-subtitle">
                {{ $countries->total() }} {{ Str::plural('destination', $countries->total()) }} available — find your next visa in seconds
            </p> --}}
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ url('/countries') }}" id="filterForm">
        <div class="filter-bar">

            <div class="filter-item">
                <span class="filter-label">Visa Delivery</span>
                <select name="delivery" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Any Time</option>
                    <option value="fast"     {{ request('delivery') == 'fast'     ? 'selected' : '' }}>Fast (1–3 days)</option>
                    <option value="standard" {{ request('delivery') == 'standard' ? 'selected' : '' }}>Standard (4–10 days)</option>
                </select>
            </div>

            <div class="filter-item">
                <span class="filter-label">Type</span>
                <select name="type" onchange="document.getElementById('filterForm').submit()">
                    <option value="">All Visa Types</option>
                    @foreach($visa_types as $visatype)
                        <option value="{{ $visatype->id }}" {{ request('type') == $visatype ? 'selected' : '' }}>
                            {{ $visatype->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <span class="filter-label">Documents</span>
                <select name="document" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Any Documents</option>
                    @foreach($documents as $doc)
                        <option value="{{ $doc->id }}" {{ request('document') == $doc->id ? 'selected' : '' }}>
                            {{ $doc->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </form>

    {{-- Results meta / active filters --}}
    <div class="results-meta">
        <span class="results-count">
            Showing <strong>{{ $countries->count() }}</strong>
            of <strong>{{ $countries->total() }}</strong>
            {{ Str::plural('destination', $countries->total()) }}
        </span>

        @if(request()->anyFilled(['type','delivery','document']))
        <div class="active-filters">

            @if(request('type'))
                <a href="{{ request()->fullUrlWithoutQuery(['type']) }}" class="filter-chip">
                    {{ request('type') }}
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </a>
            @endif

            @if(request('delivery'))
                <a href="{{ request()->fullUrlWithoutQuery(['delivery']) }}" class="filter-chip">
                    {{ ucfirst(request('delivery')) }}
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </a>
            @endif

            @if(request('document'))
                <a href="{{ request()->fullUrlWithoutQuery(['document']) }}" class="filter-chip">
                    {{ $documents->firstWhere('id', request('document'))?->name }}
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </a>
            @endif

            <a href="{{ url('/countries') }}" class="filter-chip"
               style="background:transparent;border-color:var(--border);color:var(--text-muted)">
                Clear all
            </a>
        </div>
        @endif
    </div>

    {{-- Grid --}}
    <div class="country-grid">

        @forelse($countries as $country)

        @php
            $badgeClass = match($country->visa_status) {
                'No Visa Required' => 'badge-free',
                'Visa on Arrival'  => 'badge-arrival',
                'e-Visa'           => 'badge-evisa',
                default            => 'badge-required',
            };
        @endphp

        <a class="country-card" href="{{ route('country-type',$country->id)}}">

            {{-- Background image — fixed path --}}
            @if($country->card_image)
                <img class="card-img"
                     src="{{ asset('storage/' . $country->card_image) }}"
                     alt="{{ $country->country_name }}"
                     loading="lazy">
            @else
                <div class="card-no-img">
                    {{ $country->flag_emoji ?? '🌍' }}
                </div>
            @endif

            <div class="card-gradient-bottom"></div>

            {{-- Visa badge top-left --}}
            <span class="visa-badge {{ $badgeClass }}">{{ $country->visa_status }}</span>

            {{-- Flag top-right --}}
            @if($country->flag_emoji)
                <img class="card-flag"  src="{{ asset('storage/' . $country->flag_emoji) }}">{{ $country->flag_emoji }}</img>
            @endif

            {{-- Hover: documents needed --}}
            @if($country->documents->isNotEmpty())
            <div class="card-info">
                <div class="card-info-inner">
                    <div class="card-info-title">Documents needed</div>
                    <div class="card-docs">
                        @foreach($country->documents as $doc)
                            <span class="card-doc-badge">{{ $doc->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Bottom info --}}
            <div class="card-bottom">
                <div class="card-name">{{ $country->country_name }}</div>
                <div class="card-meta">
                    <div class="meta-item">
                        <span class="meta-key">Type</span>
                        <span class="meta-val">{{ $country->visa_type_name }}</span>
                    </div>
                    @if($country->validity_days && !in_array($country->visa_type_name,['Visa Free','Visa on Arrival']))
                    <div class="meta-item">
                        <span class="meta-key">Valid</span>
                        <span class="meta-val">{{ $country->validity_days }} Days</span>
                    </div>
                    @endif
                    @if($country->visa_fee !== null && !in_array($country->visa_type_name, ['Visa Free', 'Visa on Arrival']))
                    <div class="meta-item">
                        <span class="meta-key">Fees</span>
                        <span class="meta-val">
                            {{ $country->visa_fee == 0 ? 'Free' : '₹' . number_format($country->visa_fee) }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

        </a>

        @empty

        <div class="empty-state">
            <div class="empty-icon">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="var(--pink)" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                </svg>
            </div>
            <h3>No destinations found</h3>
            <p>Try adjusting your filters to explore more options.</p>
        </div>

        @endforelse

    </div>

    {{-- Pagination --}}
    @if($countries->hasPages())
    <div class="pagination-wrap">
        {{ $countries->links() }}
    </div>
    @endif

</div>

@endsection