@extends('layouts.admin')

@section('title', 'Konsultasi - Panel Dokter')
@section('page-title', 'Konsultasi Pasien')
@section('menu-consultations', 'active')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold">Konsultasi Aktif</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Pasien</th><th>Mulai</th><th>Pesan</th><th>Status</th><th class="text-end">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($active as $c)
                            <tr>
                                <td>{{ $c->booking->member->user->name }}</td>
                                <td>{{ optional($c->started_at)->format('d M Y, H:i') ?? '-' }}</td>
                                <td>{{ $c->messages_count }}</td>
                                <td><span class="badge bg-success">Berlangsung</span></td>
                                <td class="text-end"><a href="{{ route('doctor.consultations.show', $c) }}" class="btn btn-sm btn-primary">Buka Chat</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada konsultasi aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold">Riwayat Konsultasi Pasien</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Pasien</th><th>Selesai</th><th>Ringkasan</th><th>Status</th><th class="text-end">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($history as $c)
                            <tr>
                                <td>{{ $c->booking->member->user->name }}</td>
                                <td>{{ optional($c->ended_at)->format('d M Y, H:i') ?? '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($c->summary, 50) ?: '-' }}</td>
                                <td><span class="badge bg-secondary">Selesai</span></td>
                                <td class="text-end"><a href="{{ route('doctor.consultations.show', $c) }}" class="btn btn-sm btn-outline-secondary">Lihat</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat konsultasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($history->hasPages())
                <div class="card-body">{{ $history->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
