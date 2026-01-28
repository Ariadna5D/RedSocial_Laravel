<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imagenes = [
        "🚀", "✨", "🔥", "🌈", "🍕", "🦾", "🎨", "🌍", "💡", "🎉", 
        "💻", "⌨️", "🖥️", "🖱️", "💾", "📡", "🔋", "🔌", "⚙️", "🛠️",
        "👨‍💻", "👩‍💻", "👾", "🤖", "🦾", "🧠", "🧬", "🕸️", "🔒", "🔑", 
        "💾", "💿", "📀", "📼", "📷", "📸", "📹", "🎥", "📽️", "🎞️" 
        ];
            $temas = [
        "slate", "gray",
        "red", "orange", "amber", "yellow", "lime",
        "green", "emerald", "teal", "cyan", "sky",
        "blue", "indigo", "violet", "purple", "fuchsia",
        "pink", "rose"
    ];

        return [
            'name' => fake()->unique()->word() . fake()->numberBetween(10,300),
            'email' => fake()->unique()->safeEmail(),
            'pic' => fake()->randomElement($imagenes),
            'theme' => fake()->randomElement($temas),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }


}
