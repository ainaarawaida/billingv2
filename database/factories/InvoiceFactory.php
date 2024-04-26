<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
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
    
        $invoice_date = $this->faker->date();
        $pay_before = Carbon::parse($invoice_date)->addDays($this->faker->numberBetween(7, 30));
        return [
            //
            'customer_id' => $customer_id ,
            'team_id' => $team_id ,
            'numbering' => null, // Assuming unique numbering format
            'invoice_date' => $invoice_date,
            'pay_before' => $pay_before, // Valid days between 7 and 30
            'invoice_status' => $this->faker->randomElement([
                'draft',
                'new',
                'process',
                'done',
                'expired',
                'cancelled',
            ]),
            'title' => $this->faker->sentence,
            'notes' => $this->faker->paragraph,
            'sub_total' => null, // Subtotal between 1000 and 10000
            'taxes' => null, // Can be calculated based on percentage_tax and sub_total later
            'percentage_tax' => $this->faker->numberBetween(0, 20), // Tax percentage between 0 and 20
            'delivery' => $this->faker->randomFloat(2, 0, 100), // Delivery cost between 0 and 100
            'final_amount' => null, //
            'invoice_type' => $this->faker->randomElement([
                'One Time' => 'One Time',
                'Daily' => 'Daily',
                'Monthly' => 'Monthly',
                'Yearly' => 'Yearly',
            ]), //
            'payment_type' => [
                "ToyyibPay (FPX)"
            ], //
        ];
    }
}
