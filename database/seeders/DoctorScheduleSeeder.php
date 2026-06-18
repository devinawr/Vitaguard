<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Database\Seeder;

class DoctorScheduleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Doctor::all() as $doctor) {
            DoctorSchedule::create([
                'doctor_id' => $doctor->id,
                'date' => now()->addDay()->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'status' => 'available',
            ]);

            DoctorSchedule::create([
                'doctor_id' => $doctor->id,
                'date' => now()->addDay()->toDateString(),
                'start_time' => '10:30:00',
                'end_time' => '11:30:00',
                'status' => 'available',
            ]);

            DoctorSchedule::create([
                'doctor_id' => $doctor->id,
                'date' => now()->addDays(2)->toDateString(),
                'start_time' => '13:00:00',
                'end_time' => '14:00:00',
                'status' => 'available',
            ]);
        }
    }
}
