<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Públicas
Route::get('/', [PostController::class, 'index'])->name('index');

Route::get('/post/{post}', [PostController::class, 'show'])->name('posts.show');

Route::get('/ranking', [UserController::class, 'likes'])->name('ranking');

Route::middleware(['auth', 'verified'])->group(function () {

    // --- USUARIOS ---
    Route::get('/dashboard', [UserController::class, 'profile'])->name('dashboard');
    Route::get('/user/list', [UserController::class, 'list'])
        ->name('user.list')
        ->middleware('permission:watch userlist');
    Route::get('/user/{user}', [UserController::class, 'show'])
        ->name('user.profile')
        ->middleware('role:admin|moderator');
    Route::patch('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/delete/{user}', [UserController::class, 'delete'])
        ->name('user.delete')
        ->middleware('permission:delete user');

    // --- POSTS ---
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
        ->name('posts.edit');
    Route::patch('/posts/{post}/edit', [PostController::class, 'update'])
        ->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'delete'])
        ->name('posts.delete');

// --- COMENTARIOS ---
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');

Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');

Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

require __DIR__.'/auth.php';
