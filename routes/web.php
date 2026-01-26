<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Públicas
Route::get('/', [PostController::class, 'index'])->name('index');

Route::get('/post/{post}', [PostController::class, 'show'])->name('posts.show');

Route::get('/ranking', [UserController::class, 'likes'])->name('ranking');

Route::middleware(['auth', 'verified'])->group(function () {

    // 1. EL DASHBOARD (Tu propio perfil)
    Route::get('/dashboard', [UserController::class, 'profile'])->name('dashboard');

    Route::get('/user/list', [UserController::class, 'list'])
        ->name('user.list')
        ->middleware('permission:watch userlist');

    // 3. VER PERFIL AJENO (Solo Admin o Moderador)
    Route::get('/user/{user}', [UserController::class, 'show'])
        ->name('user.profile')
        ->middleware('role:admin|moderator');

    // 4. ACTUALIZAR (Quitamos el middleware de la ruta para manejarlo en el controlador)
    Route::patch('/user/{user}', [UserController::class, 'update'])->name('user.update');

    Route::delete('/user/delete/{user}', [UserController::class, 'delete'])
        ->name('user.delete')
        ->middleware('permission:delete user');

    Route::delete('/user/delete/{user}', [UserController::class, 'delete'])
        ->name('user.delete')
        ;

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

    Route::post('/posts/{post}/like', [PostController::class, 'like'])
        ->name('posts.like');

    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
        ->name('posts.edit')
        ->middleware('permission:edit post');

    Route::delete('/posts/{post}', [PostController::class, 'delete'])
        ->name('posts.destroy')
        ->middleware('permission:delete post');
});

require __DIR__.'/auth.php';
