<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Públicas
Route::get('/', [SocialController::class, 'index'])->name('index');

// Rutas Protegidas (Solo usuarios logueados)
// web.php

Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. EL DASHBOARD (Tu propio perfil)
    // Asegúrate de que esta línea exista para corregir el error RouteNotFound
    Route::get('/dashboard', [UserController::class, 'profile'])->name('dashboard');

    // 2. LISTA DE USUARIOS (Solo con permiso específico)
    Route::get('/user/list', [UserController::class, 'list'])
        ->name('user.list')
        ->middleware('permission:watch userlist');
    
    // 3. VER PERFIL AJENO (Solo Admin o Moderador)
    // Usamos el middleware 'role' de Spatie
    Route::get('/user/{user}', [UserController::class, 'show'])
        ->name('user.profile')
        ->middleware('role:admin|moderator');
    
    // 4. ACTUALIZAR (Solo Admin)
    Route::patch('/user/{user}', [UserController::class, 'update'])
        ->name('user.update')
        ->middleware('role:admin');

    // 5. ELIMINAR (Solo con permiso)
    Route::delete('/user/delete/{user}', [UserController::class, 'delete'])
        ->name('user.delete')
        ->middleware('permission:delete user');
});

require __DIR__.'/auth.php';