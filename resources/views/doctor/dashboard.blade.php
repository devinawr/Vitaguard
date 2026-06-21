@extends('layouts.admin')

@section('title', 'Dashboard - Panel Dokter')
@section('page-title', 'Dashboard Dokter')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card bg-stat-blue">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-title mb-1">Total Booking</p>
                    <p class="stat-value mb-0">{{ $totalBookings }}</p>
                </div>
                <i class="bi bi-calendar-check stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card bg-stat-yellow">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-title mb-1">Booking Dikonfirmasi</p>
                    <p class="stat-value mb-0">{{ $confirmedBookings }}</p>
                </div>
                <i class="bi bi-check2-circle stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card bg-stat-green">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-title mb-1">Booking Hari Ini</p>
                    <p class="stat-value mb-0">{{ $todayBookings }}</p>
                </div>
                <i class="bi bi-person-check stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Booking Terbaru</h3>
        <a href="{{ route('doctor.bookings.index') }}" class="btn btn-sm btn-outline-secondary">
            Lihat Semua
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Pasien</th>
                        <th>Jadwal</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking->member->user->name ?? '-' }}</td>
                            <td>
                                @if($booking->schedule && $booking->schedule->date)
                                    {{ $booking->schedule->date->format('d/m/Y') }}<br>
                                    <small class="text-muted">
                                        {{ substr($booking->schedule->start_time, 0, 5) }}–{{ substr($booking->schedule->end_time, 0, 5) }}
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($booking->complaint, 45) }}</td>
                            <td>
                                @if($booking->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                @elseif($booking->status === 'ongoing')
                                    <span class="badge bg-primary">Berlangsung</span>
                                @elseif($booking->status === 'completed')
                                    <span class="badge bg-info">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('doctor.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p>Belum ada booking masuk</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Konsultasi Terbaru</h3>
        <a href="{{ route('doctor.consultations.index') }}" class="btn btn-sm btn-outline-secondary">
            Lihat Semua
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Pasien</th>
                        <th>Mulai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentConsultations as $consultation)
                        <tr>
                            <td>{{ $consultation->booking->member->user->name ?? '-' }}</td>
                            <td>{{ optional($consultation->started_at)->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                @if($consultation->status === 'active')
                                    <span class="badge bg-primary">Berlangsung</span>
                                @elseif($consultation->status === 'closed')
                                    <span class="badge bg-info">Selesai</span>
                                @else
                                     <span class="badge bg-secondary">{{ ucfirst($consultation->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('doctor.consultations.show', $consultation) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p>Belum ada konsultasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
