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
        --surface-3: #242428;
        --border: #2a2a2e;
        --text: #e8e6e0;
        --text-muted: #6b6b72;
        --red: #c0392b;
        --green: #27ae60;
    }

    body {
        background-color: var(--charcoal);
        color: var(--text);
        font-family: 'Courier Prime', monospace;
        min-height: 100vh;
    }

    /* ── Dashboard wrapper ── */
    .dashboard-wrap {
        padding: 2.5rem 2.8rem;
        min-height: 100vh;
    }

    /* ── Page header ── */
    .dash-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 2.4rem;
        padding-bottom: 1.6rem;
        border-bottom: 1px solid var(--border);
        position: relative;
    }

    .dash-header::after {
        content: '';
        position: absolute;
        bottom: -1px; left: 0;
        width: 80px; height: 1px;
        background: var(--gold);
    }

    .dash-eyebrow {
        font-size: 0.62rem;
        letter-spacing: 0.3em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.45rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .dash-eyebrow::before {
        content: '';
        display: block;
        width: 22px; height: 1px;
        background: var(--gold);
    }

    .dash-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem;
        font-weight: 300;
        color: var(--text);
        letter-spacing: -0.01em;
        line-height: 1;
    }

    .dash-title strong { font-weight: 600; color: var(--gold); }

    .dash-date {
        font-size: 0.6rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--text-muted);
    }

    /* ── KPI strip ── */
    .kpi-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1px;
        background: var(--border);
        border: 1px solid var(--border);
        margin-bottom: 1.6rem;
    }

    .kpi-cell {
        background: var(--surface);
        padding: 1.4rem 1.3rem;
        position: relative;
        overflow: hidden;
        transition: background 0.25s;
        cursor: default;
    }

    .kpi-cell::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0;
        width: 0; height: 2px;
        background: var(--gold);
        transition: width 0.4s ease;
    }

    .kpi-cell:hover { background: var(--surface-2); }
    .kpi-cell:hover::after { width: 100%; }

    .kpi-label {
        font-size: 0.57rem;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .kpi-value {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.2rem;
        font-weight: 600;
        color: var(--text);
        line-height: 1;
        margin-bottom: 0.6rem;
    }

    .kpi-value span { color: var(--gold); }

    .kpi-delta {
        font-size: 0.62rem;
        letter-spacing: 0.08em;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .kpi-delta.up   { color: #4cce84; }
    .kpi-delta.down { color: #e57368; }
    .kpi-delta.neutral { color: var(--text-muted); }

    /* ── Card base ── */
    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        padding: 1.5rem;
        position: relative;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 16px; height: 16px;
        border-top: 1px solid rgba(201,168,76,0.35);
        border-left: 1px solid rgba(201,168,76,0.35);
        pointer-events: none;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.3rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .card-eyebrow {
        font-size: 0.57rem;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.25rem;
    }

    .card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.2rem;
        font-weight: 300;
        color: var(--text);
    }

    .card-badge {
        font-size: 0.58rem;
        letter-spacing: 0.14em;
        padding: 0.22rem 0.6rem;
        border: 1px solid;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .badge-gold  { border-color: rgba(201,168,76,0.4); color: var(--gold);  background: rgba(201,168,76,0.06); }
    .badge-red   { border-color: rgba(192,57,43,0.4);  color: #e57368;      background: rgba(192,57,43,0.06); }
    .badge-green { border-color: rgba(39,174,96,0.4);  color: #4cce84;      background: rgba(39,174,96,0.06); }
    .badge-muted { border-color: var(--border); color: var(--text-muted); background: transparent; }

    /* ── Main grid ── */
    .main-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.2rem;
        margin-bottom: 1.2rem;
    }

    /* ── Revenue bar chart (spans 2) ── */
    .revenue-card { grid-column: span 2; }

    .bar-chart {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        height: 84px;
        margin-top: 0.4rem;
    }

    .bar-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        gap: 5px;
        height: 100%;
    }

    .bar-fill {
        width: 100%;
        background: var(--surface-3);
        border-top: 1px solid var(--border);
        transition: background 0.25s;
        cursor: default;
    }

    .bar-fill.active {
        background: linear-gradient(180deg, var(--gold) 0%, rgba(201,168,76,0.25) 100%);
        border-top-color: var(--gold-light);
    }

    .bar-fill:hover { background: rgba(201,168,76,0.45); }

    .bar-lbl {
        font-size: 0.52rem;
        letter-spacing: 0.1em;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .rev-totals {
        display: flex;
        gap: 2rem;
        margin-top: 1.3rem;
        padding-top: 1.1rem;
        border-top: 1px solid var(--border);
    }

    .rev-t-label {
        font-size: 0.57rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.35rem;
    }

    .rev-t-value {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.45rem;
        font-weight: 600;
        color: var(--text);
    }

    .rev-t-value span { color: var(--gold); }

    /* ── Pending payments list ── */
    .payment-list { display: flex; flex-direction: column; }

    .payment-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border);
        transition: padding 0.2s, background 0.2s;
        cursor: default;
    }

    .payment-item:last-child { border-bottom: none; }

    .payment-item:hover {
        background: rgba(201,168,76,0.03);
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        margin: 0 -0.5rem;
    }

    .pay-left { display: flex; align-items: center; gap: 0.7rem; }

    .pay-avatar {
        width: 28px; height: 28px;
        background: var(--surface-3);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Cormorant Garamond', serif;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--gold);
        flex-shrink: 0;
    }

    .pay-name {
        font-size: 0.75rem;
        color: var(--text);
        letter-spacing: 0.04em;
        margin-bottom: 0.12rem;
    }

    .pay-ref {
        font-size: 0.58rem;
        color: var(--text-muted);
        letter-spacing: 0.1em;
    }

    .pay-right { text-align: right; }

    .pay-amount {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--gold);
    }

    .pay-due {
        font-size: 0.57rem;
        color: var(--text-muted);
        letter-spacing: 0.08em;
        margin-top: 0.12rem;
    }

    .pay-due.overdue { color: #e57368; }

    /* ── Bottom grid ── */
    .bottom-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.2rem;
    }

    /* ── Activity feed ── */
    .activity-list { display: flex; flex-direction: column; }

    .activity-item {
        display: grid;
        grid-template-columns: 14px 1fr auto;
        align-items: start;
        gap: 0.7rem;
        padding: 0.78rem 0;
        border-bottom: 1px solid var(--border);
    }

    .activity-item:last-child { border-bottom: none; }

    .a-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        margin-top: 4px;
        flex-shrink: 0;
    }

    .a-dot.gold  { background: var(--gold);  box-shadow: 0 0 5px rgba(201,168,76,0.5); }
    .a-dot.green { background: #27ae60;       box-shadow: 0 0 5px rgba(39,174,96,0.5); }
    .a-dot.red   { background: #c0392b;       box-shadow: 0 0 5px rgba(192,57,43,0.5); }
    .a-dot.muted { background: var(--text-muted); }

    .a-text {
        font-size: 0.73rem;
        color: var(--text);
        line-height: 1.55;
        letter-spacing: 0.02em;
    }

    .a-text .hl { color: var(--gold); }

    .a-time {
        font-size: 0.58rem;
        color: var(--text-muted);
        letter-spacing: 0.1em;
        white-space: nowrap;
        margin-top: 2px;
    }

    /* ── Quick stats ── */
    .quick-list { display: flex; flex-direction: column; }

    .quick-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.72rem 0;
        border-bottom: 1px solid var(--border);
    }

    .quick-item:last-child { border-bottom: none; }

    .q-label {
        font-size: 0.62rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--text-muted);
    }

    .q-val {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--gold);
    }

    .q-bar-wrap {
        width: 100%;
        height: 2px;
        background: var(--surface-3);
        margin-top: 0.4rem;
        margin-bottom: 0.1rem;
    }

    .q-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--gold), var(--gold-light));
    }

    /* ── Responsive ── */
    @media (max-width: 1024px) {
        .kpi-strip       { grid-template-columns: repeat(2,1fr); }
        .main-grid       { grid-template-columns: 1fr; }
        .revenue-card    { grid-column: span 1; }
        .bottom-grid     { grid-template-columns: 1fr; }
        .dashboard-wrap  { padding: 1.5rem 1.2rem; }
    }
</style>

<div class="dashboard-wrap">

    {{-- ── Page header ── --}}
    <div class="dash-header">
        <div>
            <div class="dash-eyebrow">Command Centre</div>
            <h1 class="dash-title">Admin <strong>Dashboard</strong></h1>
        </div>
        <div class="dash-date">{{ now()->format('l — d F Y') }}</div>
    </div>

    {{-- ── KPI strip ── --}}
    <div class="kpi-strip">
        <div class="kpi-cell">
            <a href="{{route('visa.applications')}}">
            <div class="kpi-label">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Pending Payments Applications
            </div>
            </a>
            <div class="kpi-value">{{$pendingPayments}}</div>
            {{-- <div class="kpi-delta up">↑ 12% from last month</div> --}}
        </div>
        <div class="kpi-cell">
            <div class="kpi-label">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3H8L2 7h20l-6-4z"/></svg>
                Total Visa Applications
            </div>
            <div class="kpi-value">{{$application}}</div>
            {{-- <div class="kpi-delta up">↑ 8.3% YTD</div> --}}
        </div>
     
    </div>

    {{-- ── Main grid ── --}}
    <div class="main-grid">

        {{-- Revenue chart --}}
        {{-- <div class="card revenue-card">
            <div class="card-header">
                <div>
                    <div class="card-eyebrow">Monthly Overview</div>
                    <div class="card-title">Revenue Breakdown</div>
                </div>
                <span class="card-badge badge-gold">FY 2025–26</span>
            </div>

            <div class="bar-chart">
                <div class="bar-group"><div class="bar-fill" style="height:38%"></div><div class="bar-lbl">Nov</div></div>
                <div class="bar-group"><div class="bar-fill" style="height:55%"></div><div class="bar-lbl">Dec</div></div>
                <div class="bar-group"><div class="bar-fill" style="height:44%"></div><div class="bar-lbl">Jan</div></div>
                <div class="bar-group"><div class="bar-fill" style="height:67%"></div><div class="bar-lbl">Feb</div></div>
                <div class="bar-group"><div class="bar-fill" style="height:72%"></div><div class="bar-lbl">Mar</div></div>
                <div class="bar-group"><div class="bar-fill active" style="height:89%"></div><div class="bar-lbl">Apr</div></div>
            </div>

            <div class="rev-totals">
                <div>
                    <div class="rev-t-label">This Month</div>
                    <div class="rev-t-value"><span>₹</span>6,14,800</div>
                </div>
                <div>
                    <div class="rev-t-label">Last Month</div>
                    <div class="rev-t-value">₹5,62,300</div>
                </div>
                <div>
                    <div class="rev-t-label">Annual Target</div>
                    <div class="rev-t-value">₹72,00,000</div>
                </div>
            </div>
        </div> --}}

        {{-- Pending Payments list --}}
        {{-- <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-eyebrow">Awaiting Clearance</div>
                    <div class="card-title">Pending Payments</div>
                </div>
                <span class="card-badge badge-red">7 due</span>
            </div>

            <div class="payment-list">
                <div class="payment-item">
                    <div class="pay-left">
                        <div class="pay-avatar">AK</div>
                        <div>
                            <div class="pay-name">Arjun Kapoor</div>
                            <div class="pay-ref">#INV-2026-084</div>
                        </div>
                    </div>
                    <div class="pay-right">
                        <div class="pay-amount">₹18,500</div>
                        <div class="pay-due overdue">Overdue 3d</div>
                    </div>
                </div>
                <div class="payment-item">
                    <div class="pay-left">
                        <div class="pay-avatar">SM</div>
                        <div>
                            <div class="pay-name">Sunita Mehta</div>
                            <div class="pay-ref">#INV-2026-091</div>
                        </div>
                    </div>
                    <div class="pay-right">
                        <div class="pay-amount">₹42,000</div>
                        <div class="pay-due overdue">Overdue 7d</div>
                    </div>
                </div>
                <div class="payment-item">
                    <div class="pay-left">
                        <div class="pay-avatar">RV</div>
                        <div>
                            <div class="pay-name">Rahul Verma</div>
                            <div class="pay-ref">#INV-2026-095</div>
                        </div>
                    </div>
                    <div class="pay-right">
                        <div class="pay-amount">₹9,250</div>
                        <div class="pay-due">Due Apr 25</div>
                    </div>
                </div>
                <div class="payment-item">
                    <div class="pay-left">
                        <div class="pay-avatar">PT</div>
                        <div>
                            <div class="pay-name">Priya Tiwari</div>
                            <div class="pay-ref">#INV-2026-098</div>
                        </div>
                    </div>
                    <div class="pay-right">
                        <div class="pay-amount">₹31,000</div>
                        <div class="pay-due">Due Apr 30</div>
                    </div>
                </div>
                <div class="payment-item">
                    <div class="pay-left">
                        <div class="pay-avatar">NS</div>
                        <div>
                            <div class="pay-name">Nikhil Sharma</div>
                            <div class="pay-ref">#INV-2026-102</div>
                        </div>
                    </div>
                    <div class="pay-right">
                        <div class="pay-amount">₹5,750</div>
                        <div class="pay-due">Due May 3</div>
                    </div>
                </div>
            </div>
        </div> --}}

    </div>{{-- /main-grid --}}

    {{-- ── Bottom grid ── --}}
    {{-- <div class="bottom-grid">

        {{-- Recent Activity --}}
        {{-- <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-eyebrow">Live Feed</div>
                    <div class="card-title">Recent Activity</div>
                </div>
                <span class="card-badge badge-muted">Today</span>
            </div>

            <div class="activity-list">
                <div class="activity-item">
                    <div class="a-dot green"></div>
                    <div class="a-text">Payment of <span class="hl">₹24,000</span> received from Deepa Nair — INV-2026-079</div>
                    <div class="a-time">09:14</div>
                </div>
                <div class="activity-item">
                    <div class="a-dot gold"></div>
                    <div class="a-text">New member registered: <span class="hl">Kartik Joshi</span> — Gold tier</div>
                    <div class="a-time">10:02</div>
                </div>
                <div class="activity-item">
                    <div class="a-dot red"></div>
                    <div class="a-text">Invoice <span class="hl">#INV-2026-084</span> flagged overdue — reminder sent</div>
                    <div class="a-time">11:30</div>
                </div>
                <div class="activity-item">
                    <div class="a-dot gold"></div>
                    <div class="a-text">Membership updated for <span class="hl">Ritika Bansal</span> — Premium → Elite</div>
                    <div class="a-time">13:15</div>
                </div>
                <div class="activity-item">
                    <div class="a-dot green"></div>
                    <div class="a-text">Bulk payment of <span class="hl">₹1,12,000</span> cleared — HDFC-229</div>
                    <div class="a-time">14:48</div>
                </div>
                <div class="activity-item">
                    <div class="a-dot muted"></div>
                    <div class="a-text">Admin report exported by <span class="hl">System</span> — Q1 Summary PDF</div>
                    <div class="a-time">15:00</div>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        {{-- <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-eyebrow">Snapshot</div>
                    <div class="card-title">Quick Stats</div>
                </div>
            </div> --}}

            {{-- <div class="quick-list">
                <div>
                    <div class="quick-item">
                        <div class="q-label">Collection Rate</div>
                        <div class="q-val">84%</div>
                    </div>
                    <div class="q-bar-wrap"><div class="q-bar-fill" style="width:84%"></div></div>
                </div>
                <div>
                    <div class="quick-item">
                        <div class="q-label">Capacity Utilisation</div>
                        <div class="q-val">71%</div>
                    </div>
                    <div class="q-bar-wrap"><div class="q-bar-fill" style="width:71%"></div></div>
                </div>
                <div>
                    <div class="quick-item">
                        <div class="q-label">Renewals This Month</div>
                        <div class="q-val">63%</div>
                    </div>
                    <div class="q-bar-wrap"><div class="q-bar-fill" style="width:63%"></div></div>
                </div>
                <div>
                    <div class="quick-item">
                        <div class="q-label">Target Achievement</div>
                        <div class="q-val">58%</div>
                    </div>
                    <div class="q-bar-wrap"><div class="q-bar-fill" style="width:58%"></div></div>
                </div>
                <div class="quick-item">
                    <div class="q-label">New Sign-ups</div>
                    <div class="q-val">92</div>
                </div>
            </div>
        </div> --}}

    {{-- </div>  --}} 

</div>{{-- /dashboard-wrap --}}

@endsection