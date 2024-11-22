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
        Schema::create('borrowing_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrowing_id');
            $table->unsignedBigInteger('item_id');
            $table->timestamps();

            // Foreign key for borrowing
            $table->foreign('borrowing_id')->references('id')->on('borrowings')->onDelete('cascade');

            // Foreign key for item
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

            // Prevent duplicate records
            $table->unique(['borrowing_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
