<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // para dar otra apariencia a la página, he generado titulos con algo más de sentido
        // y con más sentido con la temática
        $titulosInicio = [
            'Cómo optimizar',
            'Guía definitiva sobre',
            'El error más común en',
            'Mejores prácticas para',
            'Introducción a',
            'Novedades de la última versión de',
            'Cómo solucionar problemas de',
            'Trucos avanzados para',
            'Por qué dejé de usar',
            'Mi experiencia aprendiendo',
            'Comparativa: React vs',
            'Desplegando mi primera app en',
            'Entendiendo los conceptos de',
            'Reto de código:',
            '¿Es el fin de',
        ];

        $titulosFin = [
            'Laravel 11',
            'Tailwind CSS v4',
            'JavaScript Moderno',
            'una API REST con Node.js',
            'bases de datos relacionales',
            'Python para Data Science',
            'microservicios en la nube',
            'seguridad en aplicaciones web',
            'componentes reutilizables',
            'la lógica de programación',
            'Docker y Kubernetes',
            'Vue.js y Pinia',
            'TypeScript en proyectos grandes',
            'el despliegue con GitHub Actions',
            'Inteligencia Artificial aplicada',
        ];

        $userIds = User::pluck('id');

        for ($i = 0; $i < 21; $i++) {
            $inicio = $titulosInicio[array_rand($titulosInicio)];
            $fin = $titulosFin[array_rand($titulosFin)];

            Post::factory()->create([
                'user_id' => $userIds->random(),
                'title' => $inicio.' '.$fin, // Concatenamos ambas partes
            ]);
        }
    }
}
