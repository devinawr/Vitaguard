@extends('layouts.member')

@section('title', 'Profil dr. {{ $doctor->user->name }} - VitaGuard')

@section('content')
<div class="container py-5">

    {{-- Back --}}
    <div class="mb-3">
        <a href="{{ route('member.doctors.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Direktori
        </a>
    </div>

    <div class="row g-4">

        {{-- Left: Profile Card --}}
        <div class="col-lg-4">
            <div class="card border-0 rounded-4 text-center p-4" style="box-shadow:0 4px 18px rgba(58,34,48,0.08);">
                @if($doctor->photo)
                    <img src="{{ Storage::url($doctor->photo) }}"
                         alt="{{ $doctor->user->name }}"
                         class="rounded-circle mx-auto d-block mb-3"
                         style="width:110px;height:110px;object-fit:cover;border:3px solid #fdeef1;">
                @else
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                         style="width:110px;height:110px;background:linear-gradient(135deg,#ef8aa0,#e06483);color:#fff;font-size:2.5rem;font-weight:700;">
                        {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                    </div>
                @endif

                <h5 class="fw-bold mb-0" style="color:#2d2230;">{{ $doctor->user->name }}</h5>
                <p class="text-vg-primary fw-semibold mb-2">{{ $doctor->specialty }}</p>

                <span class="badge rounded-pill bg-success mb-3">Aktif</span>

                <div class="d-flex justify-content-center gap-3 mb-4">
                    @if($doctor->experience_years)
                        <div class="text-center">
                            <div class="fw-bold" style="color:#2d2230;font-size:1.15rem;">{{ $doctor->experience_years }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">Tahun<br>Pengalaman</div>
                        </div>
                    @endif
                    @if($doctor->rating)
                        <div class="text-center">
                            <div class="fw-bold" style="color:#2d2230;font-size:1.15rem;">
                                <i class="bi bi-star-fill text-warning"></i> {{ number_format($doctor->rating, 1) }}
                            </div>
                            <div class="text-muted" style="font-size:0.75rem;">Rating</div>
                        </div>
                    @endif
                </div>

                @if($schedules->count() > 0)
                    <a href="{{ route('member.bookings.create', $doctor) }}" class="btn btn-vg-primary w-100 rounded-pill">
                        <i class="bi bi-calendar-plus me-1"></i> Buat Booking
                    </a>
                @else
                    <button class="btn btn-secondary w-100 rounded-pill" disabled>
                        <i class="bi bi-calendar-x me-1"></i> Jadwal Tidak Tersedia
                    </button>
                @endif

                {{-- Contact Info --}}
                @if($doctor->phone)
                    <hr class="my-3">
                    <div class="text-start">
                        <small class="text-muted"><i class="bi bi-telephone me-2"></i>{{ $doctor->phone }}</small>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Details --}}
        <div class="col-lg-8">

            @if($doctor->bio)
                <div class="card border-0 rounded-4 mb-4" style="box-shadow:0 4px 18px rgba(58,34,48,0.07);">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color:#2d2230;">
                            <i class="bi bi-person-lines-fill text-vg-primary me-2"></i>Tentang Dokter
                        </h6>
                        <p class="text-muted mb-0" style="line-height:1.8;">{{ $doctor->bio }}</p>
                    </div>
                </div>
            @endif

            {{-- Professional Info --}}
            <div class="card border-0 rounded-4 mb-4" style="box-shadow:0 4px 18px rgba(58,34,48,0.07);">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3" style="color:#2d2230;">
                        <i class="bi bi-award text-vg-primary me-2"></i>Informasi Profesional
                    </h6>
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width:40%;">Spesialis</td>
                            <td class="fw-semibold">{{ $doctor->specialty }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. Lisensi</td>
                            <td>{{ $doctor->license_number }}</td>
                        </tr>
                        @if($doctor->experience_years)
                            <tr>
                                <td class="text-muted">Pengalaman</td>
                                <td>{{ $doctor->experience_years }} tahun</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Available Schedules --}}
            <div class="card border-0 rounded-4" style="box-shadow:0 4px 18px rgba(58,34,48,0.07);">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3" style="color:#2d2230;">
                        <i class="bi bi-calendar3 text-vg-primary me-2"></i>Jadwal Tersedia
                    </h6>

                    @forelse($schedules as $schedule)
                        <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded-3"
                             style="background:#fdeef1;border:1px solid rgba(239,138,160,0.2);">
                            <div>
                                <span class="fw-semibold" style="color:#2d2230;">
                                    {{ $schedule->date->format('d M Y') }}
                                </span>
                                <span class="text-muted ms-2 small">
                                    {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                </span>
                            </div>
                            <a href="{{ route('member.bookings.create', $doctor) }}?schedule_id={{ $schedule->id }}"
                               class="btn btn-sm btn-vg-primary rounded-pill">
                                Pilih
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-calendar-x d-block mb-1" style="font-size:1.8rem;"></i>
                            Belum ada jadwal tersedia
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
