<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>@yield('title', 'Admin | VitaGuard')</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
            --sidebar-active: #3498db;
        }
        
        body {
            background-color: #ecf0f1;
        }
        
        .wrapper {
            display: flex;
            height: 100vh;
        }
        
        .main-sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        
        .main-header {
            margin-left: 250px;
            background-color: white;
            border-bottom: 1px solid #ddd;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .content-wrapper {
            margin-left: 250px;
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        
        .brand-link {
            display: flex;
            align-items: center;
            padding: 1rem;
            text-decoration: none;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        
        .brand-text {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .sidebar-nav {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        
        .nav-section {
            padding: 1rem 0 0.5rem 0;
            font-size: 0.75rem;
            font-weight: bold;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            padding-left: 1rem;
            margin-top: 1rem;
        }
        
        .nav-item {
            margin: 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
            border-left-color: var(--sidebar-active);
        }
        
        .nav-link.active {
            background-color: var(--sidebar-hover);
            color: white;
            border-left-color: var(--sidebar-active);
        }
        
        .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .page-header {
            background-color: white;
            padding: 1.5rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
        }
        
        .breadcrumb-nav {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .breadcrumb-nav a {
            color: #3498db;
            text-decoration: none;
        }
        
        .breadcrumb-nav a:hover {
            text-decoration: underline;
        }
        
        .stat-card {
            border: none;
            border-radius: 8px;
            color: white;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .stat-card .card-body {
            padding: 1.5rem;
        }
        
        .stat-card .card-footer {
            background: rgba(0,0,0,0.1) !important;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding: 0.75rem 1.5rem;
        }
        
        .stat-card .card-footer a {
            display: block;
            text-decoration: none;
            color: white;
        }
        
        .stat-title {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }
        
        .bg-stat-blue {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }
        
        .bg-stat-green {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }
        
        .bg-stat-yellow {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }
        
        .bg-stat-red {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }
        
        .summary-box {
            background-color: white;
            border-left: 4px solid #3498db;
            padding: 1.5rem;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .summary-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .summary-text {
            color: #7f8c8d;
            margin: 0;
        }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Sidebar -->
    <aside class="main-sidebar">
        <a href="{{ auth()->user()->role === 'doctor' ? route('doctor.dashboard') : route('admin.dashboard') }}" class="brand-link">
            <div class="brand-icon">
                <i class="bi bi-heart-fill"></i>
            </div>
            <span class="brand-text"><strong>Vita</strong>Guard</span>
        </a>

        <nav class="sidebar-nav">
            @if(auth()->user()->role === 'doctor')
                {{-- Doctor sidebar --}}
                <li class="nav-item">
                    <a href="{{ route('doctor.dashboard') }}" class="nav-link @if(request()->routeIs('doctor.dashboard')) active @endif">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <div class="nav-section">Booking Pasien</div>

                <li class="nav-item">
                    <a href="{{ route('doctor.bookings.index') }}" class="nav-link @if(request()->routeIs('doctor.bookings.*')) active @endif">
                        <i class="bi bi-calendar-check"></i>
                        <span>Booking Masuk</span>
                    </a>
                </li>
            @else
                {{-- Admin sidebar --}}
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <div class="nav-section">Manajemen Data</div>

                <li class="nav-item">
                    <a href="{{ route('admin.members.index') }}" class="nav-link @if(request()->routeIs('admin.members*')) active @endif">
                        <i class="bi bi-people-fill"></i>
                        <span>Member</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.doctors.index') }}" class="nav-link @if(request()->routeIs('admin.doctors*')) active @endif">
                        <i class="bi bi-person-badge"></i>
                        <span>Dokter</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.bookings.index') }}" class="nav-link @if(request()->routeIs('admin.bookings*')) active @endif">
                        <i class="bi bi-receipt"></i>
                        <span>Booking Konsultasi</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.articles.index') }}" class="nav-link @if(request()->routeIs('admin.articles*')) active @endif">
                        <i class="bi bi-tag-fill"></i>
                        <span>Artikel Kesehatan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.consultations.index') }}" class="nav-link @if(request()->routeIs('admin.consultations*')) active @endif">
                        <i class="bi bi-chat-dots-fill"></i>
                        <span>Konsultasi</span>
                    </a>
                </li>

                <div class="nav-section">Pengaturan</div>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link @if(request()->routeIs('admin.users*')) active @endif">
                        <i class="bi bi-gear-fill"></i>
                        <span>Pengguna</span>
                    </a>
                </li>
            @endif
        </nav>
    </aside>

    <!-- Header -->
    <div style="display: flex; flex-direction: column; flex: 1;">
        <nav class="main-header">
            <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                <div style="font-size: 0.9rem; color: #7f8c8d;">
                    @if(auth()->user()->role === 'doctor')
                        <a href="{{ route('doctor.dashboard') }}" style="color: #3498db; text-decoration: none;">Dokter</a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" style="color: #3498db; text-decoration: none;">Admin</a>
                    @endif
                    <span style="margin: 0 0.5rem;">/</span>
                    <span>@yield('page-title', 'Dashboard')</span>
                </div>
                <div class="dropdown">
                    <a class="text-decoration-none text-dark dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> 
                        <span style="margin-left: 0.5rem;">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="content-wrapper">
            <div class="page-header">
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            </div>
            
            @yield('content')
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

@stack('scripts')
</body>
</html>