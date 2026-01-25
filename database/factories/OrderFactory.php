<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Reservation;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reservation_id' => Reservation::factory(),
            'status' => fake()->randomElement(OrderStatus::cases()),
            'amount_total' => fake()->numberBetween(1000, 5000),
            'stripe_checkout_session_id' => 'cs_test_' . fake()->uuid(),
            'stripe_payment_intent_id' => 'pi_' . fake()->uuid(),
            'stripe_customer_id' => 'cus_' . fake()->uuid(),
            'idempotency_key' => fake()->uuid(),
            'metadata' => [
                'source' => 'test',
            ],
        ];
    }
}
