<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminDoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $doctors = Doctor::with('user')
            ->when($request->filled('search'), function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%')
                      ->orWhere('email', 'like', '%' . request('search') . '%');
                })->orWhere('specialty', 'like', '%' . request('search') . '%');
            })
            ->when($request->filled('specialty'), function ($query) {
                $query->where('specialty', $request->specialty);
            })
            ->when($request->filled('status'), function ($query) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10);

        $specialties = Doctor::distinct('specialty')->pluck('specialty');

        return view('admin.doctors.index', compact('doctors', 'specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'specialty' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255', 'unique:doctors,license_number'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'bio' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:15'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'status' => ['nullable', 'in:active,inactive'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:Male,Female'],
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('doctors', 'public');
        }

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'doctor',
        ]);

        // Create doctor
        Doctor::create([
            'user_id' => $user->id,
            'specialty' => $validated['specialty'],
            'license_number' => $validated['license_number'],
            'experience_years' => $validated['experience_years'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'photo' => $photoPath,
            'status' => $validated['status'] ?? 'active',
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Data dokter berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'schedules', 'bookings']);
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email'],
            'specialty' => ['sometimes', 'required', 'string', 'max:255'],
            'license_number' => ['sometimes', 'required', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'bio' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:15'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'status' => ['nullable', 'in:active,inactive'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:Male,Female'],
        ]);

        // Update user data
        if (isset($validated['name']) || isset($validated['email'])) {
            $doctor->user->update([
                'name' => $validated['name'] ?? $doctor->user->name,
                'email' => $validated['email'] ?? $doctor->user->email,
            ]);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($doctor->photo && Storage::disk('public')->exists($doctor->photo)) {
                Storage::disk('public')->delete($doctor->photo);
            }
            $validated['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        // Update doctor data
        $doctorData = collect($validated)->except(['name', 'email'])->toArray();
        $doctor->update($doctorData);

        return redirect()->route('admin.doctors.show', $doctor)
            ->with('success', 'Data dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        if ($doctor->photo && Storage::disk('public')->exists($doctor->photo)) {
            Storage::disk('public')->delete($doctor->photo);
        }

        $doctor->user->delete();
        $doctor->delete();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Data dokter berhasil dihapus.');
    }
}
