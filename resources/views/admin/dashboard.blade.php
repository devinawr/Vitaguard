@extends('layouts.admin')

@section('title', 'Dashboard Admin | VitaGuard')
@section('page-title', 'Dashboard')

@section('content')

<div class="row">

    <!-- Member -->
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card bg-stat-blue">
            <div class="card-body">
                <div class="stat-content">
                    <div>
                        <h3 class="stat-title">Member</h3>
                        <p class="stat-desc">Kelola data member</p>
                    </div>

                    <div class="stat-side">
                        <i class="bi bi-people-fill stat-icon-small"></i>
                        <div class="stat-number">{{ $data['total_member'] }}</div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.members.index') }}" class="stat-link">
                    <span>Lihat data</span>
                    <i class="bi bi-info-circle-fill"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Dokter -->
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card bg-stat-green">
            <div class="card-body">
                <div class="stat-content">
                    <div>
                        <h3 class="stat-title">Dokter</h3>
                        <p class="stat-desc">Kelola data dokter</p>
                    </div>

                    <div class="stat-side">
                        <i class="bi bi-person-badge stat-icon-small"></i>
                        <div class="stat-number">{{ $data['total_dokter'] }}</div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.doctors.index') }}" class="stat-link">
                    <span>Lihat data</span>
                    <i class="bi bi-info-circle-fill"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Artikel -->
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card bg-stat-red">
            <div class="card-body">
                <div class="stat-content">
                    <div>
                        <h3 class="stat-title">Artikel</h3>
                        <p class="stat-desc">Artikel kesehatan</p>
                    </div>

                    <div class="stat-side">
                        <i class="bi bi-journal-text stat-icon-small"></i>
                        <div class="stat-number">{{ $data['total_artikel'] }}</div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.articles.index') }}" class="stat-link">
                    <span>Lihat data</span>
                    <i class="bi bi-info-circle-fill"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Booking -->
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card bg-stat-yellow">
            <div class="card-body">
                <div class="stat-content">
                    <div>
                        <h3 class="stat-title">Booking</h3>
                        <p class="stat-desc">Booking konsultasi</p>
                    </div>

                    <div class="stat-side">
                        <i class="bi bi-calendar-check-fill stat-icon-small"></i>
                        <div class="stat-number">{{ $data['total_booking'] }}</div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.bookings.index') }}" class="stat-link">
                    <span>Lihat data</span>
                    <i class="bi bi-info-circle-fill"></i>
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Konsultasi -->
<div class="row mt-4">

    <div class="col-lg-6 col-md-6">
        <div class="card stat-card bg-stat-purple">
            <div class="card-body">
                <div class="stat-content">
                    <div>
                        <h3 class="stat-title">Konsultasi Aktif</h3>
                        <p class="stat-desc">Konsultasi yang berlangsung</p>
                    </div>

                    <div class="stat-side">
                        <i class="bi bi-chat-dots-fill stat-icon-small"></i>
                        <div class="stat-number">{{ $data['consultations_active'] }}</div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.consultations.index') }}" class="stat-link">
                    <span>Lihat data</span>
                    <i class="bi bi-info-circle-fill"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6">
        <div class="card stat-card bg-stat-dark">
            <div class="card-body">
                <div class="stat-content">
                    <div>
                        <h3 class="stat-title">Konsultasi Selesai</h3>
                        <p class="stat-desc">Konsultasi yang selesai</p>
                    </div>

                    <div class="stat-side">
                        <i class="bi bi-check-circle-fill stat-icon-small"></i>
                        <div class="stat-number">{{ $data['consultations_closed'] }}</div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.consultations.index') }}" class="stat-link">
                    <span>Lihat data</span>
                    <i class="bi bi-info-circle-fill"></i>
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Grafik + Tabel Konsultasi Aktif -->
<div class="row mt-4">

    <div class="col-lg-6">
        <div class="card dashboard-table-card">
            <div class="card-header">
                <h5 class="mb-0">Statistik Aktivitas Sistem</h5>
            </div>

            <div class="card-body">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card dashboard-table-card">
            <div class="card-header">
                <h5 class="mb-0">Konsultasi Aktif</h5>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Dokter</th>
                            <th>Mulai</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data['active_consultations'] as $consultation)
                            <tr>
                                <td>{{ $consultation->booking->member->user->name ?? '-' }}</td>
                                <td>{{ $consultation->booking->doctor->user->name ?? '-' }}</td>
                                <td>
                                    {{ $consultation->started_at ? \Carbon\Carbon::parse($consultation->started_at)->format('d M Y') : '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    Tidak ada konsultasi aktif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Booking dan Artikel Terbaru -->
<div class="row mt-4">

    <div class="col-lg-6">
        <div class="card dashboard-table-card">
            <div class="card-header">
                <h5 class="mb-0">Booking Terbaru</h5>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Dokter</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data['recent_bookings'] as $booking)
                            <tr>
                                <td>{{ $booking->member->user->name ?? '-' }}</td>
                                <td>{{ $booking->doctor->user->name ?? '-' }}</td>
                                <td>{{ ucfirst($booking->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    Tidak ada booking terbaru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card dashboard-table-card">
            <div class="card-header">
                <h5 class="mb-0">Artikel Terbaru</h5>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Penulis</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data['recent_articles'] as $article)
                            <tr>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->author->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">
                                    Tidak ada artikel terbaru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
    .stat-card {
        min-height: 150px;
        color: white;
        border: none;
        overflow: hidden;
    }

    .stat-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 85px;
    }

    .stat-title {
        font-size: 1.7rem;
        font-weight: 700;
        margin: 0 0 6px 0;
        color: white;
    }

    .stat-desc {
        margin: 0;
        font-size: 0.95rem;
        opacity: 0.9;
        color: white;
    }

    .stat-side {
        min-width: 72px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .stat-icon-small {
        font-size: 2rem;
        opacity: 0.75;
        line-height: 1;
        margin-bottom: 8px;
    }

    .stat-number {
        font-size: 1.7rem;
        font-weight: 800;
        line-height: 1;
        color: white;
    }

    .stat-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
        text-decoration: none;
    }

    .stat-link:hover {
        color: white;
        text-decoration: underline;
    }

    .bg-stat-purple {
        background: #6f42c1;
    }

    .bg-stat-dark {
        background: #343a40;
    }

    .dashboard-table-card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .dashboard-table-card .card-header {
        background: white;
        font-weight: 700;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('activityChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Booking Masuk', 'Konsultasi Selesai'],
            datasets: [{
                label: 'Jumlah',
                data: [
                    {{ $data['total_booking'] }},
                    {{ $data['consultations_closed'] }}
                ],
                backgroundColor: [
                    '#ffc107',
                    '#343a40'
                ],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>

@endsection