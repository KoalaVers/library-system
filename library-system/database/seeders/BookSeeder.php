<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'title' => 'Pemrograman PHP',
            'author' => 'Andi Setiawan',
            'year' => 2022,
            'stock' => 7,
        ]);

        Book::create([
            'title' => 'Laravel untuk Pemula',
            'author' => 'Budi Santoso',
            'year' => 2023,
            'stock' => 5,
        ]);

        Book::create([
            'title' => 'Basis Data',
            'author' => 'Citra Lestari',
            'year' => 2021,
            'stock' => 8,
        ]);

        Book::create([
            'title' => 'Algoritma dan Pemrograman',
            'author' => 'Dewi Anggraini',
            'year' => 2022,
            'stock' => 4,
        ]);

        Book::create([
            'title' => 'Pemrograman Berorientasi Objek',
            'author' => 'Eko Pratama',
            'year' => 2024,
            'stock' => 6,
        ]);
    }
}