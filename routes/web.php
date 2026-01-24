<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Públicas
Route::get('/', [SocialController::class, 'index'])->name('index');

// Rutas Protegidas (Solo usuarios logueados)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Tu Dashboard principal
    Route::get('/dashboard', [UserController::class, 'profile'])->name('dashboard');

    // Gestión de usuarios
    Route::get('/user/list', [UserController::class, 'list'])->name('user.list');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.profile');
    Route::delete('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');

    // Rutas de perfil (las que trae Breeze por defecto)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';