<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $schedules = DoctorSchedule::query()
            ->with('doctor.user:id,name,email')
            ->when($request->filled('doctor_id'), fn ($query) => $query->where('doctor_id', $request->doctor_id))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('date', $request->date))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate($request->integer('per_page', 10));

        return response()->json($schedules);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'status' => ['nullable', Rule::in(['available', 'booked'])],
        ]);

        $this->ensureScheduleDoesNotOverlap($validated);

        $schedule = DoctorSchedule::create($validated);

        return response()->json([
            'message' => 'Jadwal dokter berhasil dibuat.',
            'data' => $schedule->load('doctor.user:id,name,email'),
        ], 201);
    }

    public function show(DoctorSchedule $doctorSchedule): JsonResponse
    {
        return response()->json([
            'data' => $doctorSchedule->load(['doctor.user:id,name,email', 'bookings']),
        ]);
    }

    public function update(Request $request, DoctorSchedule $doctorSchedule): JsonResponse
    {
        $validated = $request->validate([
            'doctor_id' => ['sometimes', 'required', 'exists:doctors,id'],
            'date' => ['sometimes', 'required', 'date'],
            'start_time' => ['sometimes', 'required', 'date_format:H:i'],
            'end_time' => ['sometimes', 'required', 'date_format:H:i'],
            'status' => ['sometimes', 'required', Rule::in(['available', 'booked'])],
        ]);

        $merged = array_merge($doctorSchedule->only([
            'doctor_id',
            'date',
            'start_time',
            'end_time',
            'status',
        ]), $validated);

        if ($merged['end_time'] <= $merged['start_time']) {
            return response()->json([
                'message' => 'end_time harus lebih besar daripada start_time.',
            ], 422);
        }

        $this->ensureScheduleDoesNotOverlap($merged, $doctorSchedule->id);

        $doctorSchedule->update($validated);

        return response()->json([
            'message' => 'Jadwal dokter berhasil diperbarui.',
            'data' => $doctorSchedule->fresh()->load('doctor.user:id,name,email'),
        ]);
    }

    public function destroy(DoctorSchedule $doctorSchedule): JsonResponse
    {
        if ($doctorSchedule->bookings()->exists()) {
            return response()->json([
                'message' => 'Jadwal tidak dapat dihapus karena sudah memiliki booking.',
            ], 409);
        }

        $doctorSchedule->delete();

        return response()->json([
            'message' => 'Jadwal dokter berhasil dihapus.',
        ]);
    }

    private function ensureScheduleDoesNotOverlap(array $data, ?int $ignoreId = null): void
    {
        $overlap = DoctorSchedule::query()
            ->where('doctor_id', $data['doctor_id'])
            ->whereDate('date', $data['date'])
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->exists();

        abort_if($overlap, 422, 'Jadwal dokter bertabrakan dengan jadwal lain.');
    }
}
