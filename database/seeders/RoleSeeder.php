<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name'=>'watch user']);
        Permission::create(['name'=>'create user']);
        Permission::create(['name'=>'edit user']);
        Permission::create(['name'=>'delete user']);

        Permission::create(['name'=>'create post']);
        Permission::create(['name'=>'edit post']);
        Permission::create(['name'=>'delete post']);

        Permission::create(['name'=>'create comment']);
        Permission::create(['name'=>'edit comment']);
        Permission::create(['name'=>'delete comment']);

        Permission::create(['name'=>'can like']);

        // Creamos el Rol
        $roleAdmin = Role::create(['name' => 'admin']);
        // Cogemos el nombre del rol
        $permissionsAdmin = Permission::query()->pluck('name');
        // Sincronizamos
        $roleAdmin->syncPermissions($permissionsAdmin);

        $roleAdmin = Role::create(['name' => 'user']);
        $roleAdmin->syncPermissions(['can like','create post','create comment']);

        $roleAdmin = Role::create(['name' => 'watcher']);
        $roleAdmin->syncPermissions(['can like','create post','create comment','watch user']);
    }
}
