<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - VitaGuard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    :root {
        --vg-primary: #ef8aa0;
        --vg-primary-dark: #e06483;
        --vg-accent: #fdeef1;
        --vg-dark: #3a2230;
    }

    * { box-sizing: border-box; }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--vg-accent);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }

    .auth-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 12px 40px rgba(58,34,48,0.10);
        overflow: hidden;
        width: 100%;
        max-width: 900px;
        display: flex;
        min-height: 520px;
    }

    /* Left panel */
    .auth-panel-left {
        width: 42%;
        background: linear-gradient(150deg, #ef8aa0 0%, #e06483 60%, #c94d6f 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .auth-panel-left::before {
        content: '';
        position: absolute;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        top: -60px; left: -60px;
    }

    .auth-panel-left::after {
        content: '';
        position: absolute;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
        bottom: -40px; right: -40px;
    }

    .auth-brand-icon {
        width: 70px; height: 70px;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem;
        color: #fff;
        margin-bottom: 1.25rem;
        position: relative; z-index: 1;
    }

    .auth-brand-name {
        font-size: 2rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: 0.3px;
        position: relative; z-index: 1;
        margin-bottom: 0.5rem;
    }

    .auth-brand-tagline {
        color: rgba(255,255,255,0.82);
        font-size: 0.88rem;
        text-align: center;
        line-height: 1.6;
        position: relative; z-index: 1;
    }

    .auth-features {
        margin-top: 2rem;
        position: relative; z-index: 1;
        width: 100%;
    }

    .auth-feature-item {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        color: rgba(255,255,255,0.88);
        font-size: 0.82rem;
        margin-bottom: 0.65rem;
    }

    .auth-feature-item i {
        width: 26px; height: 26px;
        background: rgba(255,255,255,0.18);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    /* Right panel */
    .auth-panel-right {
        flex: 1;
        padding: 3rem 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .auth-title {
        font-size: 1.55rem;
        font-weight: 700;
        color: var(--vg-dark);
        margin-bottom: 0.3rem;
    }

    .auth-subtitle {
        color: #9e8a95;
        font-size: 0.88rem;
        margin-bottom: 2rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--vg-dark);
        margin-bottom: 0.4rem;
    }

    .form-control {
        border: 1.5px solid rgba(239,138,160,0.25);
        border-radius: 12px;
        padding: 0.7rem 1rem;
        font-family: 'Poppins', sans-serif;
        font-size: 0.88rem;
        transition: all 0.2s;
        color: var(--vg-dark);
    }

    .form-control:focus {
        border-color: var(--vg-primary);
        box-shadow: 0 0 0 3px rgba(239,138,160,0.15);
    }

    .input-group-text {
        background: #fdf6f8;
        border: 1.5px solid rgba(239,138,160,0.25);
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: var(--vg-primary);
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }

    .input-group .form-control:focus {
        border-left: none;
    }

    .input-group:focus-within .input-group-text {
        border-color: var(--vg-primary);
    }

    .btn-auth {
        background: linear-gradient(135deg, var(--vg-primary), var(--vg-primary-dark));
        border: none;
        color: #fff;
        border-radius: 12px;
        padding: 0.78rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        width: 100%;
        transition: all 0.25s;
        letter-spacing: 0.2px;
    }

    .btn-auth:hover {
        background: linear-gradient(135deg, var(--vg-primary-dark), #c94d6f);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(224,100,131,0.35);
    }

    .auth-divider {
        display: flex; align-items: center; gap: 0.75rem;
        margin: 1.5rem 0;
        color: #c8b8c0;
        font-size: 0.82rem;
    }

    .auth-divider::before, .auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(239,138,160,0.2);
    }

    .auth-link {
        color: var(--vg-primary-dark);
        font-weight: 600;
        text-decoration: none;
    }

    .auth-link:hover {
        color: #c94d6f;
        text-decoration: underline;
    }

    .form-check-input:checked {
        background-color: var(--vg-primary);
        border-color: var(--vg-primary);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(239,138,160,0.2);
    }

    @media (max-width: 700px) {
        body { padding: 1rem; }
        .auth-panel-left { display: none; }
        .auth-panel-right { padding: 2rem 1.5rem; }
        .auth-card { border-radius: 20px; }
    }
</style>
</head>
<body>

<div class="auth-card">

    {{-- Left panel --}}
    <div class="auth-panel-left">
        <div class="auth-brand-icon">
            <i class="bi bi-heart-pulse-fill"></i>
        </div>
        <div class="auth-brand-name">VitaGuard</div>
        <div class="auth-brand-tagline">Platform kesehatan terpercaya untuk konsultasi dokter terbaik</div>

        <div class="auth-features">
            <div class="auth-feature-item">
                <i class="bi bi-shield-check"></i>
                <span>Dokter terverifikasi & berpengalaman</span>
            </div>
            <div class="auth-feature-item">
                <i class="bi bi-calendar2-check"></i>
                <span>Booking jadwal mudah & cepat</span>
            </div>
            <div class="auth-feature-item">
                <i class="bi bi-lock-fill"></i>
                <span>Data kesehatan aman & terlindungi</span>
            </div>
        </div>
    </div>

    {{-- Right panel --}}
    <div class="auth-panel-right">
        <div class="auth-title">Selamat Datang!</div>
        <div class="auth-subtitle">Masuk ke akun VitaGuard kamu</div>

        @if($errors->any())
            <div class="alert alert-danger rounded-3 py-2 px-3 mb-3" style="font-size:0.85rem;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           required autocomplete="email" autofocus>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="password" class="form-label mb-0">Password</label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-link" style="font-size:0.8rem;">
                            Lupa password?
                        </a>
                    @endif
                </div>
                <div class="input-group mt-1">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password" placeholder="Masukkan password"
                           required autocomplete="current-password">
                </div>
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember" style="font-size:0.85rem;color:#9e8a95;">
                        Ingat saya
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-auth">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <div class="auth-divider">atau</div>

        <p class="text-center mb-0" style="font-size:0.87rem;color:#9e8a95;">
            Belum punya akun?
            <a href="{{ route('register') }}" class="auth-link ms-1">Daftar sekarang</a>
        </p>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
