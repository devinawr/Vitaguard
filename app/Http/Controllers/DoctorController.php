<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $doctors = Doctor::query()
            ->with('user')
            ->when($request->filled('specialty'), fn ($query) => $query->where('specialty', 'like', "%{$request->specialty}%"))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return response()->json($doctors);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('role', 'doctor')
                    ->whereNull('deleted_at')),
                'unique:doctors,user_id',
            ],
            'specialty' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255', 'unique:doctors,license_number'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'bio' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:15'],
            'photo' => ['nullable', 'string', 'max:255'],
            'rating' => ['nullable', 'numeric', 'between:0,5'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['Woman', 'Man'])],
        ]);

        $doctor = Doctor::create($validated);

        return response()->json([
            'message' => 'Data dokter berhasil dibuat.',
            'data' => $doctor->load('user'),
        ], 201);
    }

    public function show(Doctor $doctor): JsonResponse
    {
        return response()->json([
            'data' => $doctor->load(['user', 'schedules', 'bookings.member.user']),
        ]);
    }

    public function update(Request $request, Doctor $doctor): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'sometimes',
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('role', 'doctor')
                    ->whereNull('deleted_at')),
                Rule::unique('doctors', 'user_id')->ignore($doctor->id),
            ],
            'specialty' => ['sometimes', 'required', 'string', 'max:255'],
            'license_number' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('doctors', 'license_number')->ignore($doctor->id),
            ],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'bio' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:15'],
            'photo' => ['nullable', 'string', 'max:255'],
            'rating' => ['nullable', 'numeric', 'between:0,5'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['Woman', 'Man'])],
        ]);

        $doctor->update($validated);

        return response()->json([
            'message' => 'Data dokter berhasil diperbarui.',
            'data' => $doctor->fresh()->load('user'),
        ]);
    }

    public function destroy(Doctor $doctor): JsonResponse
    {
        $doctor->delete();

        return response()->json([
            'message' => 'Data dokter berhasil dihapus secara soft delete.',
        ]);
    }
}
