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
        Schema::table('movie_production_companie', function (Blueprint $table) {
            $table->dropPrimary('movie_production_companie_pkey');
            $table->dropConstrainedForeignId('production_companie_name');
        });
        Schema::table('production_companie', function (Blueprint $table) {
            $table->dropPrimary('name');
            $table->id();
        });
        Schema::table('movie_production_companie', function (Blueprint $table) {
            $table->foreignId('production_companie_id')->constrained(table: 'production_companie')->onDelete('cascade');
            $table->primary(['movie_id', 'production_companie_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movie_production_companie', function (Blueprint $table) {
            $table->dropConstrainedForeignId('prudction_companie_id');
            $table->text('production_companie_name');
        });
        Schema::table('genre', function (Blueprint $table) {
            $table->dropPrimary('production_companie_pkey');
            $table->primary('name');
        });

        Schema::table('movie_production_companie', function (Blueprint $table) {
            $table->foreign('production_companie_name')->references('name')->on('production_companie')->onDelete('cascade');
            $table->primary(['production_companie_name', 'movie_id']);
        });

        Schema::table('production_companie', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
};
