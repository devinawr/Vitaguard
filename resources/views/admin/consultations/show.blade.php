@extends('layouts.admin')

@section('title', 'Detail Konsultasi')
@section('page-title', 'Detail Konsultasi')
@section('menu-consultations', 'active')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Percakapan</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.consultations.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                @forelse($consultation->messages as $message)
                    <div class="mb-3">
                        <small class="text-muted">
                            {{ $message->sender->name }}
                            <br>
                            {{ $message->created_at->format('d/m/Y H:i') }}
                        </small>
                        <div class="alert alert-light border mt-1">
                            {{ $message->message }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-5">Belum ada pesan</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Konsultasi</h3>
            </div>
            <div class="card-body">
                <h6 class="mb-2">Pasien</h6>
                <p>
                    {{ $consultation->booking->member->user->name }}<br>
                    <small class="text-muted">{{ $consultation->booking->member->user->email }}</small>
                </p>

                <h6 class="mb-2 mt-3">Dokter</h6>
                <p>
                    {{ $consultation->booking->doctor->user->name }}<br>
                    <small class="text-muted">{{ $consultation->booking->doctor->specialty }}</small>
                </p>

                <h6 class="mb-2 mt-3">Status</h6>
                <p>
                    @if($consultation->status === 'ongoing')
                        <span class="badge bg-info">Berlangsung</span>
                    @elseif($consultation->status === 'completed')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-danger">Dibatalkan</span>
                    @endif
                </p>

                <h6 class="mb-2 mt-3">Tanggal Mulai</h6>
                <p>{{ $consultation->created_at->format('d/m/Y H:i') }}</p>

                <h6 class="mb-2 mt-3">Jumlah Pesan</h6>
                <p>{{ $consultation->messages->count() }} pesan</p>

                @if($consultation->summary)
                    <h6 class="mb-2 mt-3">Ringkasan</h6>
                    <p>{{ $consultation->summary }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
