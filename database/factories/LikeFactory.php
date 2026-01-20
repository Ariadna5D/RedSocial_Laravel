<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
{
    // 1. Elegimos al azar si el "like" es para un Post o para un Comment
    $tipo = fake()->randomElement([Post::class, Comment::class]);

    // 2. Buscamos un usuario que ya exista en la base de datos
    $usuarioAleatorio = User::inRandomOrder()->first();

    if ($usuarioAleatorio) {
        // Si existe un usuario, usamos su ID
        $userId = $usuarioAleatorio->id;
    } else {
        // Si no hay usuarios, creamos uno nuevo con el Factory y guardamos su ID
        $nuevoUsuario = User::factory()->create();
        $userId = $nuevoUsuario->id;
    }

    // 3. Buscamos un registro (Post o Comment) de la clase que elegimos antes
    $registroParaDarLike = $tipo::inRandomOrder()->first();

    if ($registroParaDarLike) {
        // Si ya hay un Post o Comment, cogemos su ID
        $likeableId = $registroParaDarLike->id;
    } else {
        // Si no hay nada creado de ese tipo, lo creamos ahora mismo
        $nuevoRegistro = $tipo::factory()->create();
        $likeableId = $nuevoRegistro->id;
    }

    // 4. Devolvemos el array con los datos listos para insertar
    return [
        'user_id'      => $userId,
        'likeable_id'  => $likeableId,
        'likeable_type' => $tipo,
    ];
}
}
