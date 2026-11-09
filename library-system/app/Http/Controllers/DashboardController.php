<?php

namespace App\Http\Controllers;

use App\Models\Book;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Library System';
        $description = 'Sistem Informasi Perpustakaan Sederhana';

        // Mengambil data buku langsung dari database
        $bookCount = Book::count();
        $totalStock = Book::sum('stock');

        return view('dashboard.index', compact(
            'title',
            'description',
            'bookCount',
            'totalStock'
        ));
    }
}
