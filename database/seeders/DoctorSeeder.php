<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'email' => 'andi@vitaguard.test',
                'specialty' => 'Dokter Umum',
                'license_number' => 'SIP-001-VG',
                'experience_years' => 8,
                'bio' => 'Berpengalaman menangani konsultasi kesehatan umum dan pencegahan penyakit.',
                'phone' => '081234560001',
                'rating' => 4.80,
                'status' => 'active',
                'date_of_birth' => '1988-04-12',
                'gender' => 'Man',
            ],
            [
                'email' => 'sinta@vitaguard.test',
                'specialty' => 'Dokter Kulit',
                'license_number' => 'SIP-002-VG',
                'experience_years' => 6,
                'bio' => 'Fokus pada kesehatan kulit, alergi, dan perawatan dermatologi dasar.',
                'phone' => '081234560002',
                'rating' => 4.90,
                'status' => 'active',
                'date_of_birth' => '1990-09-23',
                'gender' => 'Woman',
            ],
            [
                'email' => 'budi@vitaguard.test',
                'specialty' => 'Dokter Gigi',
                'license_number' => 'SIP-003-VG',
                'experience_years' => 10,
                'bio' => 'Menangani konsultasi kesehatan gigi dan mulut.',
                'phone' => '081234560003',
                'rating' => 4.70,
                'status' => 'active',
                'date_of_birth' => '1985-01-15',
                'gender' => 'Man',
            ],
            [
                'email' => 'maya@vitaguard.test',
                'specialty' => 'Dokter Anak',
                'license_number' => 'SIP-004-VG',
                'experience_years' => 7,
                'bio' => 'Memberikan konsultasi kesehatan anak dan pemantauan tumbuh kembang.',
                'phone' => '081234560004',
                'rating' => 4.85,
                'status' => 'active',
                'date_of_birth' => '1989-06-10',
                'gender' => 'Woman',
            ],
            [
                'email' => 'rina@vitaguard.test',
                'specialty' => 'Penyakit Dalam',
                'license_number' => 'SIP-005-VG',
                'experience_years' => 12,
                'bio' => 'Berpengalaman dalam konsultasi penyakit dalam dan pengelolaan penyakit kronis.',
                'phone' => '081234560005',
                'rating' => 4.95,
                'status' => 'active',
                'date_of_birth' => '1983-10-27',
                'gender' => 'Woman',
            ],
        ];

        foreach ($doctors as $data) {
            $email = $data['email'];
            unset($data['email']);

            Doctor::create([
                'user_id' => User::where('email', $email)->value('id'),
                ...$data,
            ]);
        }
    }
}
