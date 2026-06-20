@extends('layouts.admin')
@section('title', 'Detail Artikel')
@section('page-title', 'Detail Artikel')
@section('menu-articles', 'active')

@section('content')
<div class="row">
    <div class="col-md-9">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">{{ $article->title }}</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="mb-4 text-center">
                    <img 
                        src="{{ $article->thumbnail
                            ? (\Illuminate\Support\Str::startsWith($article->thumbnail, ['http://', 'https://'])
                                ? $article->thumbnail
                                : Storage::url($article->thumbnail))
                            : 'https://placehold.co/800x500/fdeef1/ef8aa0?text=VitaGuard+Article' }}"
                        alt="{{ $article->title }}" 
                        class="img-fluid rounded border"
                        loading="lazy"
                        style="max-width: 100%; max-height: 400px; object-fit: cover;"
                    >
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-2">
                            <strong>Penulis:</strong> {{ $article->author->name ?? '-' }}
                        </small>
                        <small class="text-muted d-block mb-2">
                            <strong>Slug:</strong> {{ $article->slug ?? '-' }}
                        </small>
                        <small class="text-muted d-block mb-2">
                            <strong>Views:</strong> {{ $article->views ?? 0 }}
                        </small>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted d-block mb-2">
                            <strong>Status:</strong>
                            @if($article->status === 'published')
                                <span class="badge bg-success">Published</span>
                            @elseif($article->status === 'draft')
                                <span class="badge bg-warning text-dark">Draft</span>
                            @elseif($article->status === 'archive')
                                <span class="badge bg-secondary">Archive</span>
                            @else
                                <span class="badge bg-dark">Unknown</span>
                            @endif
                        </small>

                        <small class="text-muted d-block mb-2">
                            <strong>Tanggal Publish:</strong>
                            {{ $article->published_at ? $article->published_at->format('d/m/Y H:i') : '-' }}
                        </small>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <h5 class="fw-bold">Konten Artikel</h5>
                    <div class="content" style="line-height: 1.8;">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <small class="text-muted d-block">
                    <strong>Dibuat:</strong> {{ $article->created_at ? $article->created_at->format('d/m/Y H:i') : '-' }}
                </small>
                <small class="text-muted d-block">
                    <strong>Diperbarui:</strong> {{ $article->updated_at ? $article->updated_at->format('d/m/Y H:i') : '-' }}
                </small>

                @if(!empty($article->deleted_at))
                    <small class="text-danger d-block mt-1">
                        <strong>Deleted At:</strong> {{ $article->deleted_at->format('d/m/Y H:i') }}
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection