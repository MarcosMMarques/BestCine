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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // domínio (o que o usuário está comprando)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reservation_id')->constrained('reservation')->cascadeOnDelete();

            // status do pedido (ex: pending, paid, cancelled, expired)
            $table->string('status', 30)->index();

            // valores
            $table->integer('amount_total');

            // stripe identifiers
            $table->string('stripe_checkout_session_id')->nullable()->unique();
            $table->string('stripe_payment_intent_id')->nullable()->unique();
            $table->string('stripe_customer_id')->nullable()->index();

            // idempotência / antifraude
            $table->string('idempotency_key')->nullable()->unique();

            // controle e observabilidade
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
