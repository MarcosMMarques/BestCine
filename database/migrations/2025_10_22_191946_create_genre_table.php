<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genre', function (Blueprint $table) {
            $table->text('name')->primary();
        });

        Schema::create('genre_movie', function (Blueprint $table) {
            $table->text('genre_name');

            $table->foreignId('movie_id')->constrained('movie')->onDelete('cascade');
            $table->foreign('genre_name')->references('name')->on('genre')->onDelete('cascade');

            $table->primary(['movie_id', 'genre_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre');
        Schema::dropIfExists('genre_movie');
    }
};
