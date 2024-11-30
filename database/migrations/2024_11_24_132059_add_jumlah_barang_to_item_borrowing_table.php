<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJumlahBarangToItemBorrowingTable extends Migration
{
    public function up()
    {
        Schema::table('item_borrowing', function (Blueprint $table) {
            $table->integer('jumlah_barang')->default(1); // You can set a default value if necessary
        });
    }

    public function down()
    {
        Schema::table('item_borrowing', function (Blueprint $table) {
            $table->dropColumn('jumlah_barang');
        });
    }
}
