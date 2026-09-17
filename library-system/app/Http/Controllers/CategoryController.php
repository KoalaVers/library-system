<?php

namespace App\Http\Controllers;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = [
            'Pemrograman',
            'Basis Data',
            'Jaringan Komputer',
            'Sistem Informasi',
            'Algoritma'
        ];

        return view('categories.index', compact('categories'));
    }
}