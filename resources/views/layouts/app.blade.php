<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: #f4f6f9;
            font-size: 15px;
        }

        .fas,
        .far,
        .fal {
            font-family: "Font Awesome 5 Free" !important;
        }

        .fab {
            font-family: "Font Awesome 5 Brands" !important;
        }

        /* ─── Sidebar ─── */
        .sidebar {
            height: 100vh;
            background: #2c3e50;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width .25s ease;
            display: flex;
            flex-direction: column;
            /* แถบเลื่อนบาง ๆ ซ่อนไว้ แสดงเฉพาะตอนชี้เมาส์ (ยังเลื่อนด้วยล้อเมาส์/นิ้วได้) */
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
        }

        .sidebar:hover {
            scrollbar-color: rgba(255, 255, 255, .25) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 5px;
        }

        .sidebar:hover::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .25);
        }

        .sidebar .brand {
            padding: 16px 16px;
            color: #fff;
            font-size: 1.05rem;
            font-weight: 700;
            border-bottom: 1px solid #3d5166;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            min-height: 56px;
        }

        .sidebar .brand-text {
            transition: opacity .2s;
        }

        .sidebar .nav-link {
            color: #b2bec3;
            padding: 8px 16px;
            font-size: .93rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background .2s, padding .25s;
            border-radius: 0;
            white-space: nowrap;
            position: relative;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #3d5166;
            color: #fff;
        }

        .sidebar .nav-link i {
            min-width: 20px;
            text-align: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .sidebar .nav-section {
            color: #636e72;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 12px 16px 3px;
            white-space: nowrap;
            transition: opacity .2s;
        }

        .sidebar .nav-links {
            flex: 1;
        }

        /* Toggle button */
        .sidebar-toggle {
            background: none;
            border: none;
            color: #b2bec3;
            font-size: 14px;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: all .15s;
            margin-left: auto;
            flex-shrink: 0;
        }

        .sidebar-toggle:hover {
            background: #3d5166;
            color: #fff;
        }

        .sidebar-toggle i {
            transition: transform .25s;
        }

        .sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        /* ─── Collapsed state ─── */
        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar.collapsed .brand-text,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .nav-section {
            opacity: 0;
            pointer-events: none;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed .brand {
            justify-content: center;
            padding: 16px 8px;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 8px 8px;
        }

        /* จอเตี้ย (โน้ตบุ๊ก 768px ลงมา) — บีบเมนูให้พอดีจอ */
        @media (max-height: 800px) {
            .sidebar .brand { min-height: 48px; padding: 10px 16px; }
            .sidebar .nav-link { padding: 6px 16px; }
            .sidebar.collapsed .nav-link { padding: 6px 8px; }
            .sidebar .nav-section { padding: 8px 16px 2px; font-size: .68rem; }
        }

        .sidebar.collapsed .sidebar-toggle {
            margin: 0 auto;
        }

        /* Tooltip on collapsed */
        .sidebar.collapsed .nav-link::after {
            content: attr(data-title);
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            background: #1e293b;
            color: #fff;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: .78rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity .15s;
            z-index: 200;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .3);
        }

        .sidebar.collapsed .nav-link:hover::after {
            opacity: 1;
        }

        /* ─── Main content ─── */
        .main-content {
            margin-left: 240px;
            min-height: 100vh;
            transition: margin-left .25s ease;
        }

        .sidebar.collapsed~.main-content {
            margin-left: 60px;
        }

        .topbar {
            background: #fff;
            padding: 12px 24px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .topbar-toggle {
            background: none;
            border: none;
            font-size: 18px;
            color: #64748b;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 6px;
            margin-right: 12px;
            display: none;
        }

        .topbar-toggle:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .page-body {
            padding: 24px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .08);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
        }

        .badge-role-superadmin {
            background: #e74c3c;
        }

        .badge-role-teacher {
            background: #2980b9;
        }

        .badge-role-student {
            background: #27ae60;
        }

        .alert {
            border-radius: 8px;
        }

        /* ─── Mobile ─── */
        @media (max-width: 768px) {
            .sidebar {
                width: 240px;
                left: -240px;
            }

            .sidebar.mobile-open {
                left: 0;
            }

            .sidebar.collapsed {
                width: 240px;
                left: -240px;
            }

            .sidebar.collapsed.mobile-open {
                left: 0;
                width: 240px;
            }

            .sidebar.collapsed .brand-text,
            .sidebar.collapsed .nav-link span,
            .sidebar.collapsed .nav-section {
                opacity: 1;
                pointer-events: auto;
                width: auto;
            }

            .sidebar.collapsed .brand {
                justify-content: flex-start;
                padding: 16px;
            }

            .sidebar.collapsed .nav-link {
                justify-content: flex-start;
                padding: 11px 16px;
            }

            .main-content,
            .sidebar.collapsed~.main-content {
                margin-left: 0;
            }

            .topbar-toggle {
                display: inline-block;
            }

            .sidebar-toggle {
                display: none;
            }

            .mobile-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .4);
                z-index: 99;
            }

            .mobile-overlay.show {
                display: block;
            }
        }

        @yield('styles')
    </style>
    @yield('head')
</head>

<body>
    @yield('body')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            var sb = document.querySelector('.sidebar');
            if (!sb) return;
            var key = 'sidebar_collapsed';
            var overlay = document.getElementById('mobileOverlay');

            if (localStorage.getItem(key) === '1' && window.innerWidth > 768) {
                sb.classList.add('collapsed');
            }

            document.addEventListener('click', function(e) {
                if (e.target.closest('.sidebar-toggle')) {
                    sb.classList.toggle('collapsed');
                    localStorage.setItem(key, sb.classList.contains('collapsed') ? '1' : '0');
                }
                if (e.target.closest('.topbar-toggle')) {
                    sb.classList.toggle('mobile-open');
                    if (overlay) overlay.classList.toggle('show', sb.classList.contains('mobile-open'));
                }
                if (e.target === overlay) {
                    sb.classList.remove('mobile-open');
                    overlay.classList.remove('show');
                }
            });
        })();
    </script>
    @yield('scripts')
</body>

</html>
