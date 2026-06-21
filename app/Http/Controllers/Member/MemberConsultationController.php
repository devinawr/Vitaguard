<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Modul Konsultasi Online & Riwayat Konsultasi (sisi Member) - bagian Dev.
 *
 * Member dapat:
 *  - memulai konsultasi dari booking yang sudah dikonfirmasi,
 *  - mengirim pesan & melihat balasan dokter (chat),
 *  - melihat riwayat konsultasi beserta ringkasan/diagnosis.
 */
class MemberConsultationController extends Controller
{
    /** Ambil profil member milik user yang sedang login. */
    private function member()
    {
        $member = auth()->user()->member;
        abort_if(! $member, 403, 'Profil member tidak ditemukan.');

        return $member;
    }

    /** Daftar riwayat konsultasi + booking yang siap dikonsultasikan. */
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

    /** Mulai konsultasi dari sebuah booking yang sudah dikonfirmasi. */
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

        $consultation->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        return back();
    }
}
