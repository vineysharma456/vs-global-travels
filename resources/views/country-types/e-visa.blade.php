@extends('layouts.app')

@section('title', 'E-Visa')

@section('content')

<section style="background: linear-gradient(135deg, #0f2a4a 0%, #1a4a7a 50%, #0d3d5c 100%); border-radius: 16px; padding: 3rem 2rem; text-align: center; position: relative; overflow: hidden;">

    <!-- Dot pattern overlay -->
    <div style="position:absolute;top:0;left:0;right:0;bottom:0;pointer-events:none;opacity:0.07;">
        <svg width="100%" height="100%">
            <defs><pattern id="dots" width="40" height="40" patternUnits="userSpaceOnUse">
                <circle cx="20" cy="20" r="1.5" fill="white"/>
            </pattern></defs>
            <rect width="100%" height="100%" fill="url(#dots)"/>
        </svg>
    </div>

    <!-- Decorative circles -->
    <div style="position:absolute;top:-60px;right:-60px;width:220px;height:220px;border-radius:50%;background:rgba(56,189,248,0.08);"></div>
    <div style="position:absolute;bottom:-80px;left:-40px;width:280px;height:280px;border-radius:50%;background:rgba(34,211,138,0.06);"></div>

    <!-- Content -->
    <div style="position:relative;z-index:1;">

        <!-- Rating badge -->
        <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.08);border:0.5px solid rgba(255,255,255,0.15);border-radius:999px;padding:6px 16px;margin-bottom:1.5rem;">
            <span style="color:#FACC15;font-size:13px;">★★★★★</span>
            <span style="color:white;font-size:13px;font-weight:500;">4.5</span>
            <span style="color:rgba(255,255,255,0.5);font-size:12px;">·</span>
            <span style="color:rgba(255,255,255,0.6);font-size:12px;">9,278 reviews</span>
        </div>

        <!-- Heading -->
        <h1 style="color:white;font-size:2.4rem;font-weight:600;margin:0 0 0.5rem;line-height:1.2;">
            {{$country->country_name}} Visa for Indians
        </h1>
        <p style="color:#34D399;font-size:1.6rem;font-weight:500;margin:0 0 2rem;">
            in exactly 2 hours
        </p>

        <!-- Stats row -->
        <div style="display:flex;justify-content:center;gap:0;margin:0 auto 2.5rem;max-width:480px;">
            <div style="flex:1;padding:1rem;border-right:0.5px solid rgba(255,255,255,0.12);">
                <p style="color:rgba(255,255,255,0.5);font-size:11px;letter-spacing:1.5px;text-transform:uppercase;margin:0 0 6px;">Valid</p>
                <p style="color:white;font-size:1.1rem;font-weight:500;margin:0;">{{$country->validity_days}} Days</p>
            </div>
            <div style="flex:1;padding:1rem;border-right:0.5px solid rgba(255,255,255,0.12);">
                <p style="color:rgba(255,255,255,0.5);font-size:11px;letter-spacing:1.5px;text-transform:uppercase;margin:0 0 6px;">Purpose</p>
                <p style="color:white;font-size:1.1rem;font-weight:500;margin:0;">Tourism</p>
            </div>
            <div style="flex:1;padding:1rem;">
                <p style="color:rgba(255,255,255,0.5);font-size:11px;letter-spacing:1.5px;text-transform:uppercase;margin:0 0 6px;">Max Stay</p>
                <p style="color:white;font-size:1.1rem;font-weight:500;margin:0;">{{$country->stay_duration}} Days</p>
            </div>
        </div>

        <!-- CTA Button -->
        <a href="{{ route('visa.apply',[$country->id]) }}"
           style="display:inline-block;background:white;color:#0f2a4a;border-radius:999px;padding:14px 48px;font-size:1rem;font-weight:500;text-decoration:none;letter-spacing:0.3px;">
            Start Application
        </a>

    </div>
</section>

@endsection