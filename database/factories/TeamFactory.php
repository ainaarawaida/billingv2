<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
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
            'name' => $this->faker->company,
            'slug' => $this->faker->unique()->slug, // Generate a unique slug
            'email' => $this->faker->unique()->safeEmail,
            'phone' => str_pad("03" . $this->faker->numerify('########'), 10, '0', STR_PAD_LEFT),
            'ssm' => $this->faker->randomNumber(7, true), // Assuming SSM is a 7-digit number
            'address' => $this->faker->address,
            'poscode' => $this->faker->postcode,
            'city' => $this->faker->city,
            'state' => $this->faker->randomElement([
                'JHR',
                'KDH',
                'KTN',
                'MLK',
                'NSN',
                'PHG',
                'PRK',
                'PLS',
                'PNG',
                'SBH',
                'SWK',
                'SGR',
                'TRG',
                'KUL',
                'LBN',
                'PJY'
            ]),
            'photo' => null,
        ];
    }
}
