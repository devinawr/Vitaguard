<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsultationMessageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $messages = ConsultationMessage::query()
            ->with([
                'sender:id,name,role',
                'consultation.booking.member.user:id,name,email',
                'consultation.booking.doctor.user:id,name,email',
            ])
            ->when($request->filled('consultation_id'), fn ($query) => $query->where('consultation_id', $request->consultation_id))
            ->when($request->filled('sender_id'), fn ($query) => $query->where('sender_id', $request->sender_id))
            ->when($request->boolean('unread'), fn ($query) => $query->whereNull('read_at'))
            ->orderBy('created_at')
            ->paginate($request->integer('per_page', 20));

        return response()->json($messages);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'consultation_id' => ['required', 'exists:consultations,id'],
            'sender_id' => ['required', 'exists:users,id'],
            'message' => ['required', 'string', 'max:5000'],
            'read_at' => ['nullable', 'date'],
        ]);

        $consultation = Consultation::with([
            'booking.member',
            'booking.doctor',
        ])->findOrFail($validated['consultation_id']);

        abort_if($consultation->status !== 'active', 422, 'Pesan hanya dapat dikirim pada konsultasi aktif.');

        $allowedSenderIds = [
            $consultation->booking->member->user_id,
            $consultation->booking->doctor->user_id,
        ];

        abort_unless(
            in_array((int) $validated['sender_id'], $allowedSenderIds, true),
            422,
            'Pengirim bukan dokter atau member pada konsultasi ini.'
        );

        /*
         * Menyimpan melalui custom pivot model. Relasi many-to-many-nya tersedia di:
         * Consultation::participants() dan User::consultations().
         */
        $message = ConsultationMessage::create($validated);

        return response()->json([
            'message' => 'Pesan konsultasi berhasil dikirim.',
            'data' => $message->load('sender:id,name,role'),
        ], 201);
    }

    public function show(ConsultationMessage $consultationMessage): JsonResponse
    {
        return response()->json([
            'data' => $consultationMessage->load([
                'sender:id,name,role',
                'consultation.booking.member.user:id,name,email',
                'consultation.booking.doctor.user:id,name,email',
            ]),
        ]);
    }

    public function update(Request $request, ConsultationMessage $consultationMessage): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['sometimes', 'required', 'string', 'max:5000'],
            'read_at' => ['nullable', 'date'],
        ]);

        $consultationMessage->update($validated);

        return response()->json([
            'message' => 'Pesan konsultasi berhasil diperbarui.',
            'data' => $consultationMessage->fresh()->load('sender:id,name,role'),
        ]);
    }

    public function destroy(ConsultationMessage $consultationMessage): JsonResponse
    {
        return response()->json([
            'message' => 'Pesan konsultasi tidak boleh dihapus agar riwayat percakapan tetap utuh.',
        ], 403);
    }
}
