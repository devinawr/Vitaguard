<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberConsultationController extends Controller
{
    private function member()
    {
        $member = auth()->user()->member;
        abort_if(! $member, 403, 'Profil member tidak ditemukan.');

        return $member;
    }

    public function index()
    {
        $member = $this->member();

        $consultations = Consultation::with(['booking.doctor.user'])
            ->whereHas('booking', fn ($q) => $q->where('member_id', $member->id))
            ->withCount('messages')
            ->latest()
            ->paginate(10);

        // Booking yang sudah dikonfirmasi tetapi belum punya konsultasi.
        $startable = Booking::with('doctor.user')
            ->where('member_id', $member->id)
            ->where('status', 'confirmed')
            ->whereDoesntHave('consultation')
            ->latest()
            ->get();

        return view('member.consultations.index', compact('consultations', 'startable'));
    }

    public function start(Booking $booking)
    {
        $member = $this->member();
        abort_if($booking->member_id !== $member->id, 403);

        if (! in_array($booking->status, ['confirmed', 'ongoing'])) {
            return back()->with('error', 'Konsultasi hanya bisa dimulai untuk booking yang sudah dikonfirmasi.');
        }

        $consultation = DB::transaction(function () use ($booking) {
            $consultation = Consultation::firstOrCreate(
                ['booking_id' => $booking->id],
                ['started_at' => now(), 'status' => 'active'],
            );

            if ($booking->status === 'confirmed') {
                $booking->update(['status' => 'ongoing']);
            }

            return $consultation;
        });

        return redirect()
            ->route('member.consultations.show', $consultation)
            ->with('success', 'Konsultasi dimulai. Silakan sampaikan keluhanmu ke dokter.');
    }

    /** Ruang chat + detail konsultasi. */
    public function show(Consultation $consultation)
    {
        $member = $this->member();
        $consultation->load(['booking.doctor.user', 'booking.member.user', 'messages.sender']);
        abort_if($consultation->booking->member_id !== $member->id, 403);

        // Tandai pesan dari dokter sebagai sudah dibaca.
        $consultation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->update(['read_at' => now()]);

        return view('member.consultations.show', compact('consultation'));
    }

    /** Kirim pesan ke dokter. */
    public function storeMessage(Request $request, Consultation $consultation)
    {
        $member = $this->member();
        abort_if($consultation->booking->member_id !== $member->id, 403);
        abort_if($consultation->status !== 'active', 422, 'Konsultasi sudah ditutup.');

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $message = $consultation->messages()->create([
            'sender_id' => auth()->id(),
            'message'   => $validated['message'],
        ]);
        if ($request->expectsJson()) {
            return response()->json([
                'id'      => $message->id,
                'message' => $message->message,
                'time'    => optional($message->created_at)->format('d M Y, H:i'),
            ]);
        }
        return back();
    }
    public function fetchMessages(Consultation $consultation)
    {
        $member = $this->member();
        abort_if($consultation->booking->member_id !== $member->id, 403);

        $consultation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->update(['read_at' => now()]);

        $messages = $consultation->messages()
            ->with('sender')
            ->orderBy('id')
            ->get()
            ->map(fn ($m) => [
                'id'      => $m->id,
                'message' => $m->message,
                'time'    => optional($m->created_at)->format('d M Y, H:i'),
                'mine'    => $m->sender_id === auth()->id(),
                'sender'  => $m->sender->name ?? '-',
            ]);

        return response()->json([
            'messages' => $messages,
            'status'   => $consultation->status,
        ]);
    }
}
