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

        $watcher = User::factory()->create([
            'name' => 'watcher',
            'email' => 'watcher@example.org',
            'password' => Hash::make('watcher')
        ]);
        $watcher->assignRole('watcher');

        for ($i=0; $i < 10; $i++) { 
            $curUser = User::factory()->create();
            $curUser->assignRole('admin');
            $curUser = User::factory()->create();
            $curUser->assignRole('watcher');
            $curUser = User::factory()->create();
            $curUser->assignRole('user');
        }
    }
}
