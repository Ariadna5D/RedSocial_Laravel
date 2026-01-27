<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $porPagina = $request->input('per_page', 10);

        // 1. Obtenemos el paginador original
        $usuariosPaginados = User::with('roles')->paginate($porPagina)->withQueryString();

        $usuariosPaginados->setCollection(
            $usuariosPaginados->getCollection()->map(function ($usuario) {
                return [
                    'ID' => $usuario->id,
                    'Nombre' => $usuario->name,
                    'Email' => $usuario->email,
                    'Rol' => $usuario->getRoleNames()->first() ?? 'Sin Rol',
                    'Alta' => $usuario->created_at->format('d/m/Y'),
                ];
            })
        );

        return view('user.manage', ['usuarios' => $usuariosPaginados, 'porPagina' => $porPagina]);
    }

    public function profile(Request $request)
    {
        $usuario = $request->user();
        $roles = Role::all();

        return view('user.profile', compact('usuario', 'roles'));
    }

    public function show(User $user)
    {
        $roles = Role::all();

        return view('user.profile', ['usuario' => $user, 'roles' => $roles]);
    }

    public function update(Request $request, User $user)
    {
        $authUser = $request->user();

        if ($authUser->id !== $user->id && ! $authUser->hasPermissionTo('update users')) {
            abort(403, 'No tienes el permiso "edit users" para realizar esta acción.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update($request->only('name', 'email'));

        if ($authUser->hasPermissionTo('update user') && $request->has('role')) {
            $user->syncRoles($request->role);
        }

        return back()->with('success', 'Perfil actualizado!');
    }

    public function delete(User $user)
    {
        $user->delete();

        return redirect()->route('user.list')->with('success', 'Usuario eliminado');
    }

    public function likes()
    {
        $usuarios = User::select('id', 'name')
            ->withCount(['postLikes', 'commentLikes'])
            ->get()
            ->map(function ($usuario) {
                $usuario->total_likes = $usuario->post_likes_count + $usuario->comment_likes_count;

                return $usuario;
            })
            ->sortByDesc('total_likes')
            ->values(); 

        $configuracion = [
            'Puesto' => 'puesto',
            'Nombre' => 'name',
            'Total Likes' => 'total_likes',
        ];

        return view('social.ranking', [
            'usuarios' => $usuarios,
            'configuracion' => $configuracion,
        ]);
    }
}
