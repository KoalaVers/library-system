<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::resource('books', BookController::class);

    // Admin & Petugas dapat melihat daftar kategori & mengelola data anggota
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::resource('members', MemberController::class);
});

// Route khusus Admin untuk CRUD Kategori
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
});