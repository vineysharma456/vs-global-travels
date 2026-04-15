{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My App')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed: 80px;
            --header-height: 65px;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fe;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        
        .sidebar-logo {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .sidebar-logo i { font-size: 22px; color: white; }
        .sidebar-brand { font-size: 22px; font-weight: 700; color: white; white-space: nowrap; }
        .sidebar-brand span { color: #818cf8; }
        
        .sidebar-menu { flex: 1; padding: 16px 12px; overflow-y: auto; }
        
        .menu-label {
            color: #64748b;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 16px 14px 8px;
        }
        
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            color: #94a3b8;
            border-radius: 10px;
            transition: all 0.2s;
            text-decoration: none;
            margin-bottom: 4px;
            font-weight: 500;
        }
        
        .sidebar .nav-link i { font-size: 20px; width: 24px; text-align: center; flex-shrink: 0; }
        .sidebar .nav-link:hover { color: white; background: rgba(255,255,255,0.08); }
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }
        .sidebar .nav-link .badge { margin-left: auto; background: #ef4444; font-size: 11px; }
        
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        
        .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
        }
        
        .user-avatar {
            width: 40px; height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        .user-info { flex: 1; min-width: 0; }
        .user-name { color: white; font-weight: 600; font-size: 14px; }
        .user-role { color: #64748b; font-size: 12px; }

        /* HEADER */
        .main-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            z-index: 999;
            display: flex;
            align-items: center;
            padding: 0 24px;
            transition: all 0.3s ease;
        }
        
        .toggle-btn {
            width: 40px; height: 40px;
            border: none;
            background: #f1f5f9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: #475569;
        }
        
        .toggle-btn:hover { background: #6366f1; color: white; }
        
        .search-box { position: relative; width: 300px; margin-left: 20px; }
        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 42px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            background: #f8fafc;
            transition: all 0.2s;
        }
        .search-box input:focus {
            outline: none;
            border-color: #6366f1;
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .search-box > i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        
        .header-right { display: flex; align-items: center; gap: 8px; margin-left: auto; }
        
        .header-btn {
            width: 42px; height: 42px;
            border: none;
            background: #f1f5f9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            color: #475569;
            text-decoration: none;
        }
        
        .header-btn:hover { background: #6366f1; color: white; }
        .header-btn i { font-size: 18px; }
        
        .notif-dot {
            position: absolute;
            top: 8px; right: 8px;
            width: 9px; height: 9px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
        }
        
        .header-divider { width: 1px; height: 32px; background: #e2e8f0; margin: 0 8px; }
        
        .header-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 6px;
            background: #f1f5f9;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .header-profile:hover { background: #e0e7ff; }
        
        .header-avatar {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 13px;
        }
        
        .header-user-name { font-weight: 600; font-size: 13px; color: #1e293b; }
        .header-user-role { font-size: 11px; color: #64748b; }

        /* MAIN CONTENT */
        .main-content {
            /* margin-left: var(--sidebar-width); */
            /* margin-top: var(--header-height); */
            padding: 24px;
            min-height: calc(100vh - var(--header-height));
            transition: all 0.3s ease;
        }

        /* COLLAPSED STATE - Using body class instead of sibling selector */
        body.sidebar-collapsed .sidebar { width: var(--sidebar-collapsed); }
        body.sidebar-collapsed .sidebar-brand,
        body.sidebar-collapsed .menu-label,
        body.sidebar-collapsed .nav-link span,
        body.sidebar-collapsed .nav-link .badge,
        body.sidebar-collapsed .user-info { display: none; }
        body.sidebar-collapsed .nav-link { justify-content: center; padding: 12px; }
        body.sidebar-collapsed .user-card { justify-content: center; }
        body.sidebar-collapsed .main-header { left: var(--sidebar-collapsed); }
        /* body.sidebar-collapsed .main-content { margin-left: var(--sidebar-collapsed); } */

        /* MOBILE */
        @media (max-width: 992px) {
            .search-box, .header-user-info { display: none; }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            body.sidebar-mobile-open .sidebar { transform: translateX(0); }
            .main-header { left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
     {{-- @stack('scripts') --}}
    @stack('styles')
</head>

<body>
    {{-- SIDEBAR - Must come before header in DOM --}}
    {{-- @if(Str::startsWith(Route::currentRouteName(), 'admin.') && Route::currentRouteName() !== 'admin.login')
        @include('partials.admin-sidebar')
    @elseif(Route::currentRouteName() !== 'login')
        @include('partials.sidebar')
    @endif --}}
    {{-- HEADER --}}
    @include('partials.header')

    <div class="main-content">
        @yield('content')
    </div>

    @include('partials.footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                document.body.classList.toggle('sidebar-mobile-open');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
            }
        }
        
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>