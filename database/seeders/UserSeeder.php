<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin VitaGuard', 'role' => 'admin', 'email' => 'admin@vitaguard.test'],
            ['name' => 'dr. Andi Pratama', 'role' => 'doctor', 'email' => 'andi@vitaguard.test'],
            ['name' => 'dr. Sinta Maharani', 'role' => 'doctor', 'email' => 'sinta@vitaguard.test'],
            ['name' => 'dr. Budi Santoso', 'role' => 'doctor', 'email' => 'budi@vitaguard.test'],
            ['name' => 'dr. Maya Anggraini', 'role' => 'doctor', 'email' => 'maya@vitaguard.test'],
            ['name' => 'dr. Rina Kurniawati', 'role' => 'doctor', 'email' => 'rina@vitaguard.test'],
            ['name' => 'Adrian Fakhriza', 'role' => 'member', 'email' => 'adrian@vitaguard.test'],
            ['name' => 'Nadia Putri', 'role' => 'member', 'email' => 'nadia@vitaguard.test'],
            ['name' => 'Rizky Ramadhan', 'role' => 'member', 'email' => 'rizky@vitaguard.test'],
            ['name' => 'Dewi Lestari', 'role' => 'member', 'email' => 'dewi@vitaguard.test'],
            ['name' => 'Fajar Nugroho', 'role' => 'member', 'email' => 'fajar@vitaguard.test'],
        ];

        foreach ($users as $user) {
            User::create([
                ...$user,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]);
        }
    }
}
