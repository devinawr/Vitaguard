<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\DoctorSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $bookings = Booking::query()
            ->with(['member.user:id,name,email', 'doctor.user:id,name,email', 'schedule', 'consultation'])
            ->when($request->filled('member_id'), fn ($query) => $query->where('member_id', $request->member_id))
            ->when($request->filled('doctor_id'), fn ($query) => $query->where('doctor_id', $request->doctor_id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return response()->json($bookings);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'schedule_id' => ['nullable', 'exists:doctor_schedules,id'],
            'consultation_date' => ['nullable', 'date'],
            'complaint' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(['pending', 'confirmed', 'ongoing', 'completed', 'cancelled'])],
        ]);

        $booking = DB::transaction(function () use ($validated) {
            if (!empty($validated['schedule_id'])) {
                $schedule = DoctorSchedule::lockForUpdate()->findOrFail($validated['schedule_id']);

                abort_if($schedule->doctor_id !== (int) $validated['doctor_id'], 422, 'Jadwal tidak milik dokter yang dipilih.');
                abort_if($schedule->status !== 'available', 422, 'Jadwal dokter sudah tidak tersedia.');

                $validated['consultation_date'] ??= $schedule->date->format('Y-m-d').' '.$schedule->start_time;
                $schedule->update(['status' => 'booked']);
            }

            $validated['booking_code'] = $this->generateBookingCode();

            return Booking::create($validated);
        });

        return response()->json([
            'message' => 'Booking berhasil dibuat.',
            'data' => $booking->load(['member.user:id,name,email', 'doctor.user:id,name,email', 'schedule']),
        ], 201);
    }

    public function show(Booking $booking): JsonResponse
    {
        return response()->json([
            'data' => $booking->load([
                'member.user:id,name,email',
                'doctor.user:id,name,email',
                'schedule',
                'consultation.messages.sender:id,name,role',
            ]),
        ]);
    }

    public function update(Request $request, Booking $booking): JsonResponse
    {
        $validated = $request->validate([
            'consultation_date' => ['nullable', 'date'],
            'complaint' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'status' => ['sometimes', 'required', Rule::in(['pending', 'confirmed', 'ongoing', 'completed', 'cancelled'])],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            if (($validated['status'] ?? null) === 'cancelled' && $booking->schedule_id) {
                $booking->schedule()->lockForUpdate()->first()?->update(['status' => 'available']);
            }

            $booking->update($validated);
        });

        return response()->json([
            'message' => 'Booking berhasil diperbarui.',
            'data' => $booking->fresh()->load(['member.user:id,name,email', 'doctor.user:id,name,email', 'schedule']),
        ]);
    }

    public function destroy(Booking $booking): JsonResponse
    {
        return response()->json([
            'message' => 'Riwayat booking tidak boleh dihapus. Ubah status menjadi cancelled bila diperlukan.',
        ], 403);
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'BKG-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }
}
