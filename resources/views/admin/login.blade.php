@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=DM+Sans:wght@300;400;500&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --navy:        #0d2750;
        --navy-deep:   #091d3d;
        --navy-mid:    #14305e;
        --navy-light:  #1a3d73;
        --pink:        #cc00cc;
        --pink-light:  #e500e5;
        --pink-dim:    rgba(204,0,204,0.1);
        --pink-border: rgba(204,0,204,0.3);
        --page-bg:     #f0f2f7;
        --text-dark:   #0d2750;
        --text-mid:    #4a5568;
        --text-light:  #7a99c2;
        --border-soft: #d8dde8;
        --white:       #ffffff;
        --red:         #c0392b;
    }

    body {
        background: var(--page-bg);
        font-family: 'DM Sans', sans-serif;
        min-height: 100vh;
    }

    .admin-wrapper {
        min-height: 100vh;
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    /* ── Left: navy brand panel ── */
    .admin-panel-left {
        background: var(--navy);
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 3rem;
        overflow: hidden;
    }

    /* Dot texture */
    .admin-panel-left::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(204,0,204,0.08) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
    }

    /* Pink top bar */
    .admin-panel-left::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: var(--pink);
    }

    /* Decorative circle */
    .deco-circle {
        position: absolute;
        top: -100px;
        right: -100px;
        width: 380px;
        height: 380px;
        border: 1px solid rgba(204,0,204,0.12);
        border-radius: 50%;
        animation: pulse-r 7s ease-in-out infinite;
    }

    .deco-circle::before {
        content: '';
        position: absolute;
        inset: 35px;
        border: 1px solid rgba(204,0,204,0.18);
        border-radius: 50%;
        animation: pulse-r 7s ease-in-out infinite 1s;
    }

    .deco-circle::after {
        content: '';
        position: absolute;
        inset: 75px;
        border: 1px solid rgba(204,0,204,0.25);
        border-radius: 50%;
        animation: pulse-r 7s ease-in-out infinite 2s;
    }

    @keyframes pulse-r {
        0%, 100% { opacity: 0.5; transform: scale(1); }
        50%       { opacity: 1;   transform: scale(1.04); }
    }

    /* Brand block */
    .brand-block { position: relative; z-index: 2; }

    .brand-logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 2rem;
    }

    .brand-logo-icon {
        width: 44px; height: 44px;
        background: var(--pink);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 6px 20px rgba(204,0,204,0.45);
        flex-shrink: 0;
        position: relative;
    }

    .brand-logo-icon::before {
        content: '';
        position: absolute;
        inset: 4px;
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 7px;
    }

    .brand-logo-name {
        font-family: 'Nunito', sans-serif;
        font-size: 1.3rem;
        font-weight: 900;
        color: #ffffff;
        letter-spacing: -0.3px;
        line-height: 1.15;
    }

    .brand-logo-name span { color: var(--pink-light); }

    .brand-logo-sub {
        font-size: 0.62rem;
        letter-spacing: 0.2em;
        text-transform: lowercase;
        color: var(--text-light);
        font-weight: 700;
    }

    .brand-headline {
        font-family: 'Nunito', sans-serif;
        font-size: clamp(2.2rem, 3.5vw, 3.2rem);
        font-weight: 900;
        color: #ffffff;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }

    .brand-headline span { color: var(--pink-light); }

    .brand-desc {
        margin-top: 1.2rem;
        font-size: 0.88rem;
        color: var(--text-light);
        line-height: 1.75;
        max-width: 300px;
    }

    /* Feature pills */
    .feature-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        margin-top: 1.8rem;
        position: relative;
        z-index: 2;
    }

    .pill {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        padding: 0.35rem 0.8rem;
        font-size: 0.72rem;
        color: rgba(255,255,255,0.7);
        font-weight: 500;
    }

    .pill-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--pink);
        flex-shrink: 0;
        animation: blink 2.5s ease-in-out infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 0.7; }
        50%       { opacity: 1; box-shadow: 0 0 6px var(--pink); }
    }

    /* Stat row */
    .stat-row {
        position: relative;
        z-index: 2;
        display: flex;
        gap: 2.5rem;
    }

    .stat-divider {
        width: 100%;
        height: 1px;
        background: rgba(255,255,255,0.08);
        margin-bottom: 1.5rem;
    }

    .stat-item { display: flex; flex-direction: column; gap: 0.2rem; }

    .stat-value {
        font-family: 'Nunito', sans-serif;
        font-size: 1.7rem;
        font-weight: 900;
        color: var(--pink-light);
        letter-spacing: -0.5px;
    }

    .stat-label {
        font-size: 0.62rem;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: var(--text-light);
        font-weight: 600;
    }

    /* ── Right: login form panel ── */
    .admin-panel-right {
        background: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 4rem;
        position: relative;
        border-left: 3px solid var(--pink);
    }

    .login-card {
        width: 100%;
        max-width: 400px;
        animation: fade-up 0.55s ease both;
    }

    @keyframes fade-up {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Header */
    .login-header { margin-bottom: 2rem; }

    .login-eyebrow {
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.26em;
        text-transform: uppercase;
        color: var(--pink);
        margin-bottom: 0.7rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .login-eyebrow::before {
        content: '';
        width: 20px;
        height: 2px;
        background: var(--pink);
        border-radius: 2px;
    }

    .login-title {
        font-family: 'Nunito', sans-serif;
        font-size: 2rem;
        font-weight: 900;
        color: var(--navy);
        line-height: 1.1;
        letter-spacing: -0.5px;
    }

    .login-title span { color: var(--pink); }

    .login-subtitle {
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-mid);
        line-height: 1.5;
    }

    /* Error banner */
    .error-banner {
        background: rgba(192,57,43,0.06);
        border: 1px solid rgba(192,57,43,0.25);
        border-left: 3px solid var(--red);
        padding: 0.7rem 1rem;
        margin-bottom: 1.4rem;
        font-size: 0.8rem;
        color: var(--red);
        border-radius: 0 6px 6px 0;
        animation: fade-up 0.3s ease both;
    }

    /* Fields */
    .field-group { margin-bottom: 1.3rem; }

    .field-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--navy);
        margin-bottom: 0.5rem;
    }

    .field-wrap { position: relative; }

    .field-wrap input {
        width: 100%;
        background: var(--page-bg);
        border: 1.5px solid var(--border-soft);
        color: var(--navy);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.92rem;
        padding: 0.8rem 1rem 0.8rem 2.75rem;
        outline: none;
        border-radius: 8px;
        transition: border-color 0.22s, background 0.22s, box-shadow 0.22s;
    }

    .field-wrap input::placeholder { color: #b0bac8; }

    .field-wrap input:focus {
        border-color: var(--pink);
        background: var(--white);
        box-shadow: 0 0 0 3px rgba(204,0,204,0.1);
    }

    .field-icon {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: #b0bac8;
        pointer-events: none;
        transition: color 0.22s;
    }

    .field-wrap:focus-within .field-icon { color: var(--pink); }
    .field-group:focus-within .field-label { color: var(--pink); }

    /* Divider */
    .form-divider {
        height: 1px;
        background: var(--border-soft);
        margin: 1.6rem 0;
    }

    /* Submit button */
    .btn-login {
        width: 100%;
        background: var(--navy);
        border: none;
        color: #ffffff;
        font-family: 'Nunito', sans-serif;
        font-size: 0.85rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 0.95rem;
        cursor: pointer;
        border-radius: 8px;
        position: relative;
        overflow: hidden;
        transition: background 0.25s, transform 0.15s, box-shadow 0.25s;
        box-shadow: 0 4px 16px rgba(13,39,80,0.25);
    }

    .btn-login::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1) 50%, transparent);
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }

    .btn-login:hover::before { transform: translateX(100%); }

    .btn-login:hover {
        background: var(--navy-mid);
        box-shadow: 0 6px 20px rgba(13,39,80,0.35);
    }

    .btn-login:active { transform: scale(0.98); }

    .btn-login-inner {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        position: relative;
        z-index: 1;
    }

    /* Pink alt button bar */
    .btn-login-bar {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 3px;
        background: var(--pink);
        border-radius: 0 0 8px 8px;
        opacity: 0;
        transition: opacity 0.25s;
    }

    .btn-login:hover .btn-login-bar { opacity: 1; }

    /* SSL note */
    .security-note {
        margin-top: 1.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.68rem;
        color: var(--text-light);
        letter-spacing: 0.08em;
    }

    .security-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: var(--pink);
        opacity: 0.6;
        animation: blink 2.5s ease-in-out infinite;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-wrapper { grid-template-columns: 1fr; }
        .admin-panel-left { display: none; }
        .admin-panel-right {
            padding: 2rem 1.5rem;
            border-left: none;
            border-top: 3px solid var(--pink);
        }
    }
</style>

<div class="admin-wrapper">

    {{-- Left navy panel --}}
    <div class="admin-panel-left">
        <div class="deco-circle"></div>

        <div class="brand-block">
            <div class="brand-logo">
                <div class="brand-logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <div class="brand-logo-name">VS Global<span> Travels</span></div>
                    <div class="brand-logo-sub">flight itinerary</div>
                </div>
            </div>

            <h1 class="brand-headline">Admin<br><span>Control</span><br>Centre</h1>
            <p class="brand-desc">
                Restricted access portal for authorised personnel. All activity is monitored, encrypted, and logged.
            </p>
        </div>

        <div class="feature-pills">
            <span class="pill"><span class="pill-dot"></span>256-bit SSL</span>
            <span class="pill"><span class="pill-dot"></span>Session Logging</span>
            <span class="pill"><span class="pill-dot"></span>2FA Ready</span>
        </div>

        <div>
            <div class="stat-divider"></div>
            <div class="stat-row">
                <div class="stat-item">
                    <span class="stat-value">256</span>
                    <span class="stat-label">Bit Encrypt</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">24/7</span>
                    <span class="stat-label">Monitoring</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">99.9%</span>
                    <span class="stat-label">Uptime</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right login form --}}
    <div class="admin-panel-right">
        <div class="login-card">

            <div class="login-header">
                <p class="login-eyebrow">Authenticate</p>
                <h2 class="login-title">Secure<br><span>Sign In</span></h2>
                <p class="login-subtitle">Enter your credentials to access the admin dashboard.</p>
            </div>

            @if(session('error'))
            <div class="error-banner">
                ⚠ &nbsp;{{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="email">Email Address</label>
                    <div class="field-wrap">
                        <svg class="field-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/>
                        </svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="admin@vsglobaltravels.com"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                        >
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-wrap">
                        <svg class="field-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••••"
                            required
                            autocomplete="current-password"
                        >
                    </div>
                </div>

                <div class="form-divider"></div>

                <button type="submit" class="btn-login">
                    <span class="btn-login-inner">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        Access Dashboard
                    </span>
                    <span class="btn-login-bar"></span>
                </button>
            </form>

            <div class="security-note">
                <span class="security-dot"></span>
                SSL encrypted &amp; secure connection
                <span class="security-dot"></span>
            </div>

        </div>
    </div>

</div>

@endsection