@extends('layouts.admin')

@section('title', 'Detail Booking - Panel Dokter')
@section('page-title', 'Detail Booking')

@section('content')

<div class="mb-3">
    <a href="{{ route('doctor.bookings.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Kode: {{ $booking->booking_code }}</h3>
                @if($booking->status === 'pending')
                    <span class="badge bg-warning text-dark fs-6">Pending</span>
                @elseif($booking->status === 'confirmed')
                    <span class="badge bg-success fs-6">Dikonfirmasi</span>
                @elseif($booking->status === 'ongoing')
                    <span class="badge bg-primary fs-6">Berlangsung</span>
                @elseif($booking->status === 'completed')
                    <span class="badge bg-info fs-6">Selesai</span>
                @else
                    <span class="badge bg-secondary fs-6">Dibatalkan</span>
                @endif
            </div>
            <div class="card-body">

                <h5 class="mb-3">Data Pasien</h5>
                <table class="table table-sm">
                    <tr>
                        <td style="width:35%">Nama</td>
                        <td>{{ $booking->member->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $booking->member->user->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>No. Telepon</td>
                        <td>{{ $booking->member->phone ?? '-' }}</td>
                    </tr>
                    @if($booking->member->blood_type)
                        <tr>
                            <td>Golongan Darah</td>
                            <td>{{ $booking->member->blood_type }}</td>
                        </tr>
                    @endif
                </table>

                <h5 class="mb-3 mt-4">Jadwal Konsultasi</h5>
                <table class="table table-sm">
                    <tr>
                        <td style="width:35%">Tanggal</td>
                        <td>
                            @if($booking->schedule && $booking->schedule->date)
                                {{ $booking->schedule->date->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Waktu</td>
                        <td>
                            @if($booking->schedule)
                                {{ substr($booking->schedule->start_time, 0, 5) }} – {{ substr($booking->schedule->end_time, 0, 5) }} WIB
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>

                <h5 class="mb-3 mt-4">Keluhan Pasien</h5>
                <div class="p-3 rounded bg-light border mb-3">
                    <p class="mb-0">{{ $booking->complaint }}</p>
                </div>

                @if($booking->notes)
                    <h5 class="mb-3">Catatan Tambahan</h5>
                    <div class="p-3 rounded bg-light border">
                        <p class="mb-0">{{ $booking->notes }}</p>
                    </div>
                @endif

            </div>
        </div>

    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Booking</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Dibuat</td>
                        <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Diperbarui</td>
                        <td>{{ $booking->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
