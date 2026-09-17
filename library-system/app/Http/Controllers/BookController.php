<?php

namespace App\Http\Controllers;

class BookController extends Controller
{
    public function index()
    {
        $books = [
            [
                'id' => 1,
                'title' => 'Pemrograman PHP',
                'author' => 'Andi Setiawan',
                'year' => 2022
            ],
            [
                'id' => 2,
                'title' => 'Laravel untuk Pemula',
                'author' => 'Budi Santoso',
                'year' => 2023
            ],
            [
                'id' => 3,
                'title' => 'Basis Data',
                'author' => 'Citra Lestari',
                'year' => 2021
            ],
            [
                'id' => 4,
                'title' => 'Algoritma dan Pemrograman',
                'author' => 'Dewi Anggraini',
                'year' => 2022
            ],
            [
                'id' => 5,
                'title' => 'Pemrograman Berorientasi Objek',
                'author' => 'Eko Pratama',
                'year' => 2024
            ],
            [
                'id' => 6,
                'title' => 'Sistem Informasi',
                'author' => 'Fajar Nugraha',
                'year' => 2023
            ],
            [
                'id' => 7,
                'title' => 'Jaringan Komputer',
                'author' => 'Gilang Ramadhan',
                'year' => 2022
            ],
            [
                'id' => 8,
                'title' => 'Pengembangan Web',
                'author' => 'Hana Putri',
                'year' => 2024
            ],
        ];

        $stock = 7;

        return view('books.index', compact('books', 'stock'));
    }

    public function show($id)
    {
        return view('books.show', compact('id'));
    }

}