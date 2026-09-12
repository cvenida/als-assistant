<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'type' => 'teacher',
                'email' => 'teacher@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
            ],
            [
                'id' => 2,
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'type' => 'student',
                'email' => 'student1@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
            ],
            [
                'id' => 3,
                'first_name' => 'Alex',
                'last_name' => 'Johnson',
                'type' => 'student',
                'email' => 'student2@example.com',
                'email_verified_at' => null,
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}