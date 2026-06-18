<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DoctorSeeder::class,
            MemberSeeder::class,
            ArticleSeeder::class,
            DoctorScheduleSeeder::class,
            BookingSeeder::class,
            ConsultationSeeder::class,
            ConsultationMessageSeeder::class,
        ]);
    }
}
