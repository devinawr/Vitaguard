<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class AdminDoctorScheduleController extends Controller
{
    public function store(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'date'       => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $doctor->schedules()->create([
            'date'       => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time'   => $validated['end_time'],
            'status'     => 'available',
        ]);

        return redirect()->route('admin.doctors.show', $doctor)
            ->with('success', 'Jadwal praktek berhasil ditambahkan.');
    }

    public function destroy(Doctor $doctor, DoctorSchedule $schedule)
    {
        if ($schedule->doctor_id !== $doctor->id) {
            abort(403);
        }

        if ($schedule->status === 'booked') {
            return back()->with('error', 'Jadwal tidak dapat dihapus karena sudah ada booking aktif.');
        }

        $schedule->delete();

        return redirect()->route('admin.doctors.show', $doctor)
            ->with('success', 'Jadwal praktek berhasil dihapus.');
    }
}
