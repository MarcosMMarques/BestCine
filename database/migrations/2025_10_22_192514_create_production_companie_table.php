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
        Schema::create('production_companie', function (Blueprint $table) {
            $table->text('name')->primary();
        });

        Schema::create('movie_production_companie', function (Blueprint $table) {
            $table->text('production_companie_name');

            $table->foreignId('movie_id')->constrained('movie')->onDelete('cascade');
            $table->foreign('production_companie_name')->references('name')->on('production_companie')->onDelete('cascade');

            $table->primary(['movie_id', 'production_companie_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_companie');
        Schema::dropIfExists('movie_production_companie');
    }
};
