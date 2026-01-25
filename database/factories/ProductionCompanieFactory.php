<?php

namespace Database\Factories;

use App\Models\ProductionCompanie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionCompanie>
 */
class ProductionCompanieFactory extends Factory
{
    protected $model = ProductionCompanie::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Pictures',
        ];
    }
}
