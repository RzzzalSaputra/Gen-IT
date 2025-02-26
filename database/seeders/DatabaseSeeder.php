<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Option;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tambahkan opsi ke tabel `options`
        $options = [
            ['type' => 'contact_status', 'value' => 'pending'],
            ['type' => 'contact_status', 'value' => 'responded'],
            ['type' => 'user_role', 'value' => 'admin'],
            ['type' => 'user_role', 'value' => 'user'],
            ['type' => 'post_layout', 'value' => 'default'],
            ['type' => 'post_layout', 'value' => 'teks+konten+teks'],
        ];

        foreach ($options as $option) {
            Option::updateOrCreate($option);
        }

        // Ambil role dari options
        $roleUser = Option::where('type', 'user_role')->where('value', 'user')->first();

        // Buat user
        $users = [
            [
                'user_name' => 'IchikaNakano',
                'first_name' => 'Ichika',
                'last_name' => 'Nakano',
                'email' => 'ichika@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'birthdate' => '2000-05-05',
                'role' => $roleUser->id,
            ],
            [
                'user_name' => 'NinoNakano',
                'first_name' => 'Nino',
                'last_name' => 'Nakano',
                'email' => 'nino@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'birthdate' => '2000-05-05',
                'role' => $roleUser->id,
            ],
            [
                'user_name' => 'MikuNakano',
                'first_name' => 'Miku',
                'last_name' => 'Nakano',
                'email' => 'miku@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'birthdate' => '2000-05-05',
                'role' => $roleUser->id,
            ],
            [
                'user_name' => 'YotsubaNakano',
                'first_name' => 'Yotsuba',
                'last_name' => 'Nakano',
                'email' => 'yotsuba@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                'birthdate' => '2000-05-05',
                'role' => $roleUser->id,
            ],
            [
                'user_name' => 'ItsukiNakano',
                'first_name' => 'Itsuki',
                'last_name' => 'Nakano',
                'email' => 'itsuki@example.com',
                'password' => Hash::make('Password123!'),
                'phone' => '081234567894',
                'birthdate' => '2000-05-05',
                'role' => 3,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['email' => $userData['email']], $userData);
        }

        // Ambil semua user
        $users = User::all();

        // Ambil layout dari options
        $layout = Option::where('type', 'post_layout')->where('value', 'default')->first();

        // Buat post (ambil user secara acak)
        $posts = [
            [
                'title' => 'Judul Post Pertama',
                'slug' => Str::slug('Judul Post Pertama'),
                'content' => 'Ini adalah konten dari post pertama.',
                'file' => null,
                'img' => 'https://via.placeholder.com/150',
                'layout' => $layout->id,
                'created_by' => $users->random()->id,
                'counter' => 0,
            ],
            [
                'title' => 'Judul Post Kedua',
                'slug' => Str::slug('Judul Post Kedua'),
                'content' => 'Ini adalah konten dari post kedua.',
                'file' => 'https://example.com/file.pdf',
                'img' => null,
                'layout' => $layout->id,
                'created_by' => $users->random()->id,
                'counter' => 5,
            ],
            [
                'title' => 'Judul Post Ketiga',
                'slug' => Str::slug('Judul Post Ketiga'),
                'content' => 'Ini adalah konten dari post ketiga.',
                'file' => null,
                'img' => null,
                'layout' => $layout->id,
                'created_by' => $users->random()->id,
                'counter' => 10,
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
