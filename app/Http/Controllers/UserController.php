<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
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

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create($request->all());

        return redirect()->route('user.list')->with('success', 'Usuario creado');
    }

    public function show(string $id) {
        $usuario = User::findOrFail($id);

        return redirect()->route('user.profile')->compact($usuario);
    }

    
}
