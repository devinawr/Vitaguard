<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorConsultationController extends Controller
{
    private function doctor()
    {
        $doctor = auth()->user()->doctor;
        abort_if(! $doctor, 403, 'Profil dokter tidak ditemukan.');

        return $doctor;
    }

    /** Daftar konsultasi aktif + riwayat konsultasi pasien. */
    public function index()
    {
        $doctor = $this->doctor();

        $base = Consultation::with(['booking.member.user'])
            ->whereHas('booking', fn ($q) => $q->where('doctor_id', $doctor->id))
            ->withCount('messages');

        $active = (clone $base)->where('status', 'active')->latest('started_at')->get();
        $history = (clone $base)->where('status', 'closed')->latest('ended_at')->paginate(10);

        return view('doctor.consultations.index', compact('active', 'history'));
    }

    /** Ruang chat + panel tutup konsultasi. */
    public function show(Consultation $consultation)
    {
        $doctor = $this->doctor();
        $consultation->load(['booking.member.user', 'booking.doctor.user', 'messages.sender']);
        abort_if($consultation->booking->doctor_id !== $doctor->id, 403);

        // Tandai pesan dari pasien sebagai sudah dibaca.
        $consultation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->update(['read_at' => now()]);

        return view('doctor.consultations.show', compact('consultation'));
    }

    /** Balas pesan pasien. */
        public function storeMessage(Request $request, Consultation $consultation)
    {
        $doctor = $this->doctor();
        abort_if($consultation->booking->doctor_id !== $doctor->id, 403);
        abort_if($consultation->status !== 'active', 422, 'Konsultasi sudah ditutup.');

        $validated = $request->validate(['message' => ['required', 'string', 'max:5000']]);

        $message = $consultation->messages()->create([
            'sender_id' => auth()->id(),
            'message'   => $validated['message'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'id'      => $message->id,
                'message' => $message->message,
                'time'    => optional($message->created_at)->format('H:i'),
            ]);
        }

        return back();
    }

    public function fetchMessages(Consultation $consultation)
    {
        $doctor = $this->doctor();
        abort_if($consultation->booking->doctor_id !== $doctor->id, 403);

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
                'time'    => optional($m->created_at)->format('H:i'),
                'mine'    => $m->sender_id === auth()->id(),
                'sender'  => $m->sender->name ?? '-',
            ]);

        return response()->json(['messages' => $messages, 'status' => $consultation->status]);
    }

    /** Tutup konsultasi dengan ringkasan + diagnosis. */
    public function close(Request $request, Consultation $consultation)
    {
        $doctor = $this->doctor();
        abort_if($consultation->booking->doctor_id !== $doctor->id, 403);
        abort_if($consultation->status !== 'active', 422, 'Konsultasi sudah ditutup.');

        $validated = $request->validate([
            'summary' => ['required', 'string'],
            'diagnosis' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($consultation, $validated) {
            $consultation->update([
                'summary' => $validated['summary'],
                'diagnosis' => $validated['diagnosis'] ?? null,
                'status' => 'closed',
                'ended_at' => now(),
            ]);

            $consultation->booking()->update(['status' => 'completed']);
        });

        return redirect()
            ->route('doctor.consultations.show', $consultation)
            ->with('success', 'Konsultasi ditutup dan riwayat tersimpan.');
    }
}
