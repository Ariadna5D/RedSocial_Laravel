@extends('layouts.master')

@section('title', 'Editar Perfil')

@section('content')
    <div class="p-6">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-6">
            Bienvenido a tu perfil {{ $usuario->name }}
        </h2>
        <x-edit-form 
            :modelo="$usuario" 
            :accion="route('user.update', $usuario)" 
            metodo="PATCH"
            :excepto="['id', 'email_verified_at', 'created_at', 'updated_at', 'role', 'roles', 'permissions']" 
        >
            @can('update user')
                <div class="mt-6 p-4 bg-teal-50 border border-teal-200 rounded-lg">
                    <label for="role" class="text-sm font-bold text-teal-700 uppercase mb-2 block">
                        Cambiar Rol de Usuario 
                    </label>
                    <select name="role" id="role" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $usuario->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endcan
        </x-edit-form>
    </div>
@endsection