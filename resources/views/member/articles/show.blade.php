@extends('layouts.member')
@section('title', $article->title)

@push('styles')
<style>
    .article-show-section {
        padding-top: 3rem;
        padding-bottom: 4rem;
        background:
            radial-gradient(circle at top left, rgba(239, 138, 160, 0.08), transparent 28%),
            linear-gradient(180deg, #fff 0%, #fff8fa 100%);
    }

    .article-show-container {
        max-width: 900px;
    }

    .article-back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--vg-primary);
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .article-back-link:hover {
        color: var(--vg-primary-dark);
    }

    .article-show-card {
        background: #fff;
        border: 1px solid rgba(239, 138, 160, 0.12);
        border-radius: 28px;
        padding: 2rem;
        margin-top: 1.25rem;
        box-shadow: 0 14px 35px rgba(58, 34, 48, 0.06);
    }

    .article-show-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.55rem 0.95rem;
        border-radius: 999px;
        background: #fff1f4;
        color: var(--vg-primary);
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .article-show-title {
        font-size: clamp(1.9rem, 4vw, 2.8rem);
        line-height: 1.25;
        font-weight: 800;
        color: var(--vg-dark);
        margin-bottom: 1rem;
    }

    .article-show-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .article-show-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.6rem 0.9rem;
        border-radius: 999px;
        background: #fff7f9;
        color: #7f6570;
        border: 1px solid rgba(239, 138, 160, 0.12);
        font-size: 0.88rem;
        font-weight: 600;
    }

    .article-show-cover {
        overflow: hidden;
        border-radius: 24px;
        margin-bottom: 1.75rem;
        background: #fff2f5;
        border: 1px solid rgba(239, 138, 160, 0.10);
    }

    .article-show-cover img {
        width: 100%;
        max-height: 460px;
        object-fit: cover;
        display: block;
    }

    .article-content {
        color: #5a434c;
        font-size: 1rem;
        line-height: 1.95;
        word-wrap: break-word;
    }

    .article-content h1,
    .article-content h2,
    .article-content h3,
    .article-content h4,
    .article-content h5,
    .article-content h6 {
        color: var(--vg-dark);
        font-weight: 700;
        margin-top: 1.8rem;
        margin-bottom: 0.9rem;
        line-height: 1.4;
    }

    .article-content p {
        margin-bottom: 1.1rem;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 18px;
        margin: 1rem 0;
    }

    .article-content ul,
    .article-content ol {
        padding-left: 1.2rem;
        margin-bottom: 1.1rem;
    }

    .article-content blockquote {
        margin: 1.5rem 0;
        padding: 1rem 1.25rem;
        border-left: 4px solid var(--vg-primary);
        background: #fff6f8;
        color: #6c4f5a;
        border-radius: 0 16px 16px 0;
    }

    .article-content a {
        color: var(--vg-primary);
        text-decoration: none;
        font-weight: 600;
    }

    .article-content a:hover {
        color: var(--vg-primary-dark);
        text-decoration: underline;
    }

    .article-note {
        margin-top: 2rem;
        border: 1px solid rgba(239, 138, 160, 0.14);
        background: linear-gradient(180deg, #fff9fb 0%, #fff2f6 100%);
        border-radius: 20px;
        padding: 1rem 1.1rem;
        color: #71535f;
    }

    .article-note-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: var(--vg-dark);
        margin-bottom: 0.35rem;
    }

    @media (max-width: 767.98px) {
        .article-show-section {
            padding-top: 2rem;
            padding-bottom: 3rem;
        }

        .article-show-card {
            padding: 1.25rem;
            border-radius: 22px;
        }

        .article-show-cover {
            border-radius: 18px;
        }

        .article-show-cover img {
            max-height: 260px;
        }

        .article-content {
            font-size: 0.96rem;
            line-height: 1.85;
        }
    }
</style>
@endpush

@section('content')
<section class="article-show-section">
    <div class="container article-show-container">
        <a href="{{ route('member.articles.index') }}" class="article-back-link">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke artikel</span>
        </a>

        <article class="article-show-card">
            <h1 class="article-show-title">{{ $article->title }}</h1>

            <div class="article-show-meta">
                <span class="article-show-chip">
                    <i class="bi bi-person-circle"></i>
                    {{ $article->author->name ?? 'Admin VitaGuard' }}
                </span>

                <span class="article-show-chip">
                    <i class="bi bi-calendar3"></i>
                    {{ optional($article->published_at ?? $article->created_at)->translatedFormat('d M Y') }}
                </span>

                <span class="article-show-chip">
                    <i class="bi bi-eye"></i>
                    {{ number_format($article->views) }} dilihat
                </span>

               
            </div>

            <div class="article-show-cover">
                <img
                    src="{{ $article->thumbnail
                        ? (\Illuminate\Support\Str::startsWith($article->thumbnail, ['http://', 'https://'])
                            ? $article->thumbnail
                            : asset($article->thumbnail))
                        : 'https://placehold.co/1200x630/fdeef1/ef8aa0?text=VitaGuard+Article' }}"
                    alt="{{ $article->title }}"
                    class="img-fluid"
                    loading="lazy"
                >
            </div>

            <div class="article-content">
                {!! $article->content !!}
            </div>

            <div class="article-note">
                <div class="article-note-title">
                    <i class="bi bi-info-circle"></i>
                    <span>Catatan penting</span>
                </div>
                <div>
                    Artikel ini bersifat edukatif dan informatif. Jika kamu memiliki keluhan kesehatan tertentu, sebaiknya konsultasikan langsung dengan dokter atau tenaga medis profesional.
                </div>
            </div>
        </article>
    </div>
</section>
@endsection