<?php

namespace Database\Seeders;

use App\Models\Consultation;
use Illuminate\Database\Seeder;

class ConsultationMessageSeeder extends Seeder
{
    public function run(): void
    {
        $consultations = Consultation::with([
            'booking.member',
            'booking.doctor',
        ])->get();

        foreach ($consultations as $consultation) {
            $memberUserId = $consultation->booking->member->user_id;
            $doctorUserId = $consultation->booking->doctor->user_id;

            // MANY-TO-MANY: attach user ke consultation melalui pivot consultation_messages.
            $consultation->participants()->attach($memberUserId, [
                'message' => 'Dok, saya ingin menjelaskan keluhan yang saya alami.',
                'read_at' => now(),
            ]);

            $consultation->participants()->attach($doctorUserId, [
                'message' => 'Baik, silakan jelaskan sejak kapan keluhan tersebut muncul.',
                'read_at' => now(),
            ]);

            $consultation->participants()->attach($memberUserId, [
                'message' => 'Keluhan mulai terasa sejak dua hari lalu.',
                'read_at' => null,
            ]);
        }
    }
}
