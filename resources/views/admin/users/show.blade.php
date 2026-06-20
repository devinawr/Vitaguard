@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')
@section('menu-users', 'active')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-person-circle text-secondary" style="font-size: 5rem;"></i>
                <h5 class="mt-3">{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->email }}</p>
                @if($user->role === 'admin')
                    <span class="badge bg-danger">Admin</span>
                @elseif($user->role === 'doctor')
                    <span class="badge bg-info">Dokter</span>
                @else
                    <span class="badge bg-success">Member</span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Pengguna</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td style="width: 30%">Nama</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @elseif($user->role === 'doctor')
                                <span class="badge bg-info">Dokter</span>
                            @else
                                <span class="badge bg-success">Member</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            @if($user->deleted_at)
                                <span class="badge bg-secondary">Nonaktif</span>
                            @else
                                <span class="badge bg-success">Aktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Email Terverifikasi</td>
                        <td>
                            @if($user->email_verified_at)
                                <i class="bi bi-check-circle text-success"></i> {{ $user->email_verified_at->format('d/m/Y H:i') }}
                            @else
                                <i class="bi bi-x-circle text-danger"></i> Belum Terverifikasi
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal Daftar</td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Terakhir Diperbarui</td>
                        <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
