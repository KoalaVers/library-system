<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('welcome');
});

Route:: get('/books', function() {
    return view('books.index');
});

Route::get('/books', [BookController::class, 'index']); 