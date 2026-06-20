@extends('layouts.admin')
@section('title', 'Tambah Artikel')
@section('page-title', 'Tambah Artikel Kesehatan Baru')
@section('menu-articles', 'active')
@section('content')
<div class="row">
    <div class="col-md-9">
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Artikel</h3>
            </div>
            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control @error('title') is-invalid @enderror"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="Masukkan judul artikel"
                        >
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Sumber Thumbnail</label>
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="thumbnail_type"
                                id="thumbnail_type_upload"
                                value="upload"
                                {{ old('thumbnail_type', 'upload') === 'upload' ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="thumbnail_type_upload">Upload File</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="thumbnail_type"
                                id="thumbnail_type_link"
                                value="link"
                                {{ old('thumbnail_type') === 'link' ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="thumbnail_type_link">Pakai Link</label>
                        </div>
                        @error('thumbnail_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="thumbnail-upload-group">
                        <label for="thumbnail_file" class="form-label">Upload Thumbnail</label>
                        <input
                            type="file"
                            class="form-control @error('thumbnail_file') is-invalid @enderror"
                            id="thumbnail_file"
                            name="thumbnail_file"
                            accept="image/*"
                        >
                        <small class="text-muted">Format: JPEG, PNG, JPG, GIF, WEBP. Maksimal 2MB.</small>
                        @error('thumbnail_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 d-none" id="thumbnail-link-group">
                        <label for="thumbnail_link" class="form-label">Link Thumbnail</label>
                        <input
                            type="url"
                            class="form-control @error('thumbnail_link') is-invalid @enderror"
                            id="thumbnail_link"
                            name="thumbnail_link"
                            value="{{ old('thumbnail_link') }}"
                            placeholder="https://example.com/gambar.jpg"
                        >
                        <small class="text-muted">Masukkan URL gambar yang valid.</small>
                        @error('thumbnail_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Preview Thumbnail</label>
                        <img
                            id="thumbnail-preview"
                            src="https://placehold.co/800x500/fdeef1/ef8aa0?text=Preview+Thumbnail"
                            alt="Preview Thumbnail"
                            class="img-fluid rounded border"
                            style="max-width: 100%; max-height: 300px; object-fit: cover;"
                        >
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                        <textarea
                            class="form-control @error('content') is-invalid @enderror"
                            id="content"
                            name="content"
                            rows="10"
                            placeholder="Masukkan konten artikel"
                        >{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archive" {{ old('status') == 'archive' ? 'selected' : '' }}>Archive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 d-none" id="published-at-group">
                            <label for="published_at" class="form-label">Tanggal & Jam Publikasi</label>
                            <input
                                type="datetime-local"
                                class="form-control @error('published_at') is-invalid @enderror"
                                id="published_at"
                                name="published_at"
                                value="{{ old('published_at') }}"
                            >
                            <small class="text-muted">Kosongkan jika ingin artikel langsung tayang saat disimpan. Isi tanggal & jam ke depan untuk menjadwalkan publish nanti.</small>
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary float-end">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const uploadRadio = document.getElementById('thumbnail_type_upload');
    const linkRadio = document.getElementById('thumbnail_type_link');
    const uploadGroup = document.getElementById('thumbnail-upload-group');
    const linkGroup = document.getElementById('thumbnail-link-group');
    const fileInput = document.getElementById('thumbnail_file');
    const linkInput = document.getElementById('thumbnail_link');
    const preview = document.getElementById('thumbnail-preview');
    const placeholder = 'https://placehold.co/800x500/fdeef1/ef8aa0?text=Preview+Thumbnail';

    function toggleThumbnailInput() {
        if (uploadRadio.checked) {
            uploadGroup.classList.remove('d-none');
            linkGroup.classList.add('d-none');
            if (!fileInput.files.length) {
                preview.src = placeholder;
            }
        } else {
            uploadGroup.classList.add('d-none');
            linkGroup.classList.remove('d-none');
            preview.src = linkInput.value.trim() || placeholder;
        }
    }

    fileInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) {
            preview.src = placeholder;
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    linkInput.addEventListener('input', function () {
        preview.src = this.value.trim() || placeholder;
    });

    uploadRadio.addEventListener('change', toggleThumbnailInput);
    linkRadio.addEventListener('change', toggleThumbnailInput);
    toggleThumbnailInput();

    // Toggle field Tanggal & Jam Publikasi berdasarkan status
    const statusSelect = document.getElementById('status');
    const publishedAtGroup = document.getElementById('published-at-group');

    function togglePublishedAt() {
        if (statusSelect.value === 'draft') {
            publishedAtGroup.classList.add('d-none');
        } else {
            publishedAtGroup.classList.remove('d-none');
        }
    }

    statusSelect.addEventListener('change', togglePublishedAt);
    togglePublishedAt();
});
</script>
@endsection