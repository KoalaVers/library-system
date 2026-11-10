<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
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
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('books', BookController::class);

    Route::resource('categories', CategoryController::class);
    Route::resource('members', MemberController::class);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin-test', function () {
        return 'Selamat datang, Admin! Anda memiliki akses.';
    });
});

Route::get('/admin-gate-test', function () {
    return 'Gate berhasil! Admin boleh mengelola pengguna.';
})->middleware(['auth', 'can:manage-users']);