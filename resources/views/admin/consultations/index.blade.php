@extends('layouts.admin')

@section('title', 'Consultations Management')
@section('page-title', 'Kelola Konsultasi')
@section('menu-consultations', 'active')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Konsultasi</h3>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama pasien/dokter" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">-- Semua Status --</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Berlangsung</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Selesai</option>
                                <!-- <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option> -->
                            </select>
                        </div>
                        <div class="col-md-3">
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
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Status</th>
                                <th>Jumlah Pesan</th>
                                <th>Tanggal Mulai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($consultations as $consultation)
                                <tr>
                                    <td>{{ ($consultations->currentPage() - 1) * $consultations->perPage() + $loop->iteration }}</td>
                                    <td>{{ $consultation->booking->member->user->name }}</td>
                                    <td>{{ $consultation->booking->doctor->user->name }}</td>
                                    <td>
                                        @if($consultation->status === 'active')
                                            <span class="badge bg-info">Berlangsung</span>
                                        @elseif($consultation->status === 'closed')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Belum Dimulai</span>
                                        @endif
                                    </td>
                                    <td>{{ $consultation->messages->count() }}</td>
                                    <td>{{ $consultation->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.consultations.show', $consultation) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p>Tidak ada data konsultasi</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $consultations->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
