@extends('layouts.sidenav')

@section('title', 'Admin Dashboard')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Courier+Prime:wght@400;700&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --gold: #c9a84c;
        --gold-light: #e8c97a;
        --charcoal: #0e0e0f;
        --surface: #161618;
        --surface-2: #1e1e21;
        --border: #2a2a2e;
        --text: #e8e6e0;
        --text-muted: #6b6b72;
        --red: #c0392b;
    }

    body {
        background-color: var(--charcoal);
        color: var(--text);
        font-family: 'Courier Prime', monospace;
        min-height: 100vh;
    }

    .admin-wrapper {
        min-height: 100vh;
        display: grid;
        grid-template-columns: 1fr 1fr;
        position: relative;
        overflow: hidden;
    }

    /* ── Left decorative panel ── */
    .admin-panel-left {
        background: var(--surface);
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 3rem;
        overflow: hidden;
    }

    .grid-overlay {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(201,168,76,0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(201,168,76,0.05) 1px, transparent 1px);
        background-size: 48px 48px;
        pointer-events: none;
    }

    .diagonal-accent {
        position: absolute;
        top: -80px;
        right: -80px;
        width: 320px;
        height: 320px;
        border: 1px solid rgba(201,168,76,0.12);
        border-radius: 50%;
        animation: pulse-ring 6s ease-in-out infinite;
    }

    .diagonal-accent::before {
        content: '';
        position: absolute;
        inset: 28px;
        border: 1px solid rgba(201,168,76,0.18);
        border-radius: 50%;
        animation: pulse-ring 6s ease-in-out infinite 0.8s;
    }

    .diagonal-accent::after {
        content: '';
        position: absolute;
        inset: 60px;
        border: 1px solid rgba(201,168,76,0.24);
        border-radius: 50%;
        animation: pulse-ring 6s ease-in-out infinite 1.6s;
    }

    @keyframes pulse-ring {
        0%, 100% { opacity: 0.6; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.03); }
    }

    .brand-block {
        position: relative;
        z-index: 2;
    }

    .brand-eyebrow {
        font-family: 'Courier Prime', monospace;
        font-size: 0.65rem;
        letter-spacing: 0.3em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .brand-eyebrow::before {
        content: '';
        display: block;
        width: 28px;
        height: 1px;
        background: var(--gold);
    }

    .brand-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2.5rem, 4vw, 3.8rem);
        font-weight: 300;
        line-height: 1.05;
        color: var(--text);
        letter-spacing: -0.01em;
    }

    .brand-name span {
        color: var(--gold);
        font-weight: 600;
    }

    .brand-tagline {
        margin-top: 1.5rem;
        font-size: 0.75rem;
        color: var(--text-muted);
        letter-spacing: 0.12em;
        max-width: 280px;
        line-height: 1.8;
    }

    .stat-row {
        position: relative;
        z-index: 2;
        display: flex;
        gap: 2.5rem;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .stat-value {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--gold);
    }

    .stat-label {
        font-size: 0.6rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--text-muted);
    }

    .left-bottom-line {
        width: 100%;
        height: 1px;
        background: linear-gradient(90deg, var(--gold) 0%, transparent 100%);
        margin-bottom: 1.5rem;
        opacity: 0.35;
    }

    /* ── Right login panel ── */
    .admin-panel-right {
        background: var(--charcoal);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 4rem;
        position: relative;
    }

    .login-card {
        width: 100%;
        max-width: 400px;
        animation: fade-up 0.6s ease both;
    }

    @keyframes fade-up {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .login-header {
        margin-bottom: 2.5rem;
    }

    .login-header-tag {
        font-size: 0.62rem;
        letter-spacing: 0.3em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .login-header-tag::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .login-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.2rem;
        font-weight: 300;
        color: var(--text);
        letter-spacing: -0.01em;
    }

    .login-title strong {
        font-weight: 600;
    }

    .login-subtitle {
        margin-top: 0.6rem;
        font-size: 0.72rem;
        color: var(--text-muted);
        letter-spacing: 0.08em;
    }

    /* Error */
    .error-banner {
        background: rgba(192,57,43,0.12);
        border: 1px solid rgba(192,57,43,0.35);
        border-left: 3px solid var(--red);
        padding: 0.75rem 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.78rem;
        color: #e57368;
        letter-spacing: 0.03em;
        animation: fade-up 0.3s ease both;
    }

    /* Form fields */
    .field-group {
        margin-bottom: 1.4rem;
    }

    .field-label {
        display: block;
        font-size: 0.62rem;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.6rem;
        transition: color 0.2s;
    }

    .field-wrap {
        position: relative;
    }

    .field-wrap input {
        width: 100%;
        background: var(--surface);
        border: 1px solid var(--border);
        color: var(--text);
        font-family: 'Courier Prime', monospace;
        font-size: 0.88rem;
        padding: 0.85rem 1rem 0.85rem 2.8rem;
        outline: none;
        transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        -webkit-appearance: none;
    }

    .field-wrap input::placeholder {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .field-wrap input:focus {
        border-color: var(--gold);
        background: var(--surface-2);
        box-shadow: 0 0 0 3px rgba(201,168,76,0.08), inset 0 1px 3px rgba(0,0,0,0.3);
    }

    .field-wrap input:focus + .field-focus-line {
        width: 100%;
    }

    .field-icon {
        position: absolute;
        left: 0.95rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
        transition: color 0.25s;
    }

    .field-wrap:focus-within .field-icon {
        color: var(--gold);
    }

    .field-wrap:focus-within + .field-label,
    .field-group:focus-within .field-label {
        color: var(--gold);
    }

    /* Divider */
    .form-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--border) 30%, var(--border) 70%, transparent);
        margin: 1.8rem 0;
    }

    /* Submit button */
    .btn-login {
        width: 100%;
        background: var(--gold);
        border: none;
        color: #0a0a0b;
        font-family: 'Courier Prime', monospace;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        padding: 1rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: background 0.25s, transform 0.15s;
    }

    .btn-login::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.15) 50%, transparent 100%);
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }

    .btn-login:hover::before {
        transform: translateX(100%);
    }

    .btn-login:hover {
        background: var(--gold-light);
    }

    .btn-login:active {
        transform: scale(0.98);
    }

    .btn-login-inner {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
    }

    /* Security badge */
    .security-note {
        margin-top: 1.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.62rem;
        color: var(--text-muted);
        letter-spacing: 0.12em;
    }

    .security-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--gold);
        opacity: 0.6;
        animation: blink 2.5s ease-in-out infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
    }

    /* Corner decorations on right panel */
    .corner-tl, .corner-br {
        position: absolute;
        width: 40px;
        height: 40px;
        pointer-events: none;
    }

    .corner-tl {
        top: 24px; left: 24px;
        border-top: 1px solid rgba(201,168,76,0.3);
        border-left: 1px solid rgba(201,168,76,0.3);
    }

    .corner-br {
        bottom: 24px; right: 24px;
        border-bottom: 1px solid rgba(201,168,76,0.3);
        border-right: 1px solid rgba(201,168,76,0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-wrapper { grid-template-columns: 1fr; }
        .admin-panel-left { display: none; }
        .admin-panel-right { padding: 2rem 1.5rem; }
    }
</style>

<div>

    
</div>
@endsection