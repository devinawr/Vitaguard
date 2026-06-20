<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - VitaGuard</title>

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
        min-height: 560px;
    }

    /* Left panel */
    .auth-panel-left {
        width: 38%;
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

    .auth-steps {
        margin-top: 2rem;
        position: relative; z-index: 1;
        width: 100%;
    }

    .auth-step-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        color: rgba(255,255,255,0.88);
        font-size: 0.82rem;
        margin-bottom: 1rem;
    }

    .auth-step-num {
        width: 24px; height: 24px;
        background: rgba(255,255,255,0.22);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        flex-shrink: 0;
        color: #fff;
    }

    /* Right panel */
    .auth-panel-right {
        flex: 1;
        padding: 2.5rem 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow-y: auto;
    }

    .auth-title {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--vg-dark);
        margin-bottom: 0.3rem;
    }

    .auth-subtitle {
        color: #9e8a95;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.82rem;
        color: var(--vg-dark);
        margin-bottom: 0.35rem;
    }

    .form-control {
        border: 1.5px solid rgba(239,138,160,0.25);
        border-radius: 12px;
        padding: 0.65rem 1rem;
        font-family: 'Poppins', sans-serif;
        font-size: 0.86rem;
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
        margin: 1.25rem 0;
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
        <div class="auth-brand-tagline">Bergabung dan mulai perjalanan kesehatanmu</div>

        <div class="auth-steps">
            <div class="auth-step-item">
                <div class="auth-step-num">1</div>
                <span>Buat akun gratis dalam beberapa menit</span>
            </div>
            <div class="auth-step-item">
                <div class="auth-step-num">2</div>
                <span>Pilih dokter spesialis yang kamu butuhkan</span>
            </div>
            <div class="auth-step-item">
                <div class="auth-step-num">3</div>
                <span>Booking jadwal konsultasi dengan mudah</span>
            </div>
        </div>
    </div>

    {{-- Right panel --}}
    <div class="auth-panel-right">
        <div class="auth-title">Buat Akun Baru</div>
        <div class="auth-subtitle">Daftar sekarang, gratis!</div>

        @if($errors->any())
            <div class="alert alert-danger rounded-3 py-2 px-3 mb-3" style="font-size:0.85rem;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input id="name" type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           name="name" value="{{ old('name') }}"
                           placeholder="Nama lengkapmu"
                           required autocomplete="name" autofocus>
                </div>
                @error('name')
                    <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           required autocomplete="email">
                </div>
                @error('email')
                    <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" placeholder="Min. 8 karakter"
                               required autocomplete="new-password">
                    </div>
                    @error('password')
                        <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input id="password-confirm" type="password"
                               class="form-control"
                               name="password_confirmation"
                               placeholder="Ulangi password"
                               required autocomplete="new-password">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-auth mt-1">
                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
            </button>
        </form>

        <div class="auth-divider">atau</div>

        <p class="text-center mb-0" style="font-size:0.87rem;color:#9e8a95;">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="auth-link ms-1">Masuk di sini</a>
        </p>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
