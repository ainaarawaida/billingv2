<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'title' => $this->faker->sentence(3), // Generate a 3-word sentence for title
            'team_id' => $this->faker->randomElement(Team::all()->pluck('id')->toArray()), // Set to null for now (can be overridden later)
            'tax' => $this->faker->numberBetween(0, 1), // Generate random tax between 0 and 20 (2 decimal places)
            'quantity' => $this->faker->numberBetween(1, 5), // Generate random quantity between 0 and 100
            'price' => $this->faker->numberBetween(1, 100), // Generate random 5-digit price (assuming whole numbers)
        ];
    }
}
