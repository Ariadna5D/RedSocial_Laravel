<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.org',
            'password' => Hash::make('admin')
        ]);
        $admin->assignRole('admin');

        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.org',
            'password' => Hash::make('user')
        ]);
        $user->assignRole('user');

        $revisor = User::factory()->create([
            'name' => 'revisor',
            'email' => 'revisor@example.org',
            'password' => Hash::make('revisor')
        ]);
        $revisor->assignRole('revisor');

        for ($i=0; $i < 30; $i++) {
            $curUser = User::factory()->create();
            $curUser->assignRole('admin');
            $curUser = User::factory()->create();
            $curUser->assignRole('revisor');
            $curUser = User::factory()->create();
            $curUser->assignRole('user');
        }
    }
}
