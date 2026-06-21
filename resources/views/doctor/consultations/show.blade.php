@extends('layouts.admin')

@section('title', 'Ruang Konsultasi - Panel Dokter')
@section('page-title', 'Ruang Konsultasi')
@section('menu-consultations', 'active')

@push('styles')
<style>
    .chat-box { height: 440px; overflow-y:auto; background:#f8f9fa; border-radius:12px; padding:1rem; }
    .chat-bubble { max-width:75%; padding:.55rem .85rem; border-radius:12px; margin-bottom:.55rem; font-size:.9rem; word-wrap:break-word; }
    .chat-mine { background:#0d6efd; color:#fff; margin-left:auto; }
    .chat-theirs { background:#fff; border:1px solid #e3e6ea; }
    .chat-meta { font-size:.7rem; opacity:.75; margin-top:2px; }
</style>
@endpush

@section('content')
<a href="{{ route('doctor.consultations.index') }}" class="btn btn-sm btn-light mb-3">&larr; Kembali</a>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Percakapan dengan {{ $consultation->booking->member->user->name }}</span>
                @if($consultation->status === 'active')
                    <span class="badge bg-success">Berlangsung</span>
                @else
                    <span class="badge bg-secondary">Selesai</span>
                @endif
            </div>
            <div class="card-body">
                <div class="chat-box mb-3" id="chatBox">
                    @forelse($consultation->messages as $message)
                        @php $mine = $message->sender_id === auth()->id(); @endphp
                        <div class="chat-bubble {{ $mine ? 'chat-mine' : 'chat-theirs' }}">
                            <div>{{ $message->message }}</div>
                            <div class="chat-meta">{{ $mine ? 'Anda' : $message->sender->name }} &middot; {{ $message->created_at->format('H:i') }}</div>
                        </div>
                    @empty
                        <p class="text-center text-muted my-5">Belum ada pesan.</p>
                    @endforelse
                </div>

                @if($consultation->status === 'active')
                    <form action="{{ route('doctor.consultations.messages.store', $consultation) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="message" class="form-control" placeholder="Tulis balasan..." required autocomplete="off">
                        <button class="btn btn-primary px-4">Kirim</button>
                    </form>
                @else
                    <div class="alert alert-secondary mb-0">Konsultasi telah ditutup.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Informasi Pasien</h6>
                <p class="mb-1">{{ $consultation->booking->member->user->name }}</p>
                <p class="text-muted small mb-2">{{ $consultation->booking->member->user->email }}</p>
                <hr>
                <h6 class="fw-bold">Keluhan</h6>
                <p class="small mb-0">{{ $consultation->booking->complaint ?? '-' }}</p>
            </div>
        </div>

        @if($consultation->status === 'active')
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Tutup Konsultasi</h6>
                    <form action="{{ route('doctor.consultations.close', $consultation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-2">
                            <label class="form-label small">Ringkasan <span class="text-danger">*</span></label>
                            <textarea name="summary" class="form-control @error('summary') is-invalid @enderror" rows="3" required>{{ old('summary') }}</textarea>
                            @error('summary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Diagnosis</label>
                            <textarea name="diagnosis" class="form-control" rows="2">{{ old('diagnosis') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tutup konsultasi ini? Riwayat tidak dapat dihapus.')">Tutup &amp; Simpan Ringkasan</button>
                    </form>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold">Ringkasan</h6>
                    <p class="small">{{ $consultation->summary ?? '-' }}</p>
                    <h6 class="fw-bold">Diagnosis</h6>
                    <p class="small mb-0">{{ $consultation->diagnosis ?? '-' }}</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    const box = document.getElementById('chatBox');
    if (box) box.scrollTop = box.scrollHeight;
</script>
@endsection
