<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DoctorBookingController extends Controller
{
    public function index(Request $request)
    {
        $doctor = auth()->user()->doctor;

        if (!$doctor) {
            abort(403);
        }

        $bookings = Booking::with(['member.user', 'schedule'])
            ->where('doctor_id', $doctor->id)
            ->where('status', 'confirmed')
            ->latest()
            ->paginate(10);

        return view('doctor.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $doctor = auth()->user()->doctor;

        if (!$doctor || $booking->doctor_id !== $doctor->id) {
            abort(403);
        }

        $booking->load(['member.user', 'schedule', 'consultation']);

        return view('doctor.bookings.show', compact('booking'));
    }
}
