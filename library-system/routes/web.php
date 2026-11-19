<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Autentikasi
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

// Semua fitur yang memerlukan login
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Fitur khusus Admin dan Petugas
    Route::middleware('role:admin,petugas')->group(function () {

        // Manajemen buku
        Route::resource('books', BookController::class);

        // Melihat kategori
        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        // Manajemen anggota
        Route::resource('members', MemberController::class);

        // Peminjaman dan pengembalian
        Route::resource('borrowings', BorrowingController::class)
            ->only(['index', 'create', 'store', 'show']);

        Route::post(
            '/borrowings/{borrowing}/return',
            [BorrowingController::class, 'returnBook']
        )->name('borrowings.return');
    });

    // Manajemen pengguna khusus Admin
    Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware('can:manage-users');

    // Pengelolaan kategori khusus Admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class)
            ->except(['index', 'show']);
    });
});
