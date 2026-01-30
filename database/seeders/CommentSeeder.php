<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Estos son comentarios genéricos para dar más vidilla a la web
        $comentariosFalsos = [
            "¡Excelente aporte! Me sirvió mucho.",
            "No conocía esa herramienta, gracias por compartir.",
            "¿Podrías subir un ejemplo de cómo aplicarlo en Laravel?",
            "Me parece un tema súper interesante.",
            "Justo lo que estaba buscando para mi proyecto.",
            "Gran post, sigue así.",
            "Interesante punto de vista, aunque yo prefiero usar otra librería.",
            "¡Qué buen truco! No sabía que se podía hacer así.",
            "Totalmente de acuerdo contigo.",
            "Gracias por la información, muy bien explicado.",
            "¿Esto funciona también en versiones anteriores?",
            "Buenísimo, me ahorraste horas de investigación.",
            "Me encanta cómo explicas conceptos complejos de forma simple.",
            "¡A favoritos! Lo voy a revisar con más calma luego.",
            "Hacía falta un post sobre este tema en español."
        ];

        $userIds = User::pluck('id');
        $postIds = Post::pluck('id');

        if ($userIds->isEmpty() || $postIds->isEmpty()) {
            $this->command->warn("No hay usuarios o posts en la base de datos. Se saltará el seeder de comentarios.");
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $textoAleatorio = $comentariosFalsos[array_rand($comentariosFalsos)];

            Comment::factory()->create([
                'user_id' => $userIds->random(),
                'post_id' => $postIds->random(),
                'reply'   => $textoAleatorio,
            ]);
        }
    }
}
