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

    .vg-hero-area {
        padding-top: 24px;
        padding-bottom: 10px;
    }

    .vg-swiper-shell {
        max-width: 1220px;
        margin: 0 auto;
        padding: 0 12px;
    }

    .vg-swiper {
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(219, 120, 158, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.7);
    }

    .vg-slide {
        min-height: 440px;
        position: relative;
        display: flex;
        align-items: center;
        background-size: 115%;
        background-repeat: no-repeat;
        background-position: center center;
    }

    .vg-slide::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(
            90deg,
            rgba(53, 33, 44, 0.72) 0%,
            rgba(53, 33, 44, 0.46) 42%,
            rgba(53, 33, 44, 0.14) 100%
        );
    }

    .vg-slide .container {
        position: relative;
        z-index: 2;
        padding-left: 70px;
        padding-right: 70px;
    }

    .vg-slide-content {
        max-width: 560px;
        color: #fff;
        padding: 28px 20px;
    }

    .vg-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        font-size: 0.86rem;
        font-weight: 700;
        margin-bottom: 16px;
        letter-spacing: 0.2px;
    }

    .vg-slide-title {
        font-size: 2.3rem;
        line-height: 1.2;
        font-weight: 800;
        margin-bottom: 12px;
    }

    .vg-slide-text {
        font-size: 1rem;
        line-height: 1.75;
        color: rgba(255, 255, 255, 0.92);
        margin-bottom: 22px;
    }

    .vg-btn-light {
        background: #ffffff;
        color: #d9608c;
        border: none;
        border-radius: 999px;
        padding: 12px 22px;
        font-weight: 700;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.10);
        transition: all 0.25s ease;
    }

    .vg-btn-light:hover {
        background: linear-gradient(135deg, #e878a1, #d9608c);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(217, 96, 140, 0.28);
    }

    .btn-vg-primary {
        background: linear-gradient(135deg, #f08bb0, #d9608c);
        color: #ffffff;
        border: none;
        box-shadow: 0 10px 24px rgba(217, 96, 140, 0.18);
        transition: all 0.25s ease;
    }

    .btn-vg-primary:hover {
        background: linear-gradient(135deg, #c94f7b, #b93f6c);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(185, 63, 108, 0.24);
    }

    .vg-welcome-wrap {
        padding: 26px 12px 0;
        max-width: 1220px;
        margin: 0 auto;
    }

    .vg-welcome-card {
        border: none;
        border-radius: 28px;
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        box-shadow: 0 16px 38px rgba(219, 120, 158, 0.10);
        overflow: hidden;
    }

    .vg-welcome-card .card-body {
        padding: 36px;
    }

    .vg-mini-badge {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 999px;
        background: #ffe7f0;
        color: #d9608c;
        font-size: 0.84rem;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .vg-welcome-title {
        font-size: 2rem;
        font-weight: 800;
        color: #2f2430;
        line-height: 1.28;
        margin-bottom: 12px;
    }

    .vg-welcome-text {
        color: #7f6c77;
        line-height: 1.85;
        font-size: 1rem;
        margin-bottom: 0;
    }

    .vg-feature-box {
        height: 100%;
        border-radius: 22px;
        padding: 22px;
        background: linear-gradient(180deg, #fff8fb 0%, #ffffff 100%);
        border: 1px solid #f7dce6;
        box-shadow: 0 10px 25px rgba(219, 120, 158, 0.06);
    }

    .vg-feature-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f6a8c3, #e878a1);
        color: #fff;
        font-size: 1.2rem;
        margin-bottom: 14px;
        box-shadow: 0 10px 20px rgba(232, 120, 161, 0.18);
    }

    .vg-section-title {
        font-size: 1.9rem;
        font-weight: 800;
        color: #2d2230;
    }

    .vg-section-text {
        color: #7a6d76;
    }

    .vg-article-card {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 14px 32px rgba(231, 126, 163, 0.09);
        transition: all 0.25s ease;
    }

    .vg-article-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 38px rgba(231, 126, 163, 0.14);
    }

    .vg-article-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .vg-article-body {
        padding: 22px;
    }

    .vg-article-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #2d2230;
        margin-bottom: 10px;
        min-height: 50px;
    }

    .vg-article-text {
        color: #7a6d76;
        font-size: 0.94rem;
        line-height: 1.7;
        min-height: 72px;
    }

    .swiper-pagination {
        bottom: 18px !important;
    }

    .swiper-pagination-bullet {
        width: 10px;
        height: 10px;
        background: rgba(255, 255, 255, 0.72);
        opacity: 1;
        transition: all 0.25s ease;
    }

    .swiper-pagination-bullet-active {
        width: 28px;
        border-radius: 999px;
        background: #ffffff;
    }

    .swiper-button-next,
    .swiper-button-prev {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.14);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        color: #fff;
        transition: all 0.25s ease;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: rgba(255, 255, 255, 0.28);
    }

    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-size: 15px;
        font-weight: 800;
    }

    @media (max-width: 991.98px) {
        .vg-slide {
            min-height: 360px;
        }

        .vg-slide-title {
            font-size: 1.85rem;
        }

        .vg-welcome-title {
            font-size: 1.65rem;
        }

        .vg-welcome-card .card-body {
            padding: 26px;
        }

        .vg-slide .container {
            padding-left: 45px;
            padding-right: 45px;
        }

        .vg-slide-content {
            padding: 24px 16px;
        }
    }

    @media (max-width: 575.98px) {
        .vg-hero-area {
            padding-top: 18px;
        }

        .vg-slide {
            min-height: 320px;
        }

        .vg-slide-title {
            font-size: 1.45rem;
        }

        .vg-slide-text {
            font-size: 0.92rem;
            line-height: 1.65;
        }

        .vg-badge {
            font-size: 0.78rem;
            padding: 8px 13px;
        }

        .vg-welcome-card .card-body {
            padding: 22px;
        }

        .vg-slide .container {
            padding-left: 24px;
            padding-right: 24px;
        }

        .vg-slide-content {
            padding: 20px 10px;
        }
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
                         style="background-image: url('https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=1600&q=80'); background-position: center center;">
                        <div class="container">
                            <div class="vg-slide-content">
                                <span class="vg-badge">
                                    <i class="bi bi-heart-pulse-fill"></i> Kesehatan Digital Premium
                                </span>
                                <h1 class="vg-slide-title">Temukan informasi kesehatan yang terasa lebih ringan dan nyaman dibaca</h1>
                                <p class="vg-slide-text">
                                    Artikel pilihan, tampilan lembut, dan pengalaman membaca yang lebih elegan untuk kebutuhan kesehatan harianmu.
                                </p>
                                <a href="{{ route('member.articles.index') }}" class="btn vg-btn-light">
                                    Jelajahi Artikel
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide vg-slide"
                         style="background-image: url('https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&w=1600&q=80'); background-position: center 30%;">
                        <div class="container">
                            <div class="vg-slide-content">
                                <span class="vg-badge">
                                    <i class="bi bi-cup-hot-fill"></i> Gaya Hidup Sehat
                                </span>
                                <h2 class="vg-slide-title">Mulai kebiasaan kecil yang berdampak besar untuk tubuhmu</h2>
                                <p class="vg-slide-text">
                                    Jelajahi topik nutrisi, tidur, perawatan tubuh, dan kesehatan sehari-hari dalam tampilan yang lebih aesthetic.
                                </p>
                                <a href="{{ route('member.articles.index') }}" class="btn vg-btn-light">
                                    Lihat Artikel
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide vg-slide"
                         style="background-image: url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=1600&q=80'); background-position: center 25%;">
                        <div class="container">
                            <div class="vg-slide-content">
                                <span class="vg-badge">
                                    <i class="bi bi-shield-check"></i> Informasi Terpercaya
                                </span>
                                <h2 class="vg-slide-title">Baca artikel kesehatan dengan nuansa yang lebih modern dan bersih</h2>
                                <p class="vg-slide-text">
                                    Desain yang rapi membantu pengguna fokus pada isi artikel tanpa terasa berat atau terlalu padat.
                                </p>
                                <a href="{{ route('member.articles.index') }}" class="btn vg-btn-light">
                                    Mulai Membaca
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

    <section class="pb-4">
        <div class="vg-welcome-wrap">
            <div class="card vg-welcome-card">
                <div class="card-body">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-7">
                            <span class="vg-mini-badge">Welcome to VitaGuard</span>
                            <h2 class="vg-welcome-title">Platform kesehatan digital yang lebih hangat, bersih, dan nyaman digunakan</h2>
                            <p class="vg-welcome-text">
                                VitaGuard hadir untuk membantu pengguna menemukan artikel kesehatan dengan cepat, jelas, dan tetap enak dilihat.
                                Desain ini dibuat lebih soft, modern, dan tidak bertumpuk agar tampilan terasa rapi dari atas sampai bawah.
                            </p>

                            <div class="d-flex flex-wrap gap-3 mt-4">
                                <a href="{{ route('member.articles.index') }}" class="btn btn-vg-primary px-4 py-2 rounded-pill">
                                    Jelajahi Artikel
                                </a>
                                <a href="#artikel-terbaru" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                    Artikel Terbaru
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="vg-feature-box">
                                        <div class="vg-feature-icon">
                                            <i class="bi bi-journal-text"></i>
                                        </div>
                                        <h5 class="fw-bold mb-2">Artikel Informatif</h5>
                                        <p class="text-muted mb-0">
                                            Konten kesehatan yang ringkas, mudah dibaca, dan relevan untuk kebutuhan harian.
                                        </p>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="vg-feature-box">
                                        <div class="vg-feature-icon">
                                            <i class="bi bi-stars"></i>
                                        </div>
                                        <h5 class="fw-bold mb-2">Tampilan Lebih Aesthetic</h5>
                                        <p class="text-muted mb-0">
                                            Layout yang lebih ringan, lembut, dan premium supaya pengunjung merasa nyaman saat menjelajah.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(isset($latestArticles) && $latestArticles->count())
        <section class="py-5" id="artikel-terbaru">
            <div class="container">
                <div class="text-center mb-5">
                    <h3 class="vg-section-title mb-2">Artikel Terbaru</h3>
                    <p class="vg-section-text mb-0">
                        Baca pilihan artikel kesehatan terbaru dengan tampilan yang lebih rapi dan nyaman.
                    </p>
                </div>

                <div class="row g-4">
                    @foreach($latestArticles as $article)
                        <div class="col-md-6 col-lg-4">
                            <div class="card vg-article-card h-100">
                                @php
                                    $thumbnail = $article->thumbnail
                                        ? (\Illuminate\Support\Str::startsWith($article->thumbnail, ['http://', 'https://'])
                                            ? $article->thumbnail
                                            : asset('storage/' . $article->thumbnail))
                                        : 'https://placehold.co/600x400?text=VitaGuard';
                                @endphp

                                <img
                                    src="{{ $thumbnail }}"
                                    alt="{{ $article->title }}"
                                    class="vg-article-img"
                                >

                                <div class="vg-article-body d-flex flex-column">
                                    <h5 class="vg-article-title">{{ $article->title }}</h5>
                                    <p class="vg-article-text">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 100) }}
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
        loop: true,
        speed: 850,
        spaceBetween: 0,
        autoplay: {
            delay: 4200,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
</script>
@endpush