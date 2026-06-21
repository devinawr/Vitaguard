<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Consultation;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;

        if (!$doctor) {
            abort(403, 'Profil dokter tidak ditemukan.');
        }

        $totalBookings     = Booking::where('doctor_id', $doctor->id)
            ->where('status', 'confirmed')->count();
            
        $confirmedBookings = $totalBookings;
        $todayBookings     = Booking::where('doctor_id', $doctor->id)
            ->where('status', 'confirmed')
            ->whereHas('schedule', fn($q) => $q->whereDate('date', today()))
            ->count();

        $recentBookings = Booking::with(['member.user', 'schedule'])
            ->where('doctor_id', $doctor->id)
            ->where('status', 'confirmed')
            ->latest()
            ->take(5)
            ->get();
        $recentConsultations = Consultation::with(['booking.member.user'])
            ->whereHas('booking', fn($q) => $q->where('doctor_id', $doctor->id))
            ->latest()
            ->take(5)
            ->get();

        return view('doctor.dashboard', compact(
            'doctor', 'totalBookings', 'confirmedBookings', 'todayBookings', 'recentBookings', 'recentConsultations'
        ));
    }
}
