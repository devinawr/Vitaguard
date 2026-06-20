@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                <h3 class="card-title">Edit Profil Dokter</h3>
            </div>

            <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">

                    {{-- Foto Profil --}}
                    <div class="text-center mb-4">
                        @if($doctor->photo)
                            <img src="{{ Storage::url($doctor->photo) }}"
                                 alt="Foto Profil"
                                 class="rounded-circle mb-2"
                                 style="width:90px;height:90px;object-fit:cover;border:3px solid #fdeef1;">
                        @else
                            <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                                 style="width:90px;height:90px;background:linear-gradient(135deg,#ef8aa0,#e06483);color:#fff;font-size:2rem;font-weight:700;">
                                {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <label for="photo" class="btn btn-sm btn-outline-secondary rounded-pill">
                                <i class="bi bi-camera me-1"></i> Ganti Foto
                            </label>
                            <input type="file" name="photo" id="photo" class="d-none" accept="image/*">
                        </div>
                        @error('photo')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <h6 class="fw-bold mb-3 text-muted" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;">Informasi Personal</h6>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $doctor->user->name) }}" placeholder="Nama lengkap">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="Man"   {{ old('gender', $doctor->gender) == 'Man'   ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Woman" {{ old('gender', $doctor->gender) == 'Woman' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                   value="{{ old('date_of_birth', $doctor->date_of_birth?->format('Y-m-d')) }}">
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $doctor->phone) }}" placeholder="08xxxxxxxxxx">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pengalaman (tahun)</label>
                            <input type="number" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror"
                                   value="{{ old('experience_years', $doctor->experience_years) }}" min="0" max="60">
                            @error('experience_years')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3 text-muted" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.5px;">Informasi Profesional</h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Spesialisasi</label>
                            <input type="text" class="form-control" value="{{ $doctor->specialty }}" disabled>
                            <small class="text-muted">Diatur oleh admin</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Lisensi</label>
                            <input type="text" class="form-control" value="{{ $doctor->license_number }}" disabled>
                            <small class="text-muted">Diatur oleh admin</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bio / Tentang Saya</label>
                        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror"
                                  rows="4" placeholder="Ceritakan keahlian dan pengalamanmu...">{{ old('bio', $doctor->bio) }}</textarea>
                        @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('photo').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.querySelector('img.rounded-circle, div.rounded-circle');
                if (img && img.tagName === 'IMG') {
                    img.src = e.target.result;
                }
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush
