<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Team;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecurringInvoice>
 */
class RecurringInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $team_id = $this->faker->randomElement(Team::all()->pluck('id')->toArray());
        $customer_id = $this->faker->randomElement(Customer::where('team_id', $team_id)->pluck('id')->toArray());
    
        
        return [
            //
            'team_id' => $team_id ,
            'customer_id' => $customer_id ,
            'numbering' => null ,
            'summary' =>  $this->faker->sentence ,
            'start_date' => Carbon::createFromTimestamp(rand(strtotime('2020-01-01'), 
            strtotime(date('Y-m-d', strtotime("next year January 1 - 1 day"))))) ,
            'stop_date' => null ,
            'every' => $this->faker->randomElement([
                'One Time',
                'Daily',
                'Monthly',
                'Yearly',
            ]) ,
            'generate_before' => $this->faker->numberBetween(0, 5) * $this->faker->numberBetween(0, 5),
            'status' => $this->faker->boolean ,
            'terms_conditions' => $this->faker->sentence,
            'footer' => $this->faker->sentence,
            'attachments' => null ,
           
        ];
    }
}
