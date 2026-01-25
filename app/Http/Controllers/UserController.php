<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function list()
    {
        $usuarios = User::with('roles')->get()->map(function ($usuario) {
            return [
                'ID' => $usuario->id,
                'Nombre' => $usuario->name,
                'Email' => $usuario->email,
                'Rol' => $usuario->getRoleNames()->first() ?? 'Sin Rol',
                'Alta' => $usuario->created_at->format('d/m/Y'),
            ];
        })->toArray(); 

        return view('user.manage', compact('usuarios'));
    }

    public function profile(Request $request) 
    {
        $usuario = $request->user(); 
        $roles = Role::all(); 
        return view('user.profile', compact('usuario', 'roles'));
    }

    public function show(User $user) 
    {
        // Seguridad gestionada por Middleware en web.php
        $roles = Role::all(); 
        return view('user.profile', ['usuario' => $user, 'roles' => $roles]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        // Usamos $request->user() para evitar errores de "undefined method"
        if ($request->user()->hasRole('admin') && $request->has('role')) {
            $user->syncRoles($request->role);
        }

        return back()->with('success', '¡Perfil actualizado!');
    }

    public function delete(User $user) 
    {
        // Seguridad gestionada por Middleware 'permission:delete user' en web.php
        $user->delete();

        return redirect()->route('user.list')->with('success', 'Usuario eliminado');
    }
}