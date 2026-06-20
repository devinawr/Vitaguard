@extends('layouts.admin')

@section('title', 'Members Management')
@section('page-title', 'Data Pasien')
@section('menu-members', 'active')

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
                <h3 class="card-title">Daftar Pasien</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.members.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus"></i> Tambah Pasien
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama/email pasien" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
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
                                <th>Nama Pasien</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Golongan Darah</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $member)
                                <tr>
                                    <td>{{ ($members->currentPage() - 1) * $members->perPage() + $loop->iteration }}</td>
                                    <td>{{ $member->user->name }}</td>
                                    <td>{{ $member->user->email }}</td>
                                    <td>{{ $member->phone ?? '-' }}</td>
                                    <td>
                                        @if($member->blood_type)
                                            <span class="badge bg-danger">{{ $member->blood_type }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $member->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.members.show', $member) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.members.destroy', $member) }}" method="POST" style="display:inline;">
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
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p>Tidak ada data pasien</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $members->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
