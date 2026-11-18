
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();

            // Petugas yang memproses peminjaman
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Anggota yang meminjam buku
            $table->foreignId('member_id')
                ->constrained('members')
                ->restrictOnDelete();

            // Tanggal transaksi
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();

            // Status peminjaman
            $table->string('status')->default('borrowed');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};