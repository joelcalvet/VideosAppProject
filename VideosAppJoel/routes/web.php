<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersManageController;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\VideosManageController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::get('/', function () {
    return redirect()->route('videos.index');
});

// Rutes protegides amb Jetstream per al dashboard
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Rutes de gestió de vídeos amb permís específic
Route::middleware(['auth', 'can:manage videos'])->group(function () {
    Route::get('videos/manage', [VideosManageController::class, 'index'])->name('videos.manage.index');
    Route::get('videos/manage/create', [VideosManageController::class, 'create'])->name('videos.manage.create');
    Route::post('videos/manage', [VideosManageController::class, 'store'])->name('videos.manage.store');
    Route::get('videos/manage/{video}', [VideosManageController::class, 'show'])->name('videos.manage.show');
    Route::get('videos/manage/{video}/edit', [VideosManageController::class, 'edit'])->name('videos.manage.edit');
    Route::put('videos/manage/{video}', [VideosManageController::class, 'update'])->name('videos.manage.update');
    Route::get('videos/manage/{video}/delete', [VideosManageController::class, 'delete'])->name('videos.manage.delete');
    Route::delete('videos/manage/{video}', [VideosManageController::class, 'destroy'])->name('videos.manage.destroy');
});

Route::middleware(['auth', 'can:manage users'])->group(function () {
    Route::get('users/manage', [UsersManageController::class, 'index'])->name('users.manage.index');
    Route::get('users/manage/create', [UsersManageController::class, 'create'])->name('users.manage.create');
    Route::post('users/manage', [UsersManageController::class, 'store'])->name('users.manage.store');
    Route::get('users/manage/{id}/edit', [UsersManageController::class, 'edit'])->name('users.manage.edit');
    Route::put('users/manage/{id}', [UsersManageController::class, 'update'])->name('users.manage.update');
    Route::get('users/manage/{id}/delete', [UsersManageController::class, 'delete'])->name('users.manage.delete');
    Route::delete('users/manage/{id}', [UsersManageController::class, 'destroy'])->name('users.manage.destroy');
});

// Rutes protegides amb autenticació bàsica
Route::middleware(['auth'])->group(function () {
    Route::get('/videos/create', [VideosController::class, 'create'])->name('videos.create');
    Route::post('/videos', [VideosController::class, 'store'])->name('videos.store');
    Route::get('/videos/{id}/edit', [VideosController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{id}', [VideosController::class, 'update'])->name('videos.update');
    Route::get('/videos/{id}/delete', [VideosController::class, 'edit'])->name('videos.delete');
    Route::delete('/videos/{id}', [VideosController::class, 'destroy'])->name('videos.destroy');
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
});

// Rutes públiques de vídeos (al final per evitar conflictes)
Route::get('/videos', [VideosController::class, 'index'])->name('videos.index');
Route::get('/videos/{video}', [VideosController::class, 'show'])->name('videos.show');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
