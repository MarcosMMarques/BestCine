<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE TYPE seat_status AS ENUM ('AVAILABLE', 'RESERVED');");

        Schema::create('seat', function (Blueprint $table) {
            $table->id();
            $table->integer('row');
            $table->integer('number');
            $table->enum('status', ['AVAILABLE', 'RESERVED']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat');
        DB::statement("DROP TYPE seat_status");
    }
};
