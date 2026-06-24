@extends('layouts.member')

@section('title', 'Ruang Konsultasi - VitaGuard')

@push('styles')
<style>
    .chat-box { height: 460px; overflow-y: auto; background:#fff7f9; border-radius:16px; padding:1rem; }
    .chat-bubble { max-width:75%; padding:.6rem .9rem; border-radius:14px; margin-bottom:.6rem; font-size:.92rem; word-wrap:break-word; }
    .chat-mine { background:var(--vg-primary,#e06483); color:#fff; margin-left:auto; border-bottom-right-radius:4px; }
    .chat-theirs { background:#fff; border:1px solid #f0d6de; border-bottom-left-radius:4px; }
    .chat-meta { font-size:.72rem; opacity:.8; margin-top:2px; }
</style>
@endpush

@section('content')
<div class="container py-4" style="max-width:820px;">
    <a href="{{ route('member.consultations.index') }}" class="text-decoration-none small">
        &larr; Kembali ke Konsultasi
    </a>

    <div class="card border-0 shadow-sm rounded-4 mt-3 mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-bold" style="color:var(--vg-dark,#3a2230);">
                    {{ $consultation->booking->doctor->user->name }}
                </div>
                <small class="text-muted">
                    {{ $consultation->booking->doctor->specialty }}
                </small>
            </div>

            @if($consultation->status === 'active')
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                    Berlangsung
                </span>
            @else
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                    Selesai
                </span>
            @endif
        </div>
    </div>

    @if($consultation->status === 'closed')
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-1">Ringkasan Konsultasi</h6>
                <p class="mb-2">{{ $consultation->summary ?? '-' }}</p>

                @if($consultation->diagnosis)
                    <h6 class="fw-bold mb-1">Diagnosis</h6>
                    <p class="mb-0">{{ $consultation->diagnosis }}</p>
                @endif
            </div>
        </div>
    @endif

    <div class="chat-box mb-3"
         id="chatBox"
         data-fetch-url="{{ route('member.consultations.messages.fetch', $consultation) }}"
         data-last-id="{{ $consultation->messages->last()->id ?? 0 }}">

        @forelse($consultation->messages as $message)
            @php
                $mine = $message->sender_id === auth()->id();
            @endphp

            <div class="chat-bubble {{ $mine ? 'chat-mine' : 'chat-theirs' }}">
                <div>{{ $message->message }}</div>
                <div class="chat-meta">
                    {{ $mine ? 'Anda' : $message->sender->name }}
                    &middot;
                    {{ $message->created_at->format('H:i') }}
                </div>
            </div>
        @empty
            <p class="text-center text-muted my-5">
                Belum ada pesan. Sampaikan keluhanmu untuk memulai.
            </p>
        @endforelse
    </div>

    @if($consultation->status === 'active')
        <form id="chatForm"
              class="d-flex gap-2"
              data-store-url="{{ route('member.consultations.messages.store', $consultation) }}">
            @csrf

            <input type="text"
                   id="chatInput"
                   name="message"
                   class="form-control rounded-pill"
                   placeholder="Tulis pesan..."
                   required
                   autocomplete="off">

            <button type="submit"
                    id="chatSend"
                    class="btn text-white rounded-pill px-4"
                    style="background:var(--vg-primary,#e06483);">
                Kirim
            </button>
        </form>

        <small id="chatError" class="text-danger d-block mt-1"></small>
    @else
        <div class="alert alert-secondary text-center mb-0">
            Konsultasi sudah ditutup. Anda tidak dapat mengirim pesan.
        </div>
    @endif
</div>

<script>
(function () {
    const box = document.getElementById('chatBox');

    if (!box) return;

    box.scrollTop = box.scrollHeight;

    let lastId = parseInt(box.dataset.lastId || '0');

    const token =
        document.querySelector('input[name="_token"]')?.value ||
        document.querySelector('meta[name="csrf-token"]')?.content;

    function append(m) {
        const empty = box.querySelector('p.text-muted');

        if (empty) empty.remove();

        const div = document.createElement('div');

        div.className =
            'chat-bubble ' +
            (m.mine ? 'chat-mine' : 'chat-theirs');

        const body = document.createElement('div');
        body.textContent = m.message;

        const meta = document.createElement('div');
        meta.className = 'chat-meta';
        meta.textContent =
            (m.sender || '') +
            ' · ' +
            (m.time || '');

        div.appendChild(body);
        div.appendChild(meta);

        box.appendChild(div);

        box.scrollTop = box.scrollHeight;

        if (m.id > lastId) {
            lastId = m.id;
        }
    }

    async function poll() {
        try {
            const res = await fetch(
                box.dataset.fetchUrl,
                {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            if (!res.ok) return;

            const data = await res.json();

            (data.messages || []).forEach(m => {
                if (m.id > lastId) {
                    append(m);
                }
            });

            if (data.status === 'closed') {
                location.reload();
            }

        } catch (e) {
            console.log(e);
        }
    }

    setInterval(poll, 4000);

    const form = document.getElementById('chatForm');

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const input = document.getElementById('chatInput');
            const text = input.value.trim();

            if (!text) return;

            const btn = document.getElementById('chatSend');

            btn.disabled = true;

            try {
                const res = await fetch(
                    form.dataset.storeUrl,
                    {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            message: text
                        })
                    }
                );

                if (res.ok) {
                    const m = await res.json();

                    append({
                        ...m,
                        mine: true,
                        sender: 'Anda'
                    });

                    input.value = '';

                    document.getElementById('chatError').textContent = '';
                } else {
                    const err = await res.json().catch(() => ({}));

                    document.getElementById('chatError').textContent =
                        err.message || 'Gagal mengirim pesan.';
                }

            } catch (e) {
                document.getElementById('chatError').textContent =
                    'Gagal terhubung ke server.';
            } finally {
                btn.disabled = false;
                input.focus();
            }
        });
    }
})();
</script>
@endsection