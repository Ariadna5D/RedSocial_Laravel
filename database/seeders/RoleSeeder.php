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
        Permission::create(['name'=>'watch userlist']);
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

        // creamos el Rol
        $roleAdmin = Role::create(['name' => 'admin']);
        // cogemos el nombre del rol
        $permissionsAdmin = Permission::query()->pluck('name');
        // sincronizamos
        $roleAdmin->syncPermissions($permissionsAdmin);

        $roleUser = Role::create(['name' => 'user']);
        $roleUser->syncPermissions(['can like', 'create post', 'create comment']);

        $roleRevisor = Role::create(['name' => 'revisor']);
        $roleRevisor->syncPermissions(['can like', 'create post', 'create comment', 'watch user', 'watch userlist']);
    }
}
