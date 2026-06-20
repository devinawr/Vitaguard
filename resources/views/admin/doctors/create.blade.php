@extends('layouts.admin')

@section('title', 'Tambah Dokter')
@section('page-title', 'Tambah Dokter Baru')
@section('menu-doctors', 'active')

@section('content')
<div class="row">
    <div class="col-md-8">
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
                <h3 class="card-title">Form Tambah Dokter</h3>
            </div>
            <form action="{{ route('admin.doctors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <!-- Personal Information -->
                    <h5 class="mb-3">Informasi Personal</h5>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Dokter <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                               name="name" value="{{ old('name') }}" placeholder="Masukkan nama dokter">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="Man" {{ old('gender') == 'Man' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Woman" {{ old('gender') == 'Woman' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" 
                               name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto Profil</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" 
                               name="photo" accept="image/*">
                        <small class="text-muted">Format: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Account Information -->
                    <hr>
                    <h5 class="mb-3">Informasi Akun</h5>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" 
                               name="email" value="{{ old('email') }}" placeholder="Masukkan email">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" 
                                   name="password" placeholder="Minimal 8 karakter">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password">
                            @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <hr>
                    <h5 class="mb-3">Informasi Profesional</h5>

                    <div class="mb-3">
                        <label for="specialty" class="form-label">Spesialis <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('specialty') is-invalid @enderror" id="specialty" 
                               name="specialty" value="{{ old('specialty') }}" placeholder="Contoh: Kardiologi, Dermatologi, dll">
                        @error('specialty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="license_number" class="form-label">Nomor Lisensi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('license_number') is-invalid @enderror" id="license_number" 
                               name="license_number" value="{{ old('license_number') }}" placeholder="Masukkan nomor lisensi">
                        @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="experience_years" class="form-label">Tahun Pengalaman</label>
                            <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                   id="experience_years" name="experience_years" value="{{ old('experience_years') }}" 
                                   placeholder="Contoh: 10" min="0" max="80">
                            @error('experience_years')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Biografi</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" 
                                  rows="4" placeholder="Masukkan biografi dokter">{{ old('bio') }}</textarea>
                        @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
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
@endsection
