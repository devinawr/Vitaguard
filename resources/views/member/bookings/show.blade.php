@extends('layouts.member')

@section('title', 'Detail Booking - VitaGuard')

@section('content')
<div class="container py-5">

    <div class="mb-3">
        <a href="{{ route('member.bookings.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Booking Saya
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Main Info --}}
        <div class="col-lg-8">

            {{-- Status Card --}}
            <div class="card border-0 rounded-4 mb-4" style="box-shadow:0 4px 18px rgba(58,34,48,0.07);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bold mb-1" style="color:#2d2230;">Kode Booking</h6>
                            <span class="badge rounded-pill px-3 py-2"
                                  style="background:#fdeef1;color:#e06483;font-size:0.9rem;font-weight:700;letter-spacing:0.5px;">
                                {{ $booking->booking_code }}
                            </span>
                        </div>
                        <div class="text-end">
                            @if($booking->status === 'pending')
                                <span class="badge rounded-pill bg-warning text-dark fs-6 px-3">Menunggu Konfirmasi</span>
                            @elseif($booking->status === 'confirmed')
                                <span class="badge rounded-pill bg-success fs-6 px-3">Dikonfirmasi</span>
                            @elseif($booking->status === 'ongoing')
                                <span class="badge rounded-pill bg-primary fs-6 px-3">Berlangsung</span>
                            @elseif($booking->status === 'completed')
                                <span class="badge rounded-pill bg-info fs-6 px-3">Selesai</span>
                            @else
                                <span class="badge rounded-pill bg-secondary fs-6 px-3">Dibatalkan</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    {{-- Doctor --}}
                    <h6 class="fw-bold mb-3" style="color:#2d2230;">Data Dokter</h6>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        @if($booking->doctor->photo)
                            <img src="{{ Storage::url($booking->doctor->photo) }}" alt=""
                                 class="rounded-circle"
                                 style="width:56px;height:56px;object-fit:cover;border:2px solid #fdeef1;">
                        @else
                            <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#ef8aa0,#e06483);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;font-weight:700;">
                                {{ strtoupper(substr($booking->doctor->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="fw-bold mb-0" style="color:#2d2230;">{{ $booking->doctor->user->name }}</p>
                            <small class="text-vg-primary fw-semibold">{{ $booking->doctor->specialty }}</small>
                        </div>
                    </div>

                    {{-- Schedule --}}
                    <h6 class="fw-bold mb-3" style="color:#2d2230;">Jadwal Konsultasi</h6>
                    @if($booking->schedule)
                        <div class="p-3 rounded-3 mb-4" style="background:#fdeef1;">
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">Tanggal</small>
                                    <span class="fw-semibold" style="color:#2d2230;">
                                        {{ $booking->schedule->date->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block">Waktu</small>
                                    <span class="fw-semibold" style="color:#2d2230;">
                                        {{ substr($booking->schedule->start_time, 0, 5) }} – {{ substr($booking->schedule->end_time, 0, 5) }} WIB
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">-</p>
                    @endif

                    {{-- Complaint --}}
                    <h6 class="fw-bold mb-2" style="color:#2d2230;">Keluhan</h6>
                    <p class="text-muted mb-4" style="line-height:1.8;">{{ $booking->complaint }}</p>

                    @if($booking->notes)
                        <h6 class="fw-bold mb-2" style="color:#2d2230;">Catatan Tambahan</h6>
                        <p class="text-muted mb-0" style="line-height:1.8;">{{ $booking->notes }}</p>
                    @endif
                </div>
            </div>

            {{-- Cancel Button --}}
            @if(in_array($booking->status, ['pending', 'confirmed']))
                <div class="card border-0 rounded-4" style="box-shadow:0 4px 18px rgba(58,34,48,0.07);border-left:4px solid #dc3545 !important;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-1" style="color:#2d2230;">Batalkan Booking</h6>
                        <p class="text-muted small mb-3">
                            Pembatalan booking akan membebaskan kembali jadwal tersebut.
                        </p>
                        <form action="{{ route('member.bookings.destroy', $booking) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill">
                                <i class="bi bi-x-circle me-1"></i> Batalkan Booking
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card border-0 rounded-4" style="box-shadow:0 4px 18px rgba(58,34,48,0.07);">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3" style="color:#2d2230;">Informasi Booking</h6>
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted small">Dibuat</td>
                            <td class="small fw-semibold">{{ $booking->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Diperbarui</td>
                            <td class="small fw-semibold">{{ $booking->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>

                    <hr>

                    <div class="text-center">
                        <a href="{{ route('member.doctors.show', $booking->doctor) }}"
                           class="btn btn-outline-secondary rounded-pill btn-sm w-100">
                            Lihat Profil Dokter
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
