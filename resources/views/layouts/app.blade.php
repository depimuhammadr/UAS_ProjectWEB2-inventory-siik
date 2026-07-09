<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - StockFlow Inventory</title>
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: rgba(30, 41, 59, 0.7);
            --border-glow: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-indigo: #6366f1;
            --accent-purple: #a855f7;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #090d16 0%, #0f172a 50%, #1e1b4b 100%);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #090d16;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid var(--border-glow);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 24px;
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border-glow);
        }

        .sidebar-menu {
            padding: 20px 14px;
            list-style: none;
            margin: 0;
        }

        .sidebar-item {
            margin-bottom: 8px;
        }

        .sidebar-link {
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover, .sidebar-item.active .sidebar-link {
            color: var(--text-main);
            background: rgba(99, 102, 241, 0.15);
            box-shadow: inset 0 0 0 1px rgba(99, 102, 241, 0.2);
        }

        .sidebar-link i {
            font-size: 1.25rem;
            transition: transform 0.2s ease;
        }

        .sidebar-link:hover i {
            transform: scale(1.1);
        }

        /* Main Content Styling */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Header / Navbar Styling */
        .top-navbar {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-glow);
            padding: 16px 30px;
            display: flex;
            align-items: center;
            justify-content: justify;
            z-index: 90;
        }

        .content-area {
            padding: 30px;
            flex-grow: 1;
        }

        /* Glassmorphism Cards */
        .glass-card {
            background: var(--bg-secondary);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glow);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(0,0,0,0) 70%);
            top: -20px;
            right: -20px;
            border-radius: 50%;
        }

        .role-badge {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(168, 85, 247, 0.2) 100%);
            border: 1px solid rgba(168, 85, 247, 0.3);
            color: #d8b4fe;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 30px;
        }

        .branch-badge {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 30px;
            font-weight: 600;
        }

        .dropdown-menu-dark {
            background: #0f172a;
            border: 1px solid var(--border-glow);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            border-radius: 12px;
        }

        .btn-glass {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-main);
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-glass:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
            border-color: rgba(255,255,255,0.2);
        }

        .btn-action {
            border-radius: 8px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        /* Table custom styling */
        .table-custom {
            color: var(--text-main);
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table-custom th {
            border: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 18px;
        }

        .table-custom tbody tr {
            background: rgba(30, 41, 59, 0.4);
            transition: all 0.2s ease;
        }

        .table-custom tbody tr:hover {
            background: rgba(30, 41, 59, 0.7);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .table-custom td {
            border: none;
            padding: 16px 18px;
            vertical-align: middle;
        }

        .table-custom tbody tr td:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .table-custom tbody tr td:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .form-label-custom {
            font-weight: 600;
            color: #cbd5e1;
            font-size: 0.875rem;
            margin-bottom: 8px;
        }

        .form-control-custom, .form-select-custom {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f8fafc;
            border-radius: 10px;
            padding: 10px 14px;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus, .form-select-custom:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: var(--accent-indigo);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            color: #f8fafc;
            outline: none;
        }

        /* Mobile Sidebar Button */
        .sidebar-toggle-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-main);
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: -260px;
            }
            .sidebar.active {
                left: 0;
            }
            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }
            .sidebar-toggle-btn {
                display: block;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-box-seam-fill text-indigo-400"></i> StockFlow
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="sidebar-link">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="sidebar-item {{ Request::routeIs('products.*') ? 'active' : '' }}">
                <a href="{{ route('products.index') }}" class="sidebar-link">
                    <i class="bi bi-box-fill"></i>
                    <span>Daftar Barang</span>
                </a>
            </li>

            <li class="sidebar-item {{ Request::routeIs('borrowings.*') ? 'active' : '' }}">
                <a href="{{ route('borrowings.index') }}" class="sidebar-link">
                    <i class="bi bi-arrow-down-up"></i>
                    <span>Peminjaman</span>
                </a>
            </li>

            @if(auth()->user()->isAdmin())
                <li class="sidebar-item {{ Request::routeIs('categories.*') ? 'active' : '' }}">
                    <a href="{{ route('categories.index') }}" class="sidebar-link">
                        <i class="bi bi-tags-fill"></i>
                        <span>Kategori</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->isSuperAdmin())
                <li class="sidebar-item {{ Request::routeIs('master.*') ? 'active' : '' }}">
                    <a href="{{ route('master.index') }}" class="sidebar-link">
                        <i class="bi bi-gear-fill"></i>
                        <span>Master Data</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper" id="main-wrapper">
        <!-- Top Navbar -->
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle-btn" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 fw-bold d-none d-sm-block">@yield('page_title', 'Overview')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-flex flex-column text-end d-none d-md-flex">
                    <span class="fw-semibold small text-light">{{ auth()->user()->name }}</span>
                    <div class="d-flex align-items-center gap-1 mt-1 justify-content-end">
                        <span class="role-badge">{{ auth()->user()->role }}</span>
                        @if(auth()->user()->branch)
                            <span class="branch-badge">{{ auth()->user()->branch->name }}</span>
                        @else
                            <span class="branch-badge bg-primary border-primary">Super Admin</span>
                        @endif
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn btn-glass d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5"></i>
                        <i class="bi bi-chevron-down small"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                        <li><h6 class="dropdown-header text-muted">Aktivitas Akun</h6></li>
                        <li><hr class="dropdown-divider border-white border-opacity-10"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                    <i class="bi bi-box-arrow-right"></i> Keluar Aplikasi
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 bg-success bg-opacity-10 text-success-emphasis rounded-4 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 bg-danger bg-opacity-10 text-danger-emphasis rounded-4 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 bg-danger bg-opacity-10 text-danger-emphasis rounded-4 mb-4" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle for Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('main-wrapper');

        if(sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
