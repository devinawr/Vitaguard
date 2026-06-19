@extends('layouts.member')
@section('title', 'Artikel Kesehatan')

@push('styles')
<style>
    .article-hero {
        padding-top: 3.5rem;
        padding-bottom: 2.5rem;
        background:
            radial-gradient(circle at top left, rgba(239, 138, 160, 0.10), transparent 30%),
            linear-gradient(180deg, #fff 0%, #fff7f9 100%);
        border-bottom: 1px solid rgba(239, 138, 160, 0.10);
    }

    .article-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1rem;
        border-radius: 999px;
        background: #fff0f4;
        color: var(--vg-primary);
        font-size: 0.92rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .article-hero-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        line-height: 1.2;
        color: var(--vg-dark);
        margin-bottom: 0.85rem;
    }

    .article-hero-subtitle {
        max-width: 640px;
        margin: 0 auto;
        color: #7f6a74;
        font-size: 1rem;
    }

    .article-search-wrap {
        margin-top: 2rem;
    }

    .article-search-card {
        max-width: 720px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid rgba(239, 138, 160, 0.14);
        border-radius: 24px;
        padding: 0.9rem;
        box-shadow: 0 12px 30px rgba(58, 34, 48, 0.06);
    }

    .article-search-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .article-search-icon {
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: #fff1f4;
        color: var(--vg-primary);
        font-size: 1.1rem;
    }

    .article-search-input {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        font-size: 1rem;
        color: var(--vg-dark);
    }

    .article-search-input::placeholder {
        color: #b199a3;
    }

    .article-toolbar {
        margin-top: 2rem;
        margin-bottom: 1.5rem;
    }

    .article-toolbar-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--vg-dark);
        margin-bottom: 0.25rem;
    }

    .article-toolbar-subtitle {
        color: #8d7580;
        font-size: 0.95rem;
    }

    .article-card {
        height: 100%;
        background: #fff;
        border: 1px solid rgba(239, 138, 160, 0.12);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(58, 34, 48, 0.06);
        transition: all 0.28s ease;
    }

    .article-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(58, 34, 48, 0.10);
        border-color: rgba(239, 138, 160, 0.22);
    }

    .article-card-media {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16 / 10;
        background: #fff4f7;
    }

    .article-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .article-card:hover .article-card-media img {
        transform: scale(1.04);
    }

    .article-card-body {
        padding: 1.2rem 1.2rem 1.25rem;
    }

    .article-meta-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.9rem;
        flex-wrap: wrap;
    }

    .article-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: #fff1f4;
        color: var(--vg-primary);
        border-radius: 999px;
        padding: 0.45rem 0.85rem;
        font-size: 0.8rem;
        font-weight: 600;
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .article-date {
        font-size: 0.82rem;
        color: #9a818b;
        white-space: nowrap;
    }

    .article-title {
        font-size: 1.08rem;
        font-weight: 700;
        line-height: 1.45;
        color: var(--vg-dark);
        margin-bottom: 0.75rem;
        min-height: 3.1rem;
    }

    .article-excerpt {
        color: #806a74;
        font-size: 0.94rem;
        line-height: 1.75;
        margin-bottom: 1rem;
        min-height: 4.9rem;
    }

    .article-meta-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: auto;
    }

    .article-views {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        color: #927b85;
        font-size: 0.88rem;
        font-weight: 500;
    }

    .article-btn {
        border-radius: 999px !important;
        padding-inline: 1rem !important;
        font-weight: 600 !important;
    }

    .article-empty {
        background: #fff;
        border: 1px dashed rgba(239, 138, 160, 0.28);
        border-radius: 28px;
        padding: 3rem 1.5rem;
        text-align: center;
        box-shadow: 0 10px 25px rgba(58, 34, 48, 0.04);
    }

    .article-empty-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff1f4;
        color: var(--vg-primary);
        font-size: 1.8rem;
    }

    .article-pagination .pagination {
        justify-content: center;
        gap: 0.35rem;
    }

    .article-pagination .page-link {
        border: none;
        min-width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px !important;
        color: var(--vg-dark);
        background: #fff;
        box-shadow: 0 6px 18px rgba(58, 34, 48, 0.06);
    }

    .article-pagination .page-item.active .page-link {
        background: var(--vg-primary);
        color: #fff;
    }

    .article-pagination .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(239, 138, 160, 0.18);
    }

    @media (max-width: 991.98px) {
        .article-hero {
            padding-top: 2.5rem;
            padding-bottom: 2rem;
        }

        .article-search-card {
            border-radius: 20px;
            padding: 0.8rem;
        }

        .article-search-group {
            flex-wrap: wrap;
        }

        .article-search-icon {
            display: none;
        }

        .article-search-group .form-control,
        .article-search-group .btn {
            width: 100%;
        }

        .article-title,
        .article-excerpt {
            min-height: auto;
        }

        .article-meta-bottom {
            flex-direction: column;
            align-items: stretch;
        }

        .article-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<section class="article-hero">
    <div class="container text-center">
        <div class="article-hero-badge">
            <i class="bi bi-heart-pulse-fill"></i>
            <span>Artikel kesehatan terpercaya</span>
        </div>

        <h1 class="article-hero-title">Artikel Kesehatan</h1>
        <p class="article-hero-subtitle">
            Temukan informasi kesehatan yang ringan dibaca, informatif, dan nyaman untuk dijelajahi kapan saja.
        </p>

        <div class="article-search-wrap">
            <form method="GET">
                <div class="article-search-card">
                    <div class="article-search-group">
                        <div class="article-search-icon">
                            <i class="bi bi-search"></i>
                        </div>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control article-search-input"
                            placeholder="Cari artikel yang kamu butuhkan..."
                        >

                        <button class="btn btn-vg-primary px-4" type="submit">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="article-toolbar d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <div class="article-toolbar-title">
                    {{ $articles->total() }} artikel ditemukan
                </div>
                <div class="article-toolbar-subtitle">
                    @if(request('search'))
                        Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                    @else
                        Jelajahi artikel pilihan untuk menjaga kesehatanmu.
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($articles as $article)
                <div class="col-md-6 col-lg-4">
                    <article class="article-card">
                        <div class="article-card-media">
                            <img
                                src="{{ $article->thumbnail
                                    ? (Str::startsWith($article->thumbnail, ['http://', 'https://']) ? $article->thumbnail : asset('storage/' . $article->thumbnail))
                                    : 'https://placehold.co/800x500/fdeef1/ef8aa0?text=VitaGuard+Article' }}"
                                alt="{{ $article->title }}"
                                loading="lazy"
                            >
                        </div>

                        <div class="article-card-body d-flex flex-column">
                            <div class="article-meta-top">
                                <span class="article-badge">
                                    <i class="bi bi-person-circle"></i>
                                    {{ $article->author->name ?? 'Admin VitaGuard' }}
                                </span>

                                <span class="article-date">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ optional($article->published_at ?? $article->created_at)->format('d M Y') }}
                                </span>
                            </div>

                            <h5 class="article-title">
                                {{ \Illuminate\Support\Str::limit($article->title, 68) }}
                            </h5>

                            <p class="article-excerpt">
                                {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 110) }}
                            </p>

                            <div class="article-meta-bottom">
                                <div class="article-views">
                                    <i class="bi bi-eye"></i>
                                    <span>{{ number_format($article->views) }} dilihat</span>
                                </div>

                                <a
                                    href="{{ route('member.articles.show', $article) }}"
                                    class="btn btn-vg-primary btn-sm article-btn"
                                >
                                    Baca Artikel
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="article-empty">
                        <div class="article-empty-icon">
                            <i class="bi bi-search-heart"></i>
                        </div>
                        <h4 class="fw-bold mb-2">Artikel tidak ditemukan</h4>
                        <p class="text-muted mb-3">
                            Coba gunakan kata kunci lain atau hapus filter pencarian untuk melihat lebih banyak artikel.
                        </p>

                        @if(request('search'))
                            <a href="{{ route('member.articles.index') }}" class="btn btn-outline-vg-primary">
                                Lihat Semua Artikel
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        @if($articles->hasPages())
            <div class="article-pagination mt-5">
                {{ $articles->withQueryString()->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</section>
@endsection