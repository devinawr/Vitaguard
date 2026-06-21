@extends('layouts.member')

@section('title', 'Konsultasi Saya - VitaGuard')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <span class="badge rounded-pill px-3 py-2 mb-2" style="background:#fff0f4;color:var(--vg-primary,#e06483);">Konsultasi online</span>
        <h1 class="fw-bold" style="color:var(--vg-dark,#3a2230);">Konsultasi Saya</h1>
        <p class="text-muted">Mulai konsultasi dari booking yang sudah dikonfirmasi, lalu pantau riwayat percakapanmu.</p>
    </div>

    @if(session('success'))<div class="alert alert-success rounded-3">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger rounded-3">{{ session('error') }}</div>@endif

    @if($startable->isNotEmpty())
        <h5 class="fw-semibold mb-3" style="color:var(--vg-dark,#3a2230);">Siap Dikonsultasikan</h5>
        <div class="row g-3 mb-5">
            @foreach($startable as $booking)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-dark">{{ $booking->doctor->user->name }}</div>
                            <small class="text-muted">{{ $booking->doctor->specialty }}</small>
                            <div class="small text-muted mt-1">Kode: {{ $booking->booking_code }}</div>
                        </div>
                        <form action="{{ route('member.consultations.start', $booking) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm text-white rounded-pill px-3" style="background:var(--vg-primary,#e06483);">Mulai Konsultasi</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <h5 class="fw-semibold mb-3" style="color:var(--vg-dark,#3a2230);">Riwayat Konsultasi</h5>
    @forelse($consultations as $consultation)
        <a href="{{ route('member.consultations.show', $consultation) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-dark">{{ $consultation->booking->doctor->user->name }}</div>
                        <small class="text-muted">{{ $consultation->booking->doctor->specialty }}</small>
                        <div class="small text-muted mt-1">
                            Mulai: {{ optional($consultation->started_at)->translatedFormat('d M Y, H:i') ?? '-' }}
                            &middot; {{ $consultation->messages_count }} pesan
                        </div>
                    </div>
                    <div class="text-end">
                        @if($consultation->status === 'active')
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Berlangsung</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Selesai</span>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="text-center text-muted py-5">
            <p class="mb-0">Belum ada konsultasi. Mulai dari booking yang sudah dikonfirmasi.</p>
        </div>
    @endforelse

    @if($consultations->hasPages())
        <div class="mt-3">{{ $consultations->links() }}</div>
    @endif
</div>
@endsection
