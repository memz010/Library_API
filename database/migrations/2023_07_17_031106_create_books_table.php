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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->text('description');
            $table->string('cover_image');
            $table->json('array_image');
            $table->integer('price');
            $table->integer('quantity_sell');
            $table->integer('quantity_reservation');
            $table->string('pdf')->nullable()->default(null);
            $table->string('sound_book')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
