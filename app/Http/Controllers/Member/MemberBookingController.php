<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class MemberBookingController extends Controller
{
    public function index()
    {
        $member = auth()->user()->member;

        if (!$member) {
            return redirect()->route('member.welcome')->with('error', 'Profil member tidak ditemukan.');
        }

        $bookings = Booking::with(['doctor.user', 'schedule'])
            ->where('member_id', $member->id)
            ->latest()
            ->paginate(10);

        return view('member.bookings.index', compact('bookings'));
    }

    public function create(Doctor $doctor)
    {
        $doctor->load('user');

        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('status', 'available')
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('member.bookings.create', compact('doctor', 'schedules'));
    }

    public function store(Request $request)
    {
        $member = auth()->user()->member;

        if (!$member) {
            return redirect()->route('member.welcome')->with('error', 'Profil member tidak ditemukan.');
        }

        $validated = $request->validate([
            'doctor_id'   => ['required', 'exists:doctors,id'],
            'schedule_id' => ['required', 'exists:doctor_schedules,id'],
            'complaint'   => ['required', 'string', 'max:1000'],
            'notes'       => ['nullable', 'string', 'max:1000'],
        ]);

        $schedule = DoctorSchedule::findOrFail($validated['schedule_id']);

        if ($schedule->status !== 'available') {
            return back()->with('error', 'Jadwal yang dipilih sudah tidak tersedia. Silakan pilih jadwal lain.')->withInput();
        }

        $bookingCode = 'BKG-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        $booking = Booking::create([
            'booking_code'      => $bookingCode,
            'member_id'         => $member->id,
            'doctor_id'         => $validated['doctor_id'],
            'schedule_id'       => $validated['schedule_id'],
            'consultation_date' => $schedule->date,
            'complaint'         => $validated['complaint'],
            'notes'             => $validated['notes'] ?? null,
            'status'            => 'pending',
        ]);

        $schedule->update(['status' => 'booked']);

        return redirect()->route('member.bookings.show', $booking)
            ->with('success', 'Booking berhasil dibuat! Kode booking: ' . $bookingCode);
    }

    public function show(Booking $booking)
    {
        $member = auth()->user()->member;

        if (!$member || $booking->member_id !== $member->id) {
            abort(403);
        }

        $booking->load(['doctor.user', 'schedule', 'consultation']);

        return view('member.bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        $member = auth()->user()->member;

        if (!$member || $booking->member_id !== $member->id) {
            abort(403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Booking ini tidak dapat dibatalkan.');
        }

        if ($booking->schedule) {
            $booking->schedule->update(['status' => 'available']);
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('member.bookings.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
