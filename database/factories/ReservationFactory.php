<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Session;
use App\Models\User;
use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'session_id' => Session::factory(),
            'user_id' => User::factory(),
            'status' => ReservationStatus::RESERVED,
        ];
    }

    /**
     * Indicate that the reservation is canceled.
     */
    public function canceled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ReservationStatus::CANCELED,
        ]);
    }
}
