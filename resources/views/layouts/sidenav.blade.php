{{-- resources/views/layouts/sidenav.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — VS Global Travels</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=DM+Sans:wght@300;400;500&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:       #0d2750;
            --navy-deep:  #091d3d;
            --navy-mid:   #14305e;
            --pink:       #cc00cc;
            --pink-light: #e500e5;
            --pink-dim:   rgba(204,0,204,0.12);
            --pink-border:rgba(204,0,204,0.3);
            --page-bg:    #f0f2f7;
            --white:      #ffffff;
            --border:     rgba(255,255,255,0.08);
            --text:       #e8edf5;
            --text-muted: #7a99c2;
            --sidebar-w:  268px;
            --sidebar-cw: 70px;
            --ease:       0.3s cubic-bezier(0.4,0,0.2,1);
        }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--page-bg);
        }

        /* ══ Layout shell ══ */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ══ Sidebar ══ */
        .admin-sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--navy);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: width var(--ease);
            overflow: hidden;
            box-shadow: 4px 0 24px rgba(9,29,61,0.35);
            flex-shrink: 0;
        }

        .admin-sidebar.collapsed { width: var(--sidebar-cw); }

        .admin-sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(204,0,204,0.05) 1px, transparent 1px);
            background-size: 22px 22px;
            pointer-events: none;
            z-index: 0;
        }

        .admin-sidebar::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--pink);
            z-index: 2;
        }

        /* Brand */
        .sidebar-brand {
            padding: 1.4rem 1.15rem 1.2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
            flex-shrink: 0;
            margin-top: 3px;
        }

        .sidebar-brand-inner {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            overflow: hidden;
            text-decoration: none;
        }

        .brand-icon {
            width: 36px; height: 36px;
            background: var(--pink);
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px;
            box-shadow: 0 4px 14px rgba(204,0,204,0.4);
            position: relative;
        }

        .brand-icon::before {
            content: '';
            position: absolute;
            inset: 3px;
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 5px;
        }

        .brand-icon svg { position: relative; z-index: 1; }

        .brand-text { overflow: hidden; }

        .brand-title {
            font-family: 'Nunito', sans-serif;
            font-size: 1rem;
            font-weight: 900;
            color: #ffffff;
            white-space: nowrap;
            letter-spacing: -0.3px;
            line-height: 1.1;
        }

        .brand-title .pink { color: var(--pink-light); }

        .brand-sub {
            font-size: 0.6rem;
            letter-spacing: 0.2em;
            text-transform: lowercase;
            color: var(--text-muted);
            white-space: nowrap;
            font-weight: 700;
            margin-top: 1px;
        }

        .sidebar-toggle {
            width: 26px; height: 26px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.05);
            color: var(--text-muted);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            border-radius: 5px;
            transition: border-color 0.2s, color 0.2s, background 0.2s, transform var(--ease);
        }

        .sidebar-toggle:hover {
            border-color: var(--pink-border);
            color: var(--pink-light);
            background: var(--pink-dim);
        }

        .admin-sidebar.collapsed .sidebar-toggle { transform: rotate(180deg); }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
            z-index: 1;
        }

        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

        .nav-section { margin-bottom: 0.4rem; }

        .nav-section-label {
            font-size: 0.56rem;
            letter-spacing: 0.26em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 0.55rem 1.15rem 0.35rem;
            white-space: nowrap;
            font-weight: 700;
            transition: opacity var(--ease);
        }

        .admin-sidebar.collapsed .nav-section-label { opacity: 0; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.62rem 1.15rem;
            color: var(--text-muted);
            text-decoration: none;
            position: relative;
            transition: color 0.2s, background 0.2s;
            white-space: nowrap;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%) scaleY(0);
            width: 3px; height: 55%;
            background: var(--pink);
            border-radius: 0 2px 2px 0;
            transition: transform 0.2s;
        }

        .nav-item:hover { color: #ffffff; background: rgba(255,255,255,0.06); }
        .nav-item:hover::before { transform: translateY(-50%) scaleY(1); }
        .nav-item.active { color: #ffffff; background: var(--pink-dim); }
        .nav-item.active::before { transform: translateY(-50%) scaleY(1); }
        .nav-item.active svg { stroke: var(--pink-light); }

        .nav-icon {
            width: 18px; height: 18px;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }

        .nav-label {
            transition: opacity var(--ease), transform var(--ease);
            flex: 1;
        }

        .admin-sidebar.collapsed .nav-label {
            opacity: 0;
            pointer-events: none;
            transform: translateX(-6px);
        }

        .nav-badge {
            font-size: 0.56rem;
            font-weight: 800;
            padding: 0.12rem 0.4rem;
            background: var(--pink);
            color: #fff;
            line-height: 1.7;
            border-radius: 20px;
            transition: opacity var(--ease);
            font-family: 'Nunito', sans-serif;
        }

        .admin-sidebar.collapsed .nav-badge { opacity: 0; }

        .nav-divider {
            height: 1px;
            background: var(--border);
            margin: 0.5rem 1.15rem;
        }

        /* Collapsed tooltip */
        .admin-sidebar.collapsed .nav-item[data-tip]:hover::after {
            content: attr(data-tip);
            position: absolute;
            left: calc(var(--sidebar-cw) + 10px);
            top: 50%; transform: translateY(-50%);
            background: var(--navy-deep);
            border: 1px solid var(--pink-border);
            color: #fff;
            font-size: 0.75rem;
            padding: 0.3rem 0.7rem;
            white-space: nowrap;
            pointer-events: none;
            z-index: 200;
            border-radius: 5px;
            box-shadow: 0 4px 16px rgba(9,29,61,0.4);
        }

        .admin-sidebar.collapsed .nav-item[data-tip]:hover::before { display: none; }

        /* Footer */
        .sidebar-footer {
            border-top: 1px solid var(--border);
            padding: 1rem 1.15rem;
            position: relative;
            z-index: 1;
            flex-shrink: 0;
            background: var(--navy-deep);
        }

        .user-card { display: flex; align-items: center; gap: 0.7rem; overflow: hidden; }

        .user-avatar {
            width: 34px; height: 34px;
            background: var(--pink);
            border-radius: 8px;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Nunito', sans-serif;
            font-size: 1rem; font-weight: 900; color: #fff;
            box-shadow: 0 3px 10px rgba(204,0,204,0.4);
        }

        .user-info { overflow: hidden; flex: 1; transition: opacity var(--ease); }
        .admin-sidebar.collapsed .user-info { opacity: 0; pointer-events: none; }

        .user-name { font-size: 0.82rem; font-weight: 600; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 0.6rem; letter-spacing: 0.15em; text-transform: lowercase; color: var(--pink-light); font-weight: 700; }

        .logout-btn {
            width: 28px; height: 28px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            color: var(--text-muted);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            text-decoration: none;
            border-radius: 6px;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }

        .logout-btn:hover { border-color: var(--pink-border); color: var(--pink-light); background: var(--pink-dim); }
        .admin-sidebar.collapsed .logout-btn { display: none; }

        /* ══ Main Content ══ */
        .admin-main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            transition: margin-left var(--ease);
            background: var(--page-bg);
        }

        body.sidebar-collapsed .admin-main { margin-left: var(--sidebar-cw); }

        /* ══ Mobile ══ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(9,29,61,0.65);
            z-index: 99;
        }

        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); transition: transform var(--ease), width var(--ease); }
            .admin-sidebar.mobile-open { transform: translateX(0); width: var(--sidebar-w) !important; }
            .sidebar-overlay.visible { display: block; }
            .admin-main { margin-left: 0 !important; }
            .mobile-menu-btn { display: flex !important; }
        }

        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 1rem; left: 1rem;
            z-index: 98;
            width: 40px; height: 40px;
            background: var(--navy);
            border: 1px solid var(--pink-border);
            color: var(--pink-light);
            cursor: pointer;
            align-items: center; justify-content: center;
            border-radius: 8px;
            box-shadow: 0 4px 14px rgba(9,29,61,0.4);
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleMobileSidebar()">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="3" y1="6" x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
    </svg>
</button>

<div class="admin-layout">

    {{-- ══ Sidebar ══ --}}
    <aside class="admin-sidebar" id="adminSidebar">

        <div class="sidebar-brand">
            <a href="#" class="sidebar-brand-inner">
                <div class="brand-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <div class="brand-title">VS Global<span class="pink"> Travels</span></div>
                    <div class="brand-sub">admin console</div>
                </div>
            </a>
            <button class="sidebar-toggle" onclick="toggleSidebar()" title="Toggle sidebar">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>
        </div>

        <nav class="sidebar-nav">

            <div class="nav-section">
                <div class="nav-section-label">Main</div>
                <a href="{{ route('admin.dashboard')}}" data-tip="Dashboard"
                   class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                        </svg>
                    </span>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a href="{{ route('admin.add-country')}}" data-tip="Analytics"
                   class="nav-item {{ request()->is('admin/analytics') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                    </span>
                    <span class="nav-label">Add Country</span>
                </a>
            </div>

            <div class="nav-divider"></div>

            <div class="nav-section">
                <div class="nav-section-label">Manage</div>

                <a href="{{ route('admin.country-list')}}" data-tip="Orders"
                   class="nav-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </span>
                    <span class="nav-label">Country Images</span>
                    {{-- <span class="nav-badge">5</span> --}}
                </a>

                <a href="#" data-tip="Users"
                   class="nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </span>
                    <span class="nav-label">Users</span>
                </a>

                <a href="#" data-tip="Countries"
                   class="nav-item {{ request()->is('admin/countries*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="2" y1="12" x2="22" y2="12"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </span>
                    <span class="nav-label">Countries</span>
                </a>

                <a href="#" data-tip="Itineraries"
                   class="nav-item {{ request()->is('admin/itineraries*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                    </span>
                    <span class="nav-label">Itineraries</span>
                </a>

                <a href="#" data-tip="Payments"
                   class="nav-item {{ request()->is('admin/payments*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="1" y="4" width="22" height="16" rx="2"/>
                            <line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                    </span>
                    <span class="nav-label">Payments</span>
                </a>

                <a href="#" data-tip="Messages"
                   class="nav-item {{ request()->is('admin/messages*') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </span>
                    <span class="nav-label">Messages</span>
                    <span class="nav-badge">3</span>
                </a>
            </div>

            <div class="nav-divider"></div>

            <div class="nav-section">
                <div class="nav-section-label">System</div>

                <a href="#" data-tip="Settings"
                   class="nav-item {{ request()->is('admin/settings') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                        </svg>
                    </span>
                    <span class="nav-label">Settings</span>
                </a>

                <a href="#" data-tip="Logs"
                   class="nav-item {{ request()->is('admin/logs') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <line x1="8" y1="6" x2="21" y2="6"/>
                            <line x1="8" y1="12" x2="21" y2="12"/>
                            <line x1="8" y1="18" x2="21" y2="18"/>
                            <line x1="3" y1="6" x2="3.01" y2="6"/>
                            <line x1="3" y1="12" x2="3.01" y2="12"/>
                            <line x1="3" y1="18" x2="3.01" y2="18"/>
                        </svg>
                    </span>
                    <span class="nav-label">Logs</span>
                </a>
            </div>

        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name ?? 'Administrator' }}</div>
                    <div class="user-role">super admin</div>
                </div>
                <a href="#"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="logout-btn" title="Logout">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </a>
            </div>
            <form id="logout-form" action="#" method="POST" style="display:none;">
                @csrf
            </form>
        </div>

    </aside>

    {{-- ══ Main Content Area ══ --}}
    <main class="admin-main" id="adminMain">
        @yield('content')
    </main>

</div>{{-- /admin-layout --}}

<script>
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    // Restore persisted state
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
        document.body.classList.add('sidebar-collapsed');
    }

    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    }

    function toggleMobileSidebar() {
        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('visible');
    }
</script>

</body>
</html>