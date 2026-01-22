<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(5)->create();

        for ($i = 0; $i < 10; $i++) {
            Post::factory()->create([
                'user_id' => User::all()->random()->id,
            ]);
        }

        for ($i = 0; $i < 15; $i++) {
            Comment::factory()->create([
                'user_id' => User::all()->random()->id,
                'post_id' => Post::all()->random()->id,
            ]);
        }

        for ($i = 0; $i < 50; $i++) { 
            $likeableType = collect([Post::class, Comment::class])->random();
            $likeableId = $likeableType::all()->random()->id;

            Like::firstOrCreate([
                'user_id' => User::all()->random()->id,
                'likeable_id' => $likeableId,
                'likeable_type' => $likeableType,
            ]);
        }
    }
}
