@extends('layouts.admin')

@section('title', 'Doctors Management')
@section('page-title', 'Data Dokter')
@section('menu-doctors', 'active')

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

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Dokter</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus"></i> Tambah Dokter
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama/email dokter" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="specialty" class="form-select">
                                <option value="">-- Semua Spesialis --</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty }}" {{ request('specialty') == $specialty ? 'selected' : '' }}>{{ $specialty }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">-- Semua Status --</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info btn-block">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Dokter</th>
                                <th>Email</th>
                                <th>Spesialis</th>
                                <th>No. Lisensi</th>
                                <th>Pengalaman</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($doctors as $doctor)
                                <tr>
                                    <td>{{ ($doctors->currentPage() - 1) * $doctors->perPage() + $loop->iteration }}</td>
                                    <td>
                                        @if($doctor->photo)
                                            <img src="{{ Storage::url($doctor->photo) }}" alt="" class="rounded-circle" width="30" height="30">
                                        @else
                                            <i class="bi bi-person-circle"></i>
                                        @endif
                                        {{ $doctor->user->name }}
                                    </td>
                                    <td>{{ $doctor->user->email }}</td>
                                    <td>{{ $doctor->specialty }}</td>
                                    <td>{{ $doctor->license_number }}</td>
                                    <td>{{ $doctor->experience_years ?? '-' }} tahun</td>
                                    <td>
                                        @if($doctor->status === 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin hapus?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p>Tidak ada data dokter</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $doctors->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
