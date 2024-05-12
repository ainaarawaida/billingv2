<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // protected $model = Customer::class;

    public function definition(): array
    {

        // dd(Team::all()->pluck('name')->toArray());
        return [
            //
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => str_pad("01" . $this->faker->numerify('########'), 10, '0', STR_PAD_LEFT),
            'company' => $this->faker->company,
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
            'team_id' => $this->faker->randomElement(Team::all()->pluck('id')->toArray()), // Set to null for
        ];
    }
}
