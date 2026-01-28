<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 200; $i++) {
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
