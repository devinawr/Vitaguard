<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Consultation;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::orderBy('id')->take(5)->get();

        foreach ($bookings as $index => $booking) {
            $closed = in_array($index, [0, 3], true);

            Consultation::create([
                'booking_id' => $booking->id,
                'started_at' => now()->subHours(3 - $index),
                'ended_at' => $closed ? now()->subHours(2) : null,
                'status' => $closed ? 'closed' : 'active',
                'summary' => $closed ? 'Keluhan ringan, disarankan istirahat dan menjaga asupan cairan.' : null,
                'diagnosis' => $closed ? 'Demam ringan' : null,
            ]);
        }
    }
}
