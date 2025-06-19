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
        Schema::create('book_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->nullable()->constrained('books', 'id')->nullOnDelete();
            $table->foreignId('reader_card_id')->nullable()->constrained('reader_cards', 'id')->nullOnDelete();
            $table->dateTime('receive_date');
            $table->dateTime('return_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_receipts');
    }
};
