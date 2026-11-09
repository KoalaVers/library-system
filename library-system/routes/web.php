<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Halaman yang hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Book Management
    Route::resource('books', BookController::class);

    // Category Management
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    // Member Management
    Route::get('/members', [MemberController::class, 'index'])
        ->name('members.index');

    // User Management khusus Admin
    Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware('can:manage-users');
});
