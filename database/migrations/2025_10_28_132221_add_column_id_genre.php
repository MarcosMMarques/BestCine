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

        Schema::table('genre_movie', function (Blueprint $table) {
            $table->dropPrimary('genre_movie_pkey');
            $table->dropConstrainedForeignId('genre_name');
        });
        Schema::table('genre', function (Blueprint $table) {
            $table->dropPrimary('name');
            $table->id();
        });
        Schema::table('genre_movie', function (Blueprint $table) {
            $table->foreignId('genre_id')->constrained(table: 'genre')->onDelete('cascade');
            $table->primary(['movie_id', 'genre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('genre_movie', function (Blueprint $table) {
            $table->dropConstrainedForeignId('genre_id');
            $table->text('genre_name');
        });
        Schema::table('genre', function (Blueprint $table) {
            $table->dropPrimary('genre_pkey');
            $table->primary('name');
        });

        Schema::table('genre_movie', function (Blueprint $table) {
            $table->foreign('genre_name')->references('name')->on('genre')->onDelete('cascade');
            $table->primary(['genre_name', 'movie_id']);
        });

        Schema::table('genre', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
};
