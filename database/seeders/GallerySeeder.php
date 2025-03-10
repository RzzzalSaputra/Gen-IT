<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get random user IDs for created_by
        $userIds = User::pluck('id')->toArray();

        // Sample galleries - Images (type 7)
        $imageGalleries = [
            [
                'type' => 7,
                'title' => 'Beautiful Sunset',
                'file' => 'https://images.unsplash.com/photo-1586348943529-beaae6c28db9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'link' => null,
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
            [
                'type' => 7,
                'title' => 'Mountain Landscape',
                'file' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'link' => null,
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
            [
                'type' => 7,
                'title' => 'Ocean Waves',
                'file' => 'https://images.unsplash.com/photo-1518837695005-2083093ee35b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'link' => null,
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
        ];

        // Sample galleries - Videos (type 8)
        $videoGalleries = [
            [
                'type' => 8,
                'title' => 'Amazing Nature Documentary',
                'file' => null,
                'link' => 'https://www.youtube.com/watch?v=W-CTzidBK7c',
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
            [
                'type' => 8,
                'title' => 'Travel Vlog: Tokyo',
                'file' => null,
                'link' => 'https://www.youtube.com/watch?v=cS30JWmxlLI',
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
            [
                'type' => 8,
                'title' => 'Cooking Tutorial',
                'file' => null,
                'link' => 'https://www.youtube.com/watch?v=mJ7oEALrP4s',
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
        ];

        // Merge and insert all galleries
        $galleries = array_merge($imageGalleries, $videoGalleries);
        
        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }
    }
}