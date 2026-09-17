<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Library System';
        $description = 'Sistem Informasi Perpustakaan Sederhana';

        $bookCount = 8;
        $memberCount = 5;
        $categoryCount = 5;

        return view('dashboard.index', compact(
            'title',
            'description',
            'bookCount',
            'memberCount',
            'categoryCount'
        ));
    }
}