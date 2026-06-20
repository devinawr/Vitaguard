@extends('layouts.admin')

@section('title', 'Detail Booking')
@section('page-title', 'Detail Booking Konsultasi')
@section('menu-bookings', 'active')

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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($booking->status === 'pending')
            <div class="card mb-3" style="border-left: 4px solid #198754;">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <strong class="text-success"><i class="bi bi-clock-history me-1"></i> Booking ini menunggu konfirmasi</strong>
                        <p class="mb-0 text-muted small">Konfirmasi agar dokter dapat melihat booking ini di panel mereka.</p>
                    </div>
                    <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="ms-3">
                        @csrf
                        <button type="submit" class="btn btn-success"
                                onclick="return confirm('Konfirmasi booking ini?')">
                            <i class="bi bi-check-circle me-1"></i> Konfirmasi Booking
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Booking</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <h5 class="mb-3">Data Pasien</h5>
                <table class="table table-sm">
                    <tr>
                        <td style="width: 30%">Nama Pasien</td>
                        <td>{{ $booking->member->user->name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $booking->member->user->email }}</td>
                    </tr>
                    <tr>
                        <td>No. Telepon</td>
                        <td>{{ $booking->member->phone ?? '-' }}</td>
                    </tr>
                </table>

                <h5 class="mb-3 mt-4">Data Dokter</h5>
                <table class="table table-sm">
                    <tr>
                        <td style="width: 30%">Nama Dokter</td>
                        <td>{{ $booking->doctor->user->name }}</td>
                    </tr>
                    <tr>
                        <td>Spesialis</td>
                        <td>{{ $booking->doctor->specialty }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $booking->doctor->user->email }}</td>
                    </tr>
                </table>

                <h5 class="mb-3 mt-4">Jadwal Konsultasi</h5>
                <table class="table table-sm">
                    <tr>
                        <td style="width: 30%">Tanggal</td>
                        <td>{{ $booking->schedule ? $booking->schedule->date->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jam Mulai</td>
                        <td>{{ $booking->schedule->start_time ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jam Selesai</td>
                        <td>{{ $booking->schedule->end_time ?? '-' }}</td>
                    </tr>
                </table>

                <h5 class="mb-3 mt-4">Status Booking</h5>
                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Perbarui Status
                            </button>
                        </div>
                    </div>
                </form>

                <h5 class="mb-3 mt-4">Informasi Tambahan</h5>
                <table class="table table-sm">
                    <tr>
                        <td style="width: 30%">Tanggal Booking</td>
                        <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Terakhir Diperbarui</td>
                        <td>{{ $booking->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($booking->consultation)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Konsultasi</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.consultations.show', $booking->consultation) }}" class="btn btn-info">
                        <i class="bi bi-eye"></i> Lihat Detail Konsultasi
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
