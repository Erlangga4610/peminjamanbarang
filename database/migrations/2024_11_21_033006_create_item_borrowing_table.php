<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_borrowing', function (Blueprint $table) {
            $table->id();  // ID untuk tabel pivot
            $table->foreignId('item_id')  // Kolom item_id
                  ->constrained('items')  // Mengacu pada tabel 'items'
                  ->onDelete('cascade');  // Menghapus data jika item dihapus

            $table->foreignId('borrowing_id')  // Kolom borrowing_id
                  ->constrained('borrowings')  // Mengacu pada tabel 'borrowings'
                  ->onDelete('cascade');  // Menghapus data jika peminjaman dihapus

            $table->timestamps();  // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_borrowing');
    }
};
