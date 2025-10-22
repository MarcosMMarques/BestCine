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
        Schema::create('actor', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->text('name')->nullable(false);
            $table->text('profile_path')->nullable();
        });

        Schema::create('actor_movie', function (Blueprint $table) {
            $table->unsignedInteger('actor_id');
            $table->foreign('actor_id')->references('id')->on('actor')->onDelete('cascade');

            $table->foreignId('movie_id')->constrained('movie')->onDelete('cascade');

            $table->primary(['actor_id', 'movie_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actor');
        Schema::dropIfExists('actor_movie');
    }
};
