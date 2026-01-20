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
        // 1. Crear el usuario de prueba
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 2. Crear 5 usuarios más
        User::factory(5)->create();

        // 3. Crear 10 posts (uno a uno para que el usuario sea aleatorio)
        for ($i = 0; $i < 10; $i++) {
            Post::factory()->create([
                'user_id' => User::all()->random()->id
            ]);
        }

        // 4. Crear 15 comentarios (uno a uno para variar usuario y post)
        for ($i = 0; $i < 15; $i++) {
            Comment::factory()->create([
                'user_id' => User::all()->random()->id,
                'post_id' => Post::all()->random()->id
            ]);
        }

        // 5. Los likes los dejamos igual porque el Factory ya se encarga de lo aleatorio
        Like::factory(150)->create();
    }
}
