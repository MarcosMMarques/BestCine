<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    protected $model = Movie::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tmdb_id' => fake()->unique()->numberBetween(1000, 999999),
            'title' => fake()->sentence(3),
            'synopsis' => fake()->paragraph(),
            'length' => fake()->numberBetween(80, 180),
            'image' => fake()->imageUrl(500, 750),
            'backdrop_url' => fake()->imageUrl(1920, 1080),
            'poster_url' => fake()->imageUrl(500, 750),
            'trailer_url' => 'https://www.youtube.com/watch?v=' . fake()->regexify('[A-Za-z0-9]{11}'),
            'tagline' => fake()->sentence(),
            'release_date' => fake()->date(),
        ];
    }
}
