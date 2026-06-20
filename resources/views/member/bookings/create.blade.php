@extends('layouts.member')

@section('title', 'Buat Booking - VitaGuard')

@section('content')
<div class="container py-5">

    <div class="mb-3">
        <a href="{{ route('member.doctors.show', $doctor) }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Profil Dokter
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Error --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Doctor Summary --}}
            <div class="card border-0 rounded-4 mb-4" style="box-shadow:0 4px 18px rgba(58,34,48,0.07);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        @if($doctor->photo)
                            <img src="{{ Storage::url($doctor->photo) }}" alt=""
                                 class="rounded-circle"
                                 style="width:56px;height:56px;object-fit:cover;border:2px solid #fdeef1;">
                        @else
                            <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#ef8aa0,#e06483);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h6 class="fw-bold mb-0" style="color:#2d2230;">{{ $doctor->user->name }}</h6>
                            <small class="text-vg-primary fw-semibold">{{ $doctor->specialty }}</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Booking Form --}}
            <div class="card border-0 rounded-4" style="box-shadow:0 4px 18px rgba(58,34,48,0.07);">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4" style="color:#2d2230;">Form Booking Konsultasi</h5>

                    <form action="{{ route('member.bookings.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                        {{-- Schedule Selection --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Pilih Jadwal <span class="text-danger">*</span>
                            </label>

                            @forelse($schedules as $schedule)
                                <div class="mb-2">
                                    <input class="form-check-input visually-hidden"
                                           type="radio"
                                           name="schedule_id"
                                           value="{{ $schedule->id }}"
                                           id="schedule_{{ $schedule->id }}"
                                           {{ old('schedule_id', request('schedule_id')) == $schedule->id ? 'checked' : '' }}
                                           required>
                                    <label for="schedule_{{ $schedule->id }}"
                                           class="d-block p-3 rounded-3 border schedule-card"
                                           style="cursor:pointer;transition:all 0.2s;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="bi bi-calendar3 text-vg-primary me-2"></i>
                                                <span class="fw-semibold" style="color:#2d2230;">
                                                    {{ $schedule->date->format('d M Y') }}
                                                </span>
                                                <span class="text-muted ms-2">
                                                    {{ substr($schedule->start_time, 0, 5) }} – {{ substr($schedule->end_time, 0, 5) }} WIB
                                                </span>
                                            </div>
                                            <span class="badge rounded-pill bg-success">Tersedia</span>
                                        </div>
                                    </label>
                                </div>
                            @empty
                                <div class="alert alert-warning rounded-3">
                                    <i class="bi bi-calendar-x me-2"></i>
                                    Tidak ada jadwal tersedia untuk dokter ini saat ini.
                                </div>
                            @endforelse

                            @error('schedule_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Complaint --}}
                        <div class="mb-3">
                            <label for="complaint" class="form-label fw-semibold">
                                Keluhan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control rounded-3 @error('complaint') is-invalid @enderror"
                                      id="complaint" name="complaint" rows="4"
                                      placeholder="Jelaskan keluhan atau gejala yang kamu rasakan...">{{ old('complaint') }}</textarea>
                            @error('complaint')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold">
                                Catatan Tambahan <span class="text-muted fw-normal">(opsional)</span>
                            </label>
                            <textarea class="form-control rounded-3 @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="3"
                                      placeholder="Riwayat penyakit, alergi obat, atau informasi lain yang perlu diketahui dokter...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($schedules->count() > 0)
                            <button type="submit" class="btn btn-vg-primary w-100 rounded-pill py-2 fw-semibold">
                                <i class="bi bi-check-circle me-2"></i>Konfirmasi Booking
                            </button>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[name="schedule_id"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.schedule-card').forEach(function(card) {
                card.style.borderColor = '#dee2e6';
                card.style.background = '#fff';
            });
            if (this.checked) {
                var label = document.querySelector('label[for="' + this.id + '"]');
                label.style.borderColor = '#ef8aa0';
                label.style.background = '#fdeef1';
            }
        });
    });

    var checked = document.querySelector('input[name="schedule_id"]:checked');
    if (checked) {
        var label = document.querySelector('label[for="' + checked.id + '"]');
        label.style.borderColor = '#ef8aa0';
        label.style.background = '#fdeef1';
    }
</script>
@endpush
