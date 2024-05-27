<?php

namespace Database\Seeders;

use App\Models\Team;
use Faker\Factory as Faker;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();

        $team = Team::all();
        foreach($team as $key => $val){

            $payment_method = PaymentMethod::create([
                'team_id' =>  $val->id,
                'type' =>  'manual',
                'bank_name' =>  $faker->randomElement([
                    'Maybank',
                    'CIMB Group',
                    'Public Bank',
                    'RHB Bank',
                    'Hong Leong Bank',
                    'AmBank',
                ]),
                'account_name' =>  $faker->name,
                'bank_account' =>  sprintf("%04d", $faker->randomNumber()).'-'.sprintf("%04d", $faker->randomNumber()).'-'.sprintf("%04d", $faker->randomNumber()).'-'.sprintf("%04d", $faker->randomNumber()),
                'payment_gateway_id' =>  null,
                'status' =>  true,
            ]);

        }

    }

    
}
