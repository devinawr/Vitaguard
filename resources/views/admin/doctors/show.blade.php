@extends('layouts.admin')

@section('title', 'Detail Dokter')
@section('page-title', 'Detail Dokter')
@section('menu-doctors', 'active')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($doctor->photo)
                    <img src="{{ Storage::url($doctor->photo) }}" alt="" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <i class="bi bi-person-circle text-secondary" style="font-size: 6rem;"></i>
                @endif
                <h5 class="mt-3">{{ $doctor->user->name }}</h5>
                <p class="text-muted">{{ $doctor->specialty }}</p>
                @if($doctor->status === 'active')
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-danger">Tidak Aktif</span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Dokter</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <h5 class="mb-3">Data Personal</h5>
                <table class="table table-sm">
                    <tr>
                        <td style="width: 30%">Email</td>
                        <td>{{ $doctor->user->email }}</td>
                    </tr>
                    <tr>
                        <td>No. Telepon</td>
                        <td>{{ $doctor->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>
                            @if($doctor->gender === 'Man')
                                Laki-laki
                            @elseif($doctor->gender === 'Woman')
                                Perempuan
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td>{{ $doctor->date_of_birth ? $doctor->date_of_birth->format('d/m/Y') : '-' }}</td>
                    </tr>
                </table>

                <h5 class="mb-3 mt-4">Data Profesional</h5>
                <table class="table table-sm">
                    <tr>
                        <td style="width: 30%">No. Lisensi</td>
                        <td>{{ $doctor->license_number }}</td>
                    </tr>
                    <tr>
                        <td>Spesialis</td>
                        <td>{{ $doctor->specialty }}</td>
                    </tr>
                    <tr>
                        <td>Pengalaman</td>
                        <td>{{ $doctor->experience_years ?? '-' }} tahun</td>
                    </tr>
                    <tr>
                        <td>Rating</td>
                        <td>
                            @if($doctor->rating)
                                <i class="bi bi-star-fill text-warning"></i> {{ number_format($doctor->rating, 1) }}/5
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>

                @if($doctor->bio)
                    <h5 class="mb-3 mt-4">Biografi</h5>
                    <p>{{ $doctor->bio }}</p>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Jadwal Praktek</h3>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
                    <i class="bi bi-plus"></i> Tambah Jadwal
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctor->schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->date->format('d/m/Y') }}</td>
                                <td>{{ substr($schedule->start_time, 0, 5) }}</td>
                                <td>{{ substr($schedule->end_time, 0, 5) }}</td>
                                <td>
                                    @if($schedule->status === 'available')
                                        <span class="badge bg-success">Tersedia</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Sudah Dibooking</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->status === 'available')
                                        <form action="{{ route('admin.doctors.schedules.destroy', [$doctor, $schedule]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin hapus jadwal ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    Belum ada jadwal praktek
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($doctor->bookings->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Booking</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctor->bookings->take(5) as $booking)
                                <tr>
                                    <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $booking->member->user->name }}</td>
                                    <td>
                                        @if($booking->status === 'confirmed')
                                            <span class="badge bg-success">Dikonfirmasi</span>
                                        @elseif($booking->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="badge bg-secondary">Dibatalkan</span>
                                        @elseif($booking->status === 'completed')
                                            <span class="badge bg-info">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
{{-- Modal Tambah Jadwal --}}
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jadwal Praktek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.doctors.schedules.store', $doctor) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                               id="date" name="date"
                               value="{{ old('date') }}"
                               min="{{ now()->toDateString() }}">
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                   id="start_time" name="start_time" value="{{ old('start_time') }}">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                   id="end_time" name="end_time" value="{{ old('end_time') }}">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
@if($errors->has('date') || $errors->has('start_time') || $errors->has('end_time'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('modalTambahJadwal')).show();
    });
</script>
@endif
@endpush

@endsection
