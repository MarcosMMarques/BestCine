<?php

namespace Database\Factories;

use App\Models\Session;
use App\Models\Movie;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    protected $model = Session::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movie_id' => Movie::factory(),
            'room_id' => Room::factory(),
            'datetime' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d H:i:s'),
        ];
    }
}
