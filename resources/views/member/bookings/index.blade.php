@extends('layouts.member')

@section('title', 'Booking Saya - VitaGuard')

@push('styles')
<style>
    /* ── Hero ── */
    .page-hero {
        padding-top: 3.5rem;
        padding-bottom: 2.5rem;
        background:
            radial-gradient(circle at top left, rgba(239,138,160,0.10), transparent 30%),
            linear-gradient(180deg, #fff 0%, #fff7f9 100%);
        border-bottom: 1px solid rgba(239,138,160,0.10);
    }

    .page-hero-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.55rem 1rem; border-radius: 999px;
        background: #fff0f4; color: var(--vg-primary);
        font-size: 0.92rem; font-weight: 600; margin-bottom: 1rem;
    }

    .page-hero-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800; line-height: 1.2;
        color: var(--vg-dark); margin-bottom: 0.85rem;
    }

    .page-hero-subtitle {
        max-width: 540px; margin: 0 auto;
        color: #7f6a74; font-size: 1rem;
    }

    /* ── Booking Card ── */
    .booking-card {
        background: #fff;
        border: none;
        border-radius: 20px;
        box-shadow: 0 3px 14px rgba(58,34,48,0.07);
        margin-bottom: 1rem;
        transition: box-shadow 0.2s;
    }
    .booking-card:hover {
        box-shadow: 0 8px 26px rgba(58,34,48,0.10);
    }

    .booking-code-badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        background: #fdeef1; color: #d9608c;
        border-radius: 8px; padding: 3px 10px;
        font-size: 0.78rem; font-weight: 700;
        letter-spacing: 0.3px;
        font-family: 'Courier New', monospace;
    }

    .doc-avatar {
        width: 48px; height: 48px; border-radius: 50%;
        object-fit: cover; border: 2px solid #fdeef1; flex-shrink: 0;
    }
    .doc-avatar-placeholder {
        width: 48px; height: 48px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg,#ef8aa0,#e06483);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1rem; font-weight: 700;
    }

    @media (max-width: 575.98px) {
        .page-hero { padding-top: 2.5rem; padding-bottom: 2rem; }
    }
</style>
@endpush

@section('content')

{{-- ── Hero ── --}}
<section class="page-hero">
    <div class="container text-center">
        <div class="page-hero-badge">
            <i class="bi bi-calendar2-heart"></i>
            <span>Riwayat konsultasi kamu</span>
        </div>
        <h1 class="page-hero-title">Booking Saya</h1>
        <p class="page-hero-subtitle">
            Pantau status booking konsultasi doktermu di sini — dari menunggu konfirmasi hingga selesai.
        </p>
        <div class="mt-4">
            <a href="{{ route('member.doctors.index') }}" class="btn btn-vg-primary rounded-pill px-4">
                <i class="bi bi-search me-1"></i> Cari Dokter & Booking Baru
            </a>
        </div>
    </div>
</section>

{{-- ── Content ── --}}
<div class="container py-5">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @forelse($bookings as $booking)
        <div class="booking-card">
            <div class="p-4">
                {{-- Top: code + status --}}
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <span class="booking-code-badge">
                        <i class="bi bi-hash"></i>{{ $booking->booking_code }}
                    </span>
                    @if($booking->status === 'pending')
                        <span class="badge rounded-pill bg-warning text-dark">Menunggu Konfirmasi</span>
                    @elseif($booking->status === 'confirmed')
                        <span class="badge rounded-pill bg-success">Dikonfirmasi</span>
                    @elseif($booking->status === 'ongoing')
                        <span class="badge rounded-pill bg-primary">Berlangsung</span>
                    @elseif($booking->status === 'completed')
                        <span class="badge rounded-pill bg-info">Selesai</span>
                    @else
                        <span class="badge rounded-pill bg-secondary">Dibatalkan</span>
                    @endif
                </div>

                <div class="row align-items-center g-3">
                    {{-- Doctor info --}}
                    <div class="col-md-5">
                        <div class="d-flex align-items-center gap-3">
                            @if($booking->doctor->photo)
                                <img src="{{ Storage::url($booking->doctor->photo) }}" alt=""
                                     class="doc-avatar">
                            @else
                                <div class="doc-avatar-placeholder">
                                    {{ strtoupper(substr($booking->doctor->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="fw-bold mb-0" style="color:#2d2230;font-size:0.95rem;">
                                    {{ $booking->doctor->user->name }}
                                </p>
                                <small class="text-vg-primary fw-semibold">{{ $booking->doctor->specialty }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Schedule info --}}
                    <div class="col-md-4">
                        @if($booking->schedule && $booking->schedule->date)
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-calendar3 text-vg-primary mt-1" style="font-size:0.9rem;"></i>
                                <div>
                                    <div class="fw-semibold" style="color:#2d2230;font-size:0.9rem;">
                                        {{ $booking->schedule->date->format('d M Y') }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ substr($booking->schedule->start_time, 0, 5) }} – {{ substr($booking->schedule->end_time, 0, 5) }} WIB
                                    </small>
                                </div>
                            </div>
                        @else
                            <small class="text-muted">Jadwal tidak tersedia</small>
                        @endif
                        <div class="mt-1">
                            <small class="text-muted">Dipesan: {{ $booking->created_at->format('d M Y') }}</small>
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="col-md-3 text-md-end">
                        <a href="{{ route('member.bookings.show', $booking) }}"
                           class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="bi bi-eye me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-calendar-x" style="font-size:3rem;color:#ef8aa0;"></i>
            <h5 class="mt-3 fw-bold" style="color:#2d2230;">Belum Ada Booking</h5>
            <p class="text-muted mb-4">Mulai dengan mencari dokter yang sesuai kebutuhanmu</p>
            <a href="{{ route('member.doctors.index') }}" class="btn btn-vg-primary rounded-pill px-4">
                Cari Dokter
            </a>
        </div>
    @endforelse

    @if($bookings->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $bookings->links('pagination::bootstrap-4') }}
        </div>
    @endif

</div>
@endsection
