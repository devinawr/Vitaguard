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

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $article->title }}</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($article->thumbnail)
                    <div class="mb-3">
                        <img src="{{ Storage::url($article->thumbnail) }}" alt="" class="img-fluid rounded" style="max-width: 100%; max-height: 400px;">
                    </div>
                @endif

                <div class="mb-3">
                    <small class="text-muted">
                        <i class="bi bi-pencil-square"></i> Oleh {{ $article->author->name }} | 
                        <i class="bi bi-eye"></i> {{ $article->views }} views
                    </small>
                </div>

                <div class="content">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </div>
            <div class="card-footer">
                <small class="text-muted">
                    <strong>Status:</strong>
                    @if($article->status === 'published')
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                </small>
                <br>
                <small class="text-muted">
                    <strong>Dibuat:</strong> {{ $article->created_at->format('d/m/Y H:i') }}
                    <strong class="ms-3">Diperbarui:</strong> {{ $article->updated_at->format('d/m/Y H:i') }}
                    @if($article->published_at)
                        <strong class="ms-3">Dipublikasi:</strong> {{ $article->published_at->format('d/m/Y') }}
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
