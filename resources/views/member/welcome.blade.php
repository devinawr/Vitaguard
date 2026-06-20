@extends('layouts.member')

@section('title', 'VitaGuard - Kesehatan Digital')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

<style>
    .vg-home {
        background:
            radial-gradient(circle at top left, rgba(255, 214, 230, 0.45), transparent 30%),
            linear-gradient(180deg, #fff9fb 0%, #ffffff 45%, #fff8fb 100%);
    }

    .vg-hero-area { padding-top: 24px; padding-bottom: 10px; }
    .vg-swiper-shell { max-width: 1220px; margin: 0 auto; padding: 0 12px; }

    .vg-swiper {
        border-radius: 30px; overflow: hidden;
        box-shadow: 0 20px 50px rgba(219,120,158,0.14);
        border: 1px solid rgba(255,255,255,0.7);
    }

    .vg-slide {
        min-height: 440px; position: relative; display: flex; align-items: center;
        background-size: cover; background-repeat: no-repeat; background-position: center;
    }

    .vg-slide::before {
        content: ""; position: absolute; inset: 0;
        background: linear-gradient(90deg, rgba(53,33,44,0.74) 0%, rgba(53,33,44,0.46) 45%, rgba(53,33,44,0.14) 100%);
    }

    .vg-slide .container { position: relative; z-index: 2; padding-left: 70px; padding-right: 70px; }
    .vg-slide-content { max-width: 560px; color: #fff; padding: 28px 20px; }

    .vg-badge {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 9px 16px; border-radius: 999px;
        background: rgba(255,255,255,0.18); backdrop-filter: blur(10px);
        font-size: 0.86rem; font-weight: 700; margin-bottom: 16px;
    }

    .vg-slide-title { font-size: 2.3rem; line-height: 1.2; font-weight: 800; margin-bottom: 12px; }
    .vg-slide-text { font-size: 1rem; line-height: 1.75; color: rgba(255,255,255,0.92); margin-bottom: 22px; }

    .vg-btn-light {
        background: #fff; color: #d9608c; border: none; border-radius: 999px;
        padding: 12px 22px; font-weight: 700;
        box-shadow: 0 10px 25px rgba(0,0,0,0.10); transition: all 0.25s ease;
    }
    .vg-btn-light:hover {
        background: linear-gradient(135deg, #e878a1, #d9608c); color: #fff;
        transform: translateY(-2px); box-shadow: 0 14px 30px rgba(217,96,140,0.28);
    }

    .btn-vg-primary {
        background: linear-gradient(135deg, #f08bb0, #d9608c); color: #fff; border: none;
        box-shadow: 0 10px 24px rgba(217,96,140,0.18); transition: all 0.25s ease;
    }
    .btn-vg-primary:hover {
        background: linear-gradient(135deg, #c94f7b, #b93f6c); color: #fff;
        transform: translateY(-2px); box-shadow: 0 14px 30px rgba(185,63,108,0.24);
    }

    .vg-stats-strip {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 8px 28px rgba(219,120,158,0.09);
        padding: 1.5rem 2rem;
    }
    .vg-stat-item { text-align: center; }
    .vg-stat-num { font-size: 2rem; font-weight: 800; color: #d9608c; line-height: 1; }
    .vg-stat-label { font-size: 0.82rem; color: #9e8a95; font-weight: 500; margin-top: 4px; }

    .vg-section-title { font-size: 1.9rem; font-weight: 800; color: #2d2230; }
    .vg-section-text { color: #7a6d76; }

    .vg-doc-card {
        border: none; border-radius: 24px; background: #fff;
        box-shadow: 0 8px 28px rgba(219,120,158,0.09);
        transition: all 0.25s ease; overflow: hidden;
    }
    .vg-doc-card:hover { transform: translateY(-5px); box-shadow: 0 18px 38px rgba(219,120,158,0.16); }
    .vg-doc-avatar {
        width: 72px; height: 72px; border-radius: 50%; object-fit: cover;
        border: 3px solid #fdeef1;
    }
    .vg-doc-avatar-placeholder {
        width: 72px; height: 72px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, #ef8aa0, #e06483);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.6rem; font-weight: 700;
    }
    .vg-specialty-badge {
        display: inline-block; padding: 4px 12px; border-radius: 999px;
        background: #fdeef1; color: #d9608c; font-size: 0.78rem; font-weight: 600;
    }

    .vg-step-card {
        border: none; border-radius: 20px; background: #fff;
        box-shadow: 0 6px 22px rgba(219,120,158,0.08); padding: 1.75rem;
        text-align: center; height: 100%;
    }
    .vg-step-icon {
        width: 60px; height: 60px; border-radius: 18px; margin: 0 auto 1rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; color: #fff;
        background: linear-gradient(135deg, #f6a8c3, #e878a1);
        box-shadow: 0 8px 20px rgba(232,120,161,0.22);
    }
    .vg-step-num {
        width: 28px; height: 28px; border-radius: 50%; background: #fdeef1;
        color: #d9608c; font-weight: 800; font-size: 0.85rem;
        display: flex; align-items: center; justify-content: center;
        margin: 0.75rem auto 0;
    }

    .vg-cta-banner {
        border-radius: 28px; overflow: hidden;
        background: linear-gradient(135deg, #ef8aa0 0%, #d9608c 55%, #c04d78 100%);
        box-shadow: 0 16px 40px rgba(217,96,140,0.22);
        padding: 3rem 2.5rem;
        position: relative;
    }
    .vg-cta-banner::before {
        content: ''; position: absolute;
        width: 300px; height: 300px; border-radius: 50%;
        background: rgba(255,255,255,0.07);
        top: -80px; right: -60px;
    }

    .vg-article-card {
        border: none; border-radius: 24px; overflow: hidden; background: #fff;
        box-shadow: 0 10px 28px rgba(231,126,163,0.09); transition: all 0.25s ease;
    }
    .vg-article-card:hover { transform: translateY(-6px); box-shadow: 0 20px 38px rgba(231,126,163,0.14); }
    .vg-article-img { width: 100%; height: 200px; object-fit: cover; }
    .vg-article-body { padding: 20px; }
    .vg-article-title { font-size: 1rem; font-weight: 700; color: #2d2230; margin-bottom: 8px; min-height: 48px; }
    .vg-article-text { color: #7a6d76; font-size: 0.9rem; line-height: 1.7; }

    .swiper-pagination { bottom: 18px !important; }
    .swiper-pagination-bullet { width: 10px; height: 10px; background: rgba(255,255,255,0.72); opacity: 1; transition: all 0.25s; }
    .swiper-pagination-bullet-active { width: 28px; border-radius: 999px; background: #fff; }
    .swiper-button-next, .swiper-button-prev {
        width: 42px; height: 42px; border-radius: 50%; color: #fff;
        background: rgba(255,255,255,0.14); backdrop-filter: blur(8px); transition: all 0.25s;
    }
    .swiper-button-next:hover, .swiper-button-prev:hover { background: rgba(255,255,255,0.28); }
    .swiper-button-next::after, .swiper-button-prev::after { font-size: 15px; font-weight: 800; }

    @media (max-width: 991.98px) {
        .vg-slide { min-height: 360px; }
        .vg-slide-title { font-size: 1.85rem; }
        .vg-slide .container { padding-left: 45px; padding-right: 45px; }
        .vg-slide-content { padding: 24px 16px; }
    }
    @media (max-width: 575.98px) {
        .vg-hero-area { padding-top: 18px; }
        .vg-slide { min-height: 310px; }
        .vg-slide-title { font-size: 1.4rem; }
        .vg-slide-text { font-size: 0.9rem; }
        .vg-slide .container { padding-left: 24px; padding-right: 24px; }
        .vg-cta-banner { padding: 2rem 1.5rem; }
    }
</style>
@endpush

@section('content')
<div class="vg-home">

    <section class="vg-hero-area">
        <div class="vg-swiper-shell">
            <div class="swiper homeSwiper vg-swiper">
                <div class="swiper-wrapper">

                    <div class="swiper-slide vg-slide"
                         style="background-image: url('https://images.unsplash.com/photo-1559757148-5c350d0d3c56?auto=format&fit=crop&w=1600&q=80');">
                        <div class="container">
                            <div class="vg-slide-content">
                                <span class="vg-badge"><i class="bi bi-search-heart"></i> Direktori Dokter</span>
                                <h1 class="vg-slide-title">Temukan dokter spesialis terpercaya sesuai kebutuhanmu</h1>
                                <p class="vg-slide-text">Pilih dari ratusan dokter berpengalaman di berbagai spesialisasi. Lihat profil, jadwal, dan langsung booking.</p>
                                <a href="{{ route('member.doctors.index') }}" class="btn vg-btn-light">
                                    <i class="bi bi-search me-1"></i> Cari Dokter
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide vg-slide"
                         style="background-image: url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=1600&q=80'); background-position: center 25%;">
                        <div class="container">
                            <div class="vg-slide-content">
                                <span class="vg-badge"><i class="bi bi-calendar2-check"></i> Booking Konsultasi</span>
                                <h2 class="vg-slide-title">Booking konsultasi dengan dokter pilihanmu dalam hitungan menit</h2>
                                <p class="vg-slide-text">Pilih jadwal yang tersedia, isi keluhan, dan booking selesai. Konfirmasi langsung dari admin.</p>
                                <a href="{{ route('member.bookings.index') }}" class="btn vg-btn-light">
                                    <i class="bi bi-calendar-plus me-1"></i> Booking Saya
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide vg-slide"
                         style="background-image: url('https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&w=1600&q=80'); background-position: center 30%;">
                        <div class="container">
                            <div class="vg-slide-content">
                                <span class="vg-badge"><i class="bi bi-journal-text"></i> Artikel Kesehatan</span>
                                <h2 class="vg-slide-title">Baca artikel kesehatan yang informatif dan mudah dipahami</h2>
                                <p class="vg-slide-text">Konten kesehatan pilihan yang ringkas, relevan, dan bisa langsung diterapkan dalam kehidupan sehari-hari.</p>
                                <a href="{{ route('member.articles.index') }}" class="btn vg-btn-light">
                                    <i class="bi bi-book me-1"></i> Baca Artikel
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="container" style="max-width:1000px;">
            <div class="vg-stats-strip">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="vg-stat-num">{{ $totalDoctors ?? 0 }}+</div>
                        <div class="vg-stat-label">Dokter Aktif</div>
                    </div>
                    <div class="col-4">
                        <div class="vg-stat-num">100%</div>
                        <div class="vg-stat-label">Terverifikasi</div>
                    </div>
                    <div class="col-4">
                        <div class="vg-stat-num">Mudah</div>
                        <div class="vg-stat-label">Proses Booking</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="text-center mb-4">
                <h3 class="vg-section-title">Cara Booking Konsultasi</h3>
                <p class="vg-section-text">3 langkah mudah untuk bertemu dokter pilihanmu</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="vg-step-card">
                        <div class="vg-step-icon"><i class="bi bi-search"></i></div>
                        <div class="vg-step-num">1</div>
                        <h5 class="fw-bold mt-3 mb-2" style="color:#2d2230;">Cari Dokter</h5>
                        <p class="text-muted mb-0" style="font-size:0.9rem;">Temukan dokter berdasarkan spesialisasi atau nama. Lihat profil dan jadwal tersedia.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="vg-step-card">
                        <div class="vg-step-icon"><i class="bi bi-calendar-check"></i></div>
                        <div class="vg-step-num">2</div>
                        <h5 class="fw-bold mt-3 mb-2" style="color:#2d2230;">Pilih Jadwal</h5>
                        <p class="text-muted mb-0" style="font-size:0.9rem;">Pilih tanggal dan waktu yang sesuai, lalu isi keluhan yang ingin dikonsultasikan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="vg-step-card">
                        <div class="vg-step-icon"><i class="bi bi-check2-circle"></i></div>
                        <div class="vg-step-num">3</div>
                        <h5 class="fw-bold mt-3 mb-2" style="color:#2d2230;">Tunggu Konfirmasi</h5>
                        <p class="text-muted mb-0" style="font-size:0.9rem;">Admin akan mengkonfirmasi booking. Doktermu siap menemuimu sesuai jadwal.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(isset($featuredDoctors) && $featuredDoctors->count())
    <section class="py-5" style="background:#fff8fb;">
        <div class="container">
            <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="vg-section-title mb-1">Dokter Unggulan</h3>
                    <p class="vg-section-text mb-0">Dokter berpengalaman dan terverifikasi di VitaGuard</p>
                </div>
                <a href="{{ route('member.doctors.index') }}" class="btn btn-vg-primary rounded-pill px-4">
                    Lihat Semua Dokter <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach($featuredDoctors as $doctor)
                    <div class="col-md-4">
                        <div class="vg-doc-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                @if($doctor->photo)
                                    <img src="{{ Storage::url($doctor->photo) }}"
                                         alt="{{ $doctor->user->name }}"
                                         class="vg-doc-avatar">
                                @else
                                    <div class="vg-doc-avatar-placeholder">
                                        {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="fw-bold mb-1" style="color:#2d2230;">{{ $doctor->user->name }}</h6>
                                    <span class="vg-specialty-badge">{{ $doctor->specialty }}</span>
                                </div>
                            </div>

                            <div class="d-flex gap-3 mb-3">
                                @if($doctor->experience_years)
                                    <small class="text-muted"><i class="bi bi-briefcase me-1 text-vg-primary"></i>{{ $doctor->experience_years }} tahun</small>
                                @endif
                                @if($doctor->rating)
                                    <small class="text-muted"><i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($doctor->rating, 1) }}</small>
                                @endif
                            </div>

                            @if($doctor->bio)
                                <p class="text-muted mb-3" style="font-size:0.85rem;line-height:1.6;">
                                    {{ \Illuminate\Support\Str::limit($doctor->bio, 80) }}
                                </p>
                            @endif

                            <a href="{{ route('member.doctors.show', $doctor) }}"
                               class="btn btn-vg-primary btn-sm w-100 rounded-pill">
                                Lihat Profil & Booking
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="py-5">
        <div class="container">
            <div class="vg-cta-banner text-white text-center">
                <h2 class="fw-800 mb-2" style="font-weight:800; position:relative;z-index:1;">
                    Siap berkonsultasi dengan dokter?
                </h2>
                <p class="mb-4" style="color:rgba(255,255,255,0.88);position:relative;z-index:1;">
                    Temukan dokter yang tepat dan booking jadwal konsultasimu sekarang.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap" style="position:relative;z-index:1;">
                    <a href="{{ route('member.doctors.index') }}" class="btn btn-light rounded-pill px-4 fw-bold" style="color:#d9608c;">
                        <i class="bi bi-search me-1"></i> Cari Dokter
                    </a>
                    <a href="{{ route('member.bookings.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold">
                        <i class="bi bi-calendar2-check me-1"></i> Booking Saya
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if(isset($latestArticles) && $latestArticles->count())
        <section class="py-5" id="artikel-terbaru">
            <div class="container">
                <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-2">
                    <div>
                        <h3 class="vg-section-title mb-1">Artikel Kesehatan Terbaru</h3>
                        <p class="vg-section-text mb-0">Informasi kesehatan yang ringkas dan mudah dipahami</p>
                    </div>
                    <a href="{{ route('member.articles.index') }}" class="btn btn-vg-primary rounded-pill px-4">
                        Semua Artikel <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-4">
                    @foreach($latestArticles as $article)
                        <div class="col-md-4">
                            <div class="card vg-article-card h-100">
                                @php
                                    $thumbnail = $article->thumbnail
                                        ? (\Illuminate\Support\Str::startsWith($article->thumbnail, ['http://', 'https://'])
                                            ? $article->thumbnail
                                            : asset('storage/' . $article->thumbnail))
                                        : 'https://placehold.co/600x400?text=VitaGuard';
                                @endphp
                                <img src="{{ $thumbnail }}" alt="{{ $article->title }}" class="vg-article-img">
                                <div class="vg-article-body d-flex flex-column">
                                    <h5 class="vg-article-title">{{ $article->title }}</h5>
                                    <p class="vg-article-text">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 90) }}
                                    </p>
                                    <div class="mt-auto pt-2">
                                        <a href="{{ route('member.articles.show', $article) }}"
                                           class="btn btn-sm btn-vg-primary rounded-pill px-3">
                                            Baca Artikel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    new Swiper('.homeSwiper', {
        loop: true, speed: 850, spaceBetween: 0,
        autoplay: { delay: 4500, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    });
</script>
@endpush
