<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Consultation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ConsultationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $consultations = Consultation::query()
            ->with([
                'booking.member.user:id,name,email',
                'booking.doctor.user:id,name,email',
            ])
            ->withCount('messages')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return response()->json($consultations);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'exists:bookings,id', 'unique:consultations,booking_id'],
            'started_at' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['active', 'closed'])],
            'summary' => ['nullable', 'string'],
            'diagnosis' => ['nullable', 'string'],
        ]);

        $consultation = DB::transaction(function () use ($validated) {
            $booking = Booking::lockForUpdate()->findOrFail($validated['booking_id']);

            abort_if($booking->status === 'cancelled', 422, 'Booking yang dibatalkan tidak dapat dikonsultasikan.');

            $validated['started_at'] ??= now();
            $validated['status'] ??= 'active';

            $consultation = Consultation::create($validated);
            $booking->update(['status' => $validated['status'] === 'closed' ? 'completed' : 'ongoing']);

            return $consultation;
        });

        return response()->json([
            'message' => 'Konsultasi berhasil dimulai.',
            'data' => $consultation->load(['booking.member.user', 'booking.doctor.user']),
        ], 201);
    }

    public function show(Consultation $consultation): JsonResponse
    {
        return response()->json([
            'data' => $consultation->load([
                'booking.member.user:id,name,email',
                'booking.doctor.user:id,name,email',
                'messages.sender:id,name,role',
                'participants:id,name,role',
            ]),
        ]);
    }

    public function update(Request $request, Consultation $consultation): JsonResponse
    {
        $validated = $request->validate([
            'started_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'status' => ['sometimes', 'required', Rule::in(['active', 'closed'])],
            'summary' => ['nullable', 'string'],
            'diagnosis' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($consultation, $validated) {
            if (($validated['status'] ?? null) === 'closed') {
                $validated['ended_at'] ??= now();
                $consultation->booking()->update(['status' => 'completed']);
            }

            $consultation->update($validated);
        });

        return response()->json([
            'message' => 'Konsultasi berhasil diperbarui.',
            'data' => $consultation->fresh()->load([
                'booking.member.user:id,name,email',
                'booking.doctor.user:id,name,email',
                'messages.sender:id,name,role',
            ]),
        ]);
    }

    public function destroy(Consultation $consultation): JsonResponse
    {
        return response()->json([
            'message' => 'Riwayat konsultasi tidak boleh dihapus. Tutup konsultasi dengan status closed.',
        ], 403);
    }
}
