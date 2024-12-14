<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('item_borrowing', function (Blueprint $table) {
        $table->integer('quantity')->default(1); // Menambahkan kolom quantity
    });
}

public function down()
{
    Schema::table('item_borrowing', function (Blueprint $table) {
        $table->dropColumn('quantity'); // Menghapus kolom quantity jika rollback migrasi
    });
}

};
