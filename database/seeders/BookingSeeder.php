<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\DoctorSchedule;
use App\Models\Member;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $members = Member::orderBy('id')->get();
        $schedules = DoctorSchedule::with('doctor')->orderBy('id')->take(5)->get();
        $statuses = ['completed', 'ongoing', 'confirmed', 'pending', 'confirmed'];
        $complaints = [
            'Demam ringan dan tubuh terasa lemas.',
            'Muncul kemerahan dan gatal pada kulit.',
            'Gusi mudah berdarah ketika dibersihkan.',
            'Sering merasa pusing pada sore hari.',
            'Ingin konsultasi mengenai pola tidur.',
        ];

        foreach ($schedules as $index => $schedule) {
            Booking::create([
                'booking_code' => 'BKG-SEED-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'member_id' => $members[$index]->id,
                'doctor_id' => $schedule->doctor_id,
                'schedule_id' => $schedule->id,
                'consultation_date' => $schedule->date->format('Y-m-d').' '.$schedule->start_time,
                'complaint' => $complaints[$index],
                'notes' => $index === 0 ? 'Pasien diminta menjaga hidrasi.' : null,
                'status' => $statuses[$index],
            ]);

            $schedule->update(['status' => 'booked']);
        }
    }
}
