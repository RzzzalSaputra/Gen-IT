<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Option;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $options = [
            ['type' => 'contact_status', 'value' => 'pending'],
            ['type' => 'contact_status', 'value' => 'responded'],
            ['type' => 'user_role', 'value' => 'admin'],
            ['type' => 'user_role', 'value' => 'user'],
        ];

        foreach ($options as $option) {
            Option::create($option);
        }

        $users = [
            [
                'user_name' => 'IchikaNakano',
                'first_name' => 'Ichika',
                'last_name' => 'Nakano',
                'email' => 'ichika@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'birthdate' => '2000-05-05',
                'role' => 'user',
            ],
            [
                'user_name' => 'NinoNakano',
                'first_name' => 'Nino',
                'last_name' => 'Nakano',
                'email' => 'nino@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'birthdate' => '2000-05-05',
                'role' => 'user',
            ],
            [
                'user_name' => 'MikuNakano',
                'first_name' => 'Miku',
                'last_name' => 'Nakano',
                'email' => 'miku@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'birthdate' => '2000-05-05',
                'role' => 'user',
            ],
            [
                'user_name' => 'YotsubaNakano',
                'first_name' => 'Yotsuba',
                'last_name' => 'Nakano',
                'email' => 'yotsuba@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                'birthdate' => '2000-05-05',
                'role' => 'user',
            ],
            [
                'user_name' => 'ItsukiNakano',
                'first_name' => 'Itsuki',
                'last_name' => 'Nakano',
                'email' => 'itsuki@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567894',
                'birthdate' => '2000-05-05',
                'role' => 'user',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
