<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'VitaGuard')</title>

    <!-- Font: Poppins only -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    :root {
        --vg-primary: #ef8aa0;
        --vg-primary-dark: #e06483;
        --vg-accent: #fdeef1;
        --vg-dark: #3a2230;
        --vg-border: rgba(239, 138, 160, 0.16);
        --vg-shadow: 0 4px 18px rgba(58, 34, 48, 0.08);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--vg-accent);
        color: var(--vg-dark);
        padding-top: 80px;
    }

    a {
        text-decoration: none;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
    }

    .text-vg-primary {
        color: var(--vg-primary) !important;
    }

    .bg-vg-primary {
        background-color: var(--vg-primary) !important;
    }

    .btn-vg-primary {
        background: linear-gradient(135deg, var(--vg-primary), var(--vg-primary-dark));
        border: none;
        color: #fff;
        border-radius: 999px;
        padding: 0.65rem 1.15rem;
        font-weight: 600;
        transition: all 0.25s ease;
    }

    .btn-vg-primary:hover {
        background: linear-gradient(135deg, var(--vg-primary-dark), #d85a7b);
        color: #fff;
    }

    .btn-outline-vg-primary {
        border: 1.5px solid rgba(239, 138, 160, 0.6);
        color: var(--vg-primary);
        background: #fff;
        border-radius: 999px;
        padding: 0.65rem 1.15rem;
        font-weight: 600;
        transition: all 0.25s ease;
    }

    .btn-outline-vg-primary:hover {
        background-color: var(--vg-primary);
        border-color: var(--vg-primary);
        color: #fff;
    }

    /* HEADER FULL WIDTH */
    .vg-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 9999;
        background: #fff;
        border-bottom: 1px solid var(--vg-border);
        box-shadow: var(--vg-shadow);
    }

    .vg-navbar {
        background: #fff;
        padding: 0.85rem 0;
        border-radius: 0;
    }

    .vg-brand {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--vg-primary) !important;
        letter-spacing: 0.2px;
    }

    .vg-brand-badge {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fff1f4;
        color: var(--vg-primary);
    }

    .vg-nav-link {
        position: relative;
        color: var(--vg-dark) !important;
        font-weight: 500;
        padding: 0.75rem 1rem !important;
        border-radius: 999px;
        transition: all 0.25s ease;
    }

    .vg-nav-link:hover {
        color: var(--vg-primary) !important;
        background: rgba(239, 138, 160, 0.08);
    }

    .vg-nav-link.active {
        color: var(--vg-primary) !important;
        background: #fff1f4;
        font-weight: 700;
    }

    .vg-nav-link.active::after {
        content: "";
        position: absolute;
        left: 50%;
        bottom: 6px;
        transform: translateX(-50%);
        width: 18px;
        height: 3px;
        border-radius: 999px;
        background: var(--vg-primary);
    }

    .vg-toggler {
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #fff1f4;
    }

    .vg-toggler:focus {
        box-shadow: 0 0 0 0.2rem rgba(239, 138, 160, 0.18);
    }

    .vg-auth-group {
        gap: 0.6rem;
    }

    .vg-user-btn {
        border: none;
        background: #fff1f4;
        color: var(--vg-dark);
        border-radius: 999px;
        padding: 0.6rem 0.95rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
    }

    .vg-user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--vg-primary), var(--vg-primary-dark));
        color: #fff;
        font-size: 0.95rem;
    }

    .vg-dropdown {
        border: 1px solid rgba(239, 138, 160, 0.14);
        border-radius: 14px;
        padding: 0.5rem;
        box-shadow: 0 18px 40px rgba(58, 34, 48, 0.10);
        background: #fff;
        z-index: 10000;
    }

    .vg-dropdown .dropdown-item {
        border-radius: 10px;
        padding: 0.7rem 0.9rem;
        font-weight: 500;
        color: var(--vg-dark);
        transition: all 0.2s ease;
    }

    .vg-dropdown .dropdown-item:hover {
        background: #fff1f4;
        color: var(--vg-primary);
    }

    .vg-dropdown .dropdown-item.text-danger:hover {
        background: #fff0f0;
        color: #dc3545 !important;
    }

    @media (max-width: 991.98px) {
        body {
            padding-top: 84px;
        }

        .vg-navbar .navbar-collapse {
            margin-top: 0.8rem;
            padding-top: 1rem;
            padding-bottom: 0.5rem;
            border-top: 1px solid rgba(239, 138, 160, 0.12);
            background: #fff;
        }

        .vg-navbar .navbar-nav {
            gap: 0.35rem !important;
        }

        .vg-nav-link {
            padding: 0.85rem 1rem !important;
        }

        .vg-auth-group {
            margin-top: 1rem;
            width: 100%;
        }

        .vg-auth-group .btn,
        .vg-auth-group .dropdown,
        .vg-auth-group .vg-user-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

    @stack('styles')
</head>

<body>

    {{-- NAVBAR --}}
    @include('partials.member.navbar')

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>