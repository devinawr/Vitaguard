@extends('layouts.admin')

@section('title', 'Booking Masuk - Panel Dokter')
@section('page-title', 'Booking Masuk')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Daftar Booking Dikonfirmasi</h3>
        <span class="badge bg-success">Hanya menampilkan booking yang sudah dikonfirmasi admin</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Booking</th>
                        <th>Pasien</th>
                        <th>Jadwal</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}</td>
                            <td><small class="text-muted">{{ $booking->booking_code }}</small></td>
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
                            <td>{{ \Illuminate\Support\Str::limit($booking->complaint, 40) }}</td>
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
                                <a href="{{ route('doctor.bookings.show', $booking) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p>Tidak ada booking</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $bookings->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection
