<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['email' => 'adrian@vitaguard.test', 'date_of_birth' => '2003-05-20', 'gender' => 'Man', 'phone' => '081300000001', 'address' => 'Sidoarjo', 'blood_type' => 'O+', 'weight' => 65, 'height' => 170],
            ['email' => 'nadia@vitaguard.test', 'date_of_birth' => '2002-08-14', 'gender' => 'Woman', 'phone' => '081300000002', 'address' => 'Surabaya', 'blood_type' => 'A+', 'weight' => 52, 'height' => 160],
            ['email' => 'rizky@vitaguard.test', 'date_of_birth' => '2001-11-02', 'gender' => 'Man', 'phone' => '081300000003', 'address' => 'Gresik', 'blood_type' => 'B+', 'weight' => 72, 'height' => 175],
            ['email' => 'dewi@vitaguard.test', 'date_of_birth' => '1999-03-18', 'gender' => 'Woman', 'phone' => '081300000004', 'address' => 'Mojokerto', 'blood_type' => 'AB+', 'weight' => 50, 'height' => 158],
            ['email' => 'fajar@vitaguard.test', 'date_of_birth' => '2000-12-30', 'gender' => 'Man', 'phone' => '081300000005', 'address' => 'Pasuruan', 'blood_type' => 'O-', 'weight' => 68, 'height' => 172],
        ];

        foreach ($members as $data) {
            $email = $data['email'];
            unset($data['email']);

            Member::create([
                'user_id' => User::where('email', $email)->value('id'),
                ...$data,
            ]);
        }
    }
}
