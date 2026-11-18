
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowing_details', function (Blueprint $table) {
            $table->id();

            // Relasi ke transaksi peminjaman
            $table->foreignId('borrowing_id')
                ->constrained('borrowings')
                ->cascadeOnDelete();

            // Relasi ke buku
            $table->foreignId('book_id')
                ->constrained('books')
                ->restrictOnDelete();

            // Jumlah buku yang dipinjam
            $table->unsignedInteger('quantity');

            // Mencegah buku yang sama dicatat dua kali
            $table->unique(['borrowing_id', 'book_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowing_details');
    }
};