<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Library System';
        $description = 'Sistem Informasi Perpustakaan Sederhana';

        // Menghitung jumlah judul buku.
        $bookCount = Book::count();

        // Menghitung seluruh stok buku.
        $totalStock = Book::sum('stock');

        // Menghitung jumlah anggota perpustakaan.
        $memberCount = Member::count();

        // Menghitung peminjaman yang belum dikembalikan.
        $activeBorrowings = Borrowing::where('status', 'borrowed')
            ->count();

        return view('dashboard.index', compact(
            'title',
            'description',
            'bookCount',
            'totalStock',
            'memberCount',
            'activeBorrowings'
        ));
    }
}
