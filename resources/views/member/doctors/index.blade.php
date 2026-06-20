@extends('layouts.member')

@section('title', 'Cari Dokter - VitaGuard')

@push('styles')
<style>
    .page-hero {
        padding-top: 3.5rem;
        padding-bottom: 2.5rem;
        background:
            radial-gradient(circle at top left, rgba(239,138,160,0.10), transparent 30%),
            linear-gradient(180deg, #fff 0%, #fff7f9 100%);
        border-bottom: 1px solid rgba(239,138,160,0.10);
    }

    .page-hero-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.55rem 1rem; border-radius: 999px;
        background: #fff0f4; color: var(--vg-primary);
        font-size: 0.92rem; font-weight: 600; margin-bottom: 1rem;
    }

    .page-hero-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800; line-height: 1.2;
        color: var(--vg-dark); margin-bottom: 0.85rem;
    }

    .page-hero-subtitle {
        max-width: 580px; margin: 0 auto;
        color: #7f6a74; font-size: 1rem;
    }

    .hero-search-card {
        max-width: 760px; margin: 1.75rem auto 0;
        background: #fff;
        border: 1px solid rgba(239,138,160,0.14);
        border-radius: 24px;
        padding: 1rem 1.1rem;
        box-shadow: 0 12px 30px rgba(58,34,48,0.06);
    }

    .hero-search-inner {
        display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;
    }

    .hero-search-icon {
        width: 44px; height: 44px; flex: 0 0 44px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 14px; background: #fff1f4; color: var(--vg-primary); font-size: 1rem;
    }

    .hero-search-input {
        border: none !important; box-shadow: none !important;
        background: transparent !important; font-size: 0.95rem; color: var(--vg-dark);
        flex: 1; min-width: 140px;
    }

    .hero-search-input::placeholder { color: #b199a3; }

    .hero-search-divider {
        width: 1px; height: 28px; background: rgba(239,138,160,0.2); flex-shrink: 0;
    }

    .specialty-wrap {
        display: flex; align-items: center; gap: 0.4rem;
        background: #fff0f4;
        border: 1.5px solid rgba(239,138,160,0.35);
        border-radius: 12px;
        padding: 0 0.85rem;
        height: 44px;
        flex-shrink: 0;
        transition: border-color 0.2s;
        cursor: pointer;
    }
    .specialty-wrap:focus-within {
        border-color: var(--vg-primary);
        box-shadow: 0 0 0 3px rgba(239,138,160,0.15);
    }
    .specialty-wrap i {
        color: var(--vg-primary); font-size: 0.9rem; flex-shrink: 0; pointer-events: none;
    }
    .specialty-wrap select {
        border: none !important; box-shadow: none !important;
        background: transparent !important;
        font-size: 0.88rem; font-weight: 600;
        color: var(--vg-dark);
        cursor: pointer; min-width: 130px;
        padding: 0; outline: none;
    }

    .hero-search-btn {
        background: linear-gradient(135deg, var(--vg-primary), var(--vg-primary-dark));
        border: none; color: #fff; border-radius: 14px;
        padding: 0.6rem 1.3rem; font-weight: 700; font-size: 0.9rem;
        white-space: nowrap; transition: all 0.2s;
        flex-shrink: 0;
    }
    .hero-search-btn:hover {
        background: linear-gradient(135deg, var(--vg-primary-dark), #c94d6f);
        color: #fff; transform: translateY(-1px);
    }

    .doc-card {
        height: 100%; border: none; border-radius: 24px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(58,34,48,0.07);
        transition: all 0.25s ease; overflow: hidden;
        display: flex; flex-direction: column;
    }
    .doc-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(239,138,160,0.16);
    }
    .doc-card-body {
        padding: 1.5rem; display: flex; flex-direction: column; flex: 1;
    }
    .doc-avatar {
        width: 64px; height: 64px; border-radius: 50%; object-fit: cover;
        border: 2px solid #fdeef1; flex-shrink: 0;
    }
    .doc-avatar-placeholder {
        width: 64px; height: 64px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, #ef8aa0, #e06483);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.5rem; font-weight: 700;
    }

    @media (max-width: 575.98px) {
        .page-hero { padding-top: 2.5rem; padding-bottom: 2rem; }
        .hero-search-divider, .hero-search-icon { display: none; }
        .hero-search-select { min-width: 100%; }
        .hero-search-inner { gap: 0.5rem; }
    }
</style>
@endpush

@section('content')

<section class="page-hero">
    <div class="container text-center">
        <div class="page-hero-badge">
            <i class="bi bi-person-heart"></i>
            <span>Direktori dokter terverifikasi</span>
        </div>
        <h1 class="page-hero-title">Cari Dokter</h1>
        <p class="page-hero-subtitle">
            Temukan dokter spesialis terpercaya sesuai kebutuhanmu dan langsung booking jadwal konsultasi.
        </p>

        <div class="hero-search-card">
            <form method="GET">
                <div class="hero-search-inner">
                    <div class="hero-search-icon"><i class="bi bi-search"></i></div>
                    <input type="text" name="search" class="form-control hero-search-input"
                           placeholder="Cari nama dokter atau spesialis..."
                           value="{{ request('search') }}">
                    <div class="hero-search-divider"></div>
                    <div class="specialty-wrap">
                        <i class="bi bi-funnel-fill"></i>
                        <select name="specialty">
                            <option value="">Semua Spesialis</option>
                            @foreach($specialties as $sp)
                                <option value="{{ $sp }}" {{ request('specialty') == $sp ? 'selected' : '' }}>
                                    {{ $sp }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="hero-search-btn">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                    @if(request('search') || request('specialty'))
                        <a href="{{ route('member.doctors.index') }}"
                           class="btn btn-outline-secondary rounded-pill"
                           style="white-space:nowrap;font-size:0.88rem;">
                            <i class="bi bi-x-circle me-1"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>

<div class="container py-5">

    @if(request('search') || request('specialty'))
        <p class="text-muted mb-4 small">
            Menampilkan <strong>{{ $doctors->total() }}</strong> dokter
            @if(request('search')) untuk "<strong>{{ request('search') }}</strong>"@endif
            @if(request('specialty')) spesialis <strong>{{ request('specialty') }}</strong>@endif
        </p>
    @endif

    <div class="row g-4">
        @forelse($doctors as $doctor)
            <div class="col-md-6 col-lg-4">
                <div class="doc-card">
                    <div class="doc-card-body">
                        {{-- Photo + Name --}}
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if($doctor->photo)
                                <img src="{{ Storage::url($doctor->photo) }}"
                                     alt="{{ $doctor->user->name }}"
                                     class="doc-avatar">
                            @else
                                <div class="doc-avatar-placeholder">
                                    {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="overflow-hidden">
                                <h6 class="fw-bold mb-0 text-truncate" style="color:#2d2230;">
                                    {{ $doctor->user->name }}
                                </h6>
                                <small class="text-vg-primary fw-semibold">{{ $doctor->specialty }}</small>
                            </div>
                        </div>

                        {{-- Badges --}}
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($doctor->experience_years)
                                <span class="badge rounded-pill" style="background:#fdeef1;color:#e06483;font-weight:600;">
                                    <i class="bi bi-briefcase me-1"></i>{{ $doctor->experience_years }} tahun
                                </span>
                            @endif
                            @if($doctor->rating)
                                <span class="badge rounded-pill" style="background:#fff8e1;color:#f59e0b;font-weight:600;">
                                    <i class="bi bi-star-fill me-1"></i>{{ number_format($doctor->rating, 1) }}
                                </span>
                            @endif
                        </div>

                        @if($doctor->bio)
                            <p class="text-muted small mb-3" style="line-height:1.65;">
                                {{ \Illuminate\Support\Str::limit($doctor->bio, 80) }}
                            </p>
                        @else
                            <div class="mb-3"></div>
                        @endif

                        {{-- Button always at bottom --}}
                        <a href="{{ route('member.doctors.show', $doctor) }}"
                           class="btn btn-vg-primary btn-sm w-100 rounded-pill mt-auto">
                            Lihat Profil & Jadwal
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-search" style="font-size:3rem;color:#ef8aa0;"></i>
                <h5 class="mt-3 fw-bold" style="color:#2d2230;">Dokter tidak ditemukan</h5>
                <p class="text-muted">Coba ubah kata kunci atau filter pencarian</p>
                <a href="{{ route('member.doctors.index') }}" class="btn btn-outline-secondary rounded-pill">
                    Lihat Semua Dokter
                </a>
            </div>
        @endforelse
    </div>

    @if($doctors->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $doctors->links('pagination::bootstrap-4') }}
        </div>
    @endif

</div>
@endsection
