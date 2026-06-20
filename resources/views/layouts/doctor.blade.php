<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VitaGuard - Panel Dokter')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f5f9;
        }

        .dr-sidebar {
            width: 240px;
            min-height: 100vh;
            background: #0a3448;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .dr-brand {
            padding: 1.4rem 1.25rem 1.1rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .dr-brand-title {
            color: #fff;
            font-weight: 800;
            font-size: 1.1rem;
            margin: 0;
        }

        .dr-brand-sub {
            color: rgba(255,255,255,0.4);
            font-size: 0.72rem;
            margin: 0;
        }

        .dr-nav { padding: 1rem 0; flex: 1; }

        .dr-nav-label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.3);
            padding: 0.4rem 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .dr-nav-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.72rem 1.25rem;
            color: rgba(255,255,255,0.68);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .dr-nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.06);
        }

        .dr-nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-left-color: #36d1d8;
        }

        .dr-nav-link i { width: 18px; text-align: center; font-size: 1rem; }

        .dr-user {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .dr-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: #0ea5a5;
            display: inline-flex;
            align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 0.88rem;
            flex-shrink: 0;
        }

        .dr-main {
            margin-left: 240px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .dr-topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .dr-topbar-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .dr-content { padding: 1.5rem; flex: 1; }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            border-radius: 12px 12px 0 0 !important;
            padding: 0.9rem 1.25rem;
        }

        .card-title { font-size: 0.95rem; font-weight: 700; margin: 0; }

        @media (max-width: 991.98px) {
            .dr-sidebar { display: none; }
            .dr-main { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="dr-sidebar">
    <div class="dr-brand">
        <p class="dr-brand-title">
            <i class="bi bi-heart-pulse-fill text-info me-2"></i>VitaGuard
        </p>
        <p class="dr-brand-sub">Panel Dokter</p>
    </div>

    <nav class="dr-nav">
        <p class="dr-nav-label">Menu</p>
        <a href="{{ route('doctor.dashboard') }}"
           class="dr-nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
        <a href="{{ route('doctor.bookings.index') }}"
           class="dr-nav-link {{ request()->routeIs('doctor.bookings.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Booking Masuk
        </a>
    </nav>

    <div class="dr-user">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="dr-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <div style="min-width:0;">
                <p class="mb-0 text-white fw-semibold text-truncate" style="font-size:0.82rem;">{{ auth()->user()->name }}</p>
                <p class="mb-0" style="color:rgba(255,255,255,0.4);font-size:0.7rem;">Dokter</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm w-100 text-start"
                    style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.65);border-radius:8px;font-size:0.81rem;">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</div>

<div class="dr-main">
    <div class="dr-topbar">
        <h1 class="dr-topbar-title">@yield('page-title', 'Dashboard')</h1>
        <span class="text-muted small">{{ now()->format('d M Y') }}</span>
    </div>

    <div class="dr-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
