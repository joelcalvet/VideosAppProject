<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersManageController;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\VideosManageController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SeriesManageController;
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

// Rutes de gestió d’usuaris amb permís específic
Route::middleware(['auth', 'can:manage users'])->group(function () {
    Route::get('users/manage', [UsersManageController::class, 'index'])->name('users.manage.index');
    Route::get('users/manage/create', [UsersManageController::class, 'create'])->name('users.manage.create');
    Route::post('users/manage', [UsersManageController::class, 'store'])->name('users.manage.store');
    Route::get('users/manage/{id}/edit', [UsersManageController::class, 'edit'])->name('users.manage.edit');
    Route::put('users/manage/{id}', [UsersManageController::class, 'update'])->name('users.manage.update');
    Route::get('users/manage/{id}/delete', [UsersManageController::class, 'delete'])->name('users.manage.delete');
    Route::delete('users/manage/{id}', [UsersManageController::class, 'destroy'])->name('users.manage.destroy');
});

// Rutes de gestió de sèries amb permís específic
Route::middleware(['auth', 'can:manage series'])->group(function () {
    Route::get('series/manage', [SeriesManageController::class, 'index'])->name('series.manage.index');
    Route::get('series/manage/create', [SeriesManageController::class, 'create'])->name('series.manage.create');
    Route::post('series/manage', [SeriesManageController::class, 'store'])->name('series.manage.store');
    Route::get('series/manage/{id}/edit', [SeriesManageController::class, 'edit'])->name('series.manage.edit');
    Route::put('series/manage/{id}', [SeriesManageController::class, 'update'])->name('series.manage.update');
    Route::get('series/manage/{id}/delete', [SeriesManageController::class, 'delete'])->name('series.manage.delete');
    Route::delete('series/manage/{id}', [SeriesManageController::class, 'destroy'])->name('series.manage.destroy');
});

// Rutes protegides amb autenticació bàsica
Route::middleware(['auth'])->group(function () {
    // Rutes de vídeos
    Route::get('/videos', [VideosController::class, 'index'])->name('videos.index');
    Route::get('/videos/create', [VideosController::class, 'create'])->name('videos.create');
    Route::post('/videos', [VideosController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}', [VideosController::class, 'show'])->name('videos.show');
    Route::get('/videos/{id}/edit', [VideosController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{id}', [VideosController::class, 'update'])->name('videos.update');
    Route::get('/videos/{id}/delete', [VideosController::class, 'delete'])->name('videos.delete');
    Route::delete('/videos/{id}', [VideosController::class, 'destroy'])->name('videos.destroy');

    // Rutes d'usuaris
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');

    // Rutes de sèries (públiques i de gestió per a usuaris autenticats)
    Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
    Route::get('/series/create', [SeriesController::class, 'create'])->name('series.create');
    Route::post('/series', [SeriesController::class, 'store'])->name('series.store');
    Route::get('/series/{id}', [SeriesController::class, 'show'])->name('series.show');
    Route::get('/series/{id}/edit', [SeriesController::class, 'edit'])->name('series.edit');
    Route::put('/series/{id}', [SeriesController::class, 'update'])->name('series.update');
    Route::get('/series/{id}/delete', [SeriesController::class, 'delete'])->name('series.delete');
    Route::delete('/series/{id}', [SeriesController::class, 'destroy'])->name('series.destroy');
    Route::post('/series/{id}/add-video', [SeriesController::class, 'addVideo'])->name('series.addVideo'); // Nova ruta afegida
});

// Rutes públiques de vídeos (al final per evitar conflictes)
Route::get('/videos', [VideosController::class, 'index'])->name('videos.index');
Route::get('/videos/{video}', [VideosController::class, 'show'])->name('videos.show');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
