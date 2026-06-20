@extends('layouts.admin')

@section('title', 'Detail Pasien')
@section('page-title', 'Detail Pasien')
@section('menu-members', 'active')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-person-circle text-secondary" style="font-size: 5rem;"></i>
                <h5 class="mt-3">{{ $member->user->name }}</h5>
                <p class="text-muted">{{ $member->user->email }}</p>
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
                <h3 class="card-title">Informasi Pasien</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('admin.members.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <h5 class="mb-3">Data Personal</h5>
                <table class="table table-sm">
                    <tr>
                        <td style="width: 30%">Nama</td>
                        <td>{{ $member->user->name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $member->user->email }}</td>
                    </tr>
                    <tr>
                        <td>No. Telepon</td>
                        <td>{{ $member->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>
                            @if($member->gender === 'Man')
                                Laki-laki
                            @elseif($member->gender === 'Woman')
                                Perempuan
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td>{{ $member->date_of_birth ? $member->date_of_birth->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>{{ $member->address ?? '-' }}</td>
                    </tr>
                </table>

                <h5 class="mb-3 mt-4">Informasi Kesehatan</h5>
                <table class="table table-sm">
                    <tr>
                        <td style="width: 30%">Golongan Darah</td>
                        <td>
                            @if($member->blood_type)
                                <span class="badge bg-danger">{{ $member->blood_type }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                </table>

                <h5 class="mb-3 mt-4">Data Pendaftaran</h5>
                <table class="table table-sm">
                    <tr>
                        <td style="width: 30%">Tanggal Daftar</td>
                        <td>{{ $member->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Terakhir Diperbarui</td>
                        <td>{{ $member->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
