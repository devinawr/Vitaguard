@extends('layouts.admin')

@section('title', 'Articles Management')
@section('page-title', 'Kelola Artikel Kesehatan')
@section('menu-articles', 'active')

@section('content')
<div class="row">
    <div class="col-12">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Daftar Artikel Kesehatan</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus"></i> Tambah Artikel
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('admin.articles.index') }}" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari judul artikel"
                                value="{{ request('search') }}"
                            >
                        </div>

                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">-- Semua Status --</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archive" {{ request('status') == 'archive' ? 'selected' : '' }}>Archive</option>
                            </select>
                        </div>

                        <div class="col-md-3 d-grid">
                            <button type="submit" class="btn btn-info">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="60">#</th>
                                <th>Artikel</th>
                                <th>Penulis</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Dipublikasi</th>
                                <th>Dibuat</th>
                                <th width="160">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $article)
                                <tr>
                                    <td>
                                        {{ ($articles->currentPage() - 1) * $articles->perPage() + $loop->iteration }}
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-start gap-3">
                                            <img
    src="{{ $article->thumbnail
        ? (\Illuminate\Support\Str::startsWith($article->thumbnail, ['http://', 'https://']) ? $article->thumbnail : Storage::url($article->thumbnail))
        : 'https://placehold.co/800x500/fdeef1/ef8aa0?text=VitaGuard+Article' }}"
    alt="{{ $article->title }}"
    loading="lazy"
    class="rounded border"
    style="width: 70px; height: 50px; object-fit: cover;"
>

                                            <div>
                                                <div class="fw-semibold">{{ $article->title }}</div>
                                                <small class="text-muted d-block">
                                                    Slug: {{ $article->slug }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        {{ $article->author->name ?? '-' }}
                                    </td>

                                    <td>
                                        @if($article->status === 'published')
                                            <span class="badge bg-success">Published</span>
                                        @elseif($article->status === 'draft')
                                            <span class="badge bg-warning text-dark">Draft</span>
                                        @elseif($article->status === 'archive')
                                            <span class="badge bg-secondary">Archive</span>
                                        @else
                                            <span class="badge bg-dark">Unknown</span>
                                        @endif
                                    </td>

                                    <td>
                                        <i class="bi bi-eye"></i> {{ $article->views ?? 0 }}
                                    </td>

                                    <td>
                                        {{ $article->published_at ? $article->published_at->format('d/m/Y H:i') : '-' }}
                                    </td>

                                    <td>
                                        {{ $article->created_at ? $article->created_at->format('d/m/Y H:i') : '-' }}
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.articles.show', $article->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Hapus"
                                                onclick="return confirm('Yakin hapus artikel ini?')"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox d-block mb-2" style="font-size: 2rem;"></i>
                                        Tidak ada artikel
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($articles->hasPages())
                <div class="card-footer">
                    {{ $articles->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection