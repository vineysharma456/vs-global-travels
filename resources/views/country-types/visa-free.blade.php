{{-- resources/views/visa-free.blade.php --}}
@extends('layouts.app')

@section('title', $country->country_name . ' Visa Free')

@section('content')

<div class="vf-page">
    {{-- Section 1: Hero --}}
    <x-visa-free.hero-section :country="$country" />

    <div class="vf-page__body">
        {{-- Section 2: Entry Details --}}
        <x-visa-free.entry-details :country="$country" />

        <hr class="vf-divider" />

        {{-- Section 3: Transit Timeline --}}
        <x-visa-free.transit-timeline :country="$country" />
    </div>
</div>

@endsection

@push('styles')
<style>
/* ─── CSS Variables ─────────────────────────── */
:root {
    --vf-blue:       #2563eb;
    --vf-blue-light: #eff6ff;
    --vf-blue-dot:   #3b82f6;
    --vf-text:       #111827;
    --vf-muted:      #6b7280;
    --vf-border:     #e5e7eb;
    --vf-card-bg:    #ffffff;
    --vf-radius:     12px;
    --vf-shadow:     0 1px 4px rgba(0,0,0,.08);
}

/* ─── Page wrapper ──────────────────────────── */
.vf-page { font-family: 'Segoe UI', system-ui, sans-serif; color: var(--vf-text); }
.vf-page__body { max-width: 860px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }
.vf-divider { border: none; border-top: 1px solid var(--vf-border); margin: 2.5rem 0; }

/* ─── Shared section heading ────────────────── */
.vf-section__title  { font-size: 1.5rem; font-weight: 700; margin: 0 0 .4rem; }
.vf-section__divider{ width: 48px; height: 3px; background: var(--vf-blue); border-radius: 2px; margin-bottom: 1.75rem; }

/* ═══════════════════════════════════════════════
   HERO
═══════════════════════════════════════════════ */
.vf-hero { width: 100%; }

.vf-hero__grid {
    display: grid;
    grid-template-columns: 58% 1fr;
    grid-template-rows: 480px;
    gap: 4px;
}

/* Main image */
.vf-hero__main {
    position: relative;
    overflow: hidden;
}

/* Aside 2×2 */
.vf-hero__aside {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 1fr 1fr;
    gap: 4px;
}

.vf-hero__thumb { position: relative; overflow: hidden; }

.vf-hero__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .4s ease;
}
.vf-hero__thumb:hover .vf-hero__img,
.vf-hero__main:hover .vf-hero__img { transform: scale(1.04); }

.vf-hero__img--placeholder { background: #d1d5db; }

/* Title overlay */
.vf-hero__overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: 2rem 1.75rem 1.5rem;
    background: linear-gradient(to top, rgba(0,0,0,.65) 0%, transparent 100%);
}
.vf-hero__title {
    color: #fff;
    font-size: clamp(1.6rem, 3vw, 2.25rem);
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
    display: flex;
    align-items: center;
    gap: .75rem;
}
.vf-hero__badge {
    display: inline-flex;
    align-items: center;
    background: #22c55e;
    color: #fff;
    font-size: .75rem;
    font-weight: 600;
    padding: .2rem .6rem;
    border-radius: 99px;
    letter-spacing: .03em;
    vertical-align: middle;
}

/* Trust bar */
.vf-hero__trust {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    padding: .9rem 1.5rem;
    border-top: 1px solid var(--vf-border);
    background: #fff;
}
.vf-trust__item {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .82rem;
    color: var(--vf-muted);
    font-weight: 500;
}
.vf-trust__divider { width: 1px; height: 18px; background: var(--vf-border); }

/* ═══════════════════════════════════════════════
   ENTRY DETAILS
═══════════════════════════════════════════════ */
.vf-entry__notice {
    display: flex;
    align-items: flex-start;
    gap: .85rem;
    background: var(--vf-blue-light);
    border: 1px solid #bfdbfe;
    border-radius: var(--vf-radius);
    padding: 1rem 1.25rem;
    margin-bottom: 1.75rem;
}
.vf-entry__notice-icon {
    flex-shrink: 0;
    width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    background: #dbeafe;
    border-radius: 50%;
    color: var(--vf-blue);
}
.vf-entry__notice-title { font-weight: 600; font-size: .95rem; margin: 0 0 .2rem; }
.vf-entry__notice-sub   { font-size: .84rem; color: var(--vf-muted); margin: 0; }

.vf-entry__cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
}
.vf-entry__card {
    display: flex;
    align-items: center;
    gap: .85rem;
    background: var(--vf-card-bg);
    border: 1px solid var(--vf-border);
    border-radius: var(--vf-radius);
    padding: 1rem 1.25rem;
    box-shadow: var(--vf-shadow);
}
.vf-entry__card-icon {
    flex-shrink: 0;
    width: 38px; height: 38px;
    display: flex; align-items: center; justify-content: center;
    background: var(--vf-blue-light);
    border-radius: 10px;
    color: var(--vf-blue);
}
.vf-entry__card-label { font-size: .78rem; color: var(--vf-muted); margin: 0 0 .15rem; }
.vf-entry__card-value { font-weight: 700; font-size: 1rem; margin: 0; text-decoration: underline; }

/* ═══════════════════════════════════════════════
   TRANSIT TIMELINE
═══════════════════════════════════════════════ */
.vf-timeline__track { display: flex; flex-direction: column; gap: 0; }

.vf-timeline__item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

/* Dot + line column */
.vf-timeline__dot {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
    padding-top: .2rem;
}
.vf-timeline__dot-inner {
    width: 14px; height: 14px;
    border-radius: 50%;
    border: 2px solid var(--vf-blue-dot);
    background: #fff;
    z-index: 1;
}
.vf-timeline__dot--last .vf-timeline__dot-inner {
    background: var(--vf-blue-dot);
}
.vf-timeline__line {
    width: 2px;
    flex: 1;
    min-height: 40px;
    background: #bfdbfe;
    margin: 4px 0;
}

/* Card */
.vf-timeline__card {
    display: flex;
    align-items: center;
    gap: .85rem;
    background: var(--vf-card-bg);
    border: 1px solid var(--vf-border);
    border-radius: var(--vf-radius);
    padding: 1rem 1.25rem;
    margin-bottom: 1rem;
    box-shadow: var(--vf-shadow);
    flex: 1;
    transition: box-shadow .2s ease, border-color .2s ease;
}
.vf-timeline__card:hover {
    border-color: #93c5fd;
    box-shadow: 0 4px 12px rgba(37,99,235,.1);
}
.vf-timeline__card-icon {
    flex-shrink: 0;
    width: 38px; height: 38px;
    display: flex; align-items: center; justify-content: center;
    background: var(--vf-blue-light);
    border-radius: 10px;
    color: var(--vf-blue);
}
.vf-timeline__card-title { font-weight: 600; font-size: .95rem; margin: 0 0 .2rem; }
.vf-timeline__card-desc  { font-size: .84rem; color: var(--vf-muted); margin: 0; }

/* ─── Responsive ────────────────────────────── */
@media (max-width: 680px) {
    .vf-hero__grid {
        grid-template-columns: 1fr;
        grid-template-rows: 260px auto;
    }
    .vf-hero__aside {
        grid-template-rows: 120px;
        grid-template-columns: repeat(4, 1fr);
    }
    .vf-hero__trust { gap: .8rem; flex-wrap: wrap; }
    .vf-entry__cards { grid-template-columns: 1fr 1fr; }
}
</style>
@endpush