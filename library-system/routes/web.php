
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

// Fitur yang memerlukan login
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Manajemen buku: Admin dan Petugas
    Route::resource('books', BookController::class);

    // Kategori: Admin dan Petugas hanya dapat melihat
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    // Anggota: Admin dan Petugas dapat mengelola
    Route::resource('members', MemberController::class);

    // Manajemen pengguna khusus Admin
    Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware('can:manage-users');
});

// Peminjaman dan pengembalian: Admin dan Petugas
Route::middleware(['auth', 'role:admin,petugas'])->group(function () {

    Route::resource('borrowings', BorrowingController::class)
        ->only(['index', 'create', 'store', 'show']);

    Route::post(
        '/borrowings/{borrowing}/return',
        [BorrowingController::class, 'returnBook']
    )->name('borrowings.return');
});

// Pengelolaan kategori khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('categories', CategoryController::class)
        ->except(['index', 'show']);
});
