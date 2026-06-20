<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class MemberDoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::with('user')
            ->where('status', 'active')
            ->when($request->filled('search'), function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('user', function ($subQ) {
                        $subQ->where('name', 'like', '%' . request('search') . '%');
                    })->orWhere('specialty', 'like', '%' . request('search') . '%');
                });
            })
            ->when($request->filled('specialty'), function ($query) {
                $query->where('specialty', request('specialty'));
            })
            ->latest()
            ->paginate(9);

        $specialties = Doctor::where('status', 'active')->distinct()->pluck('specialty');

        return view('member.doctors.index', compact('doctors', 'specialties'));
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('user');

        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('status', 'available')
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('member.doctors.show', compact('doctor', 'schedules'));
    }
}
