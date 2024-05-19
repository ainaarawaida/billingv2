<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\TeamSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Team::factory()->count(3)->create();
        $team = Team::all() ;
        $user_id = '1'; //admin
        foreach($team AS $key => $val){
            $val->members()->sync($user_id);

            $teamSetting = TeamSetting::updateOrCreate(
                ['team_id' => $val->id], // Search by email
                [
                    'quotation_prefix_code' => '#Q',
                    'quotation_current_no' => 0,
                    'quotation_template' => 1,
                    'invoice_prefix_code' => '#I',
                    'invoice_current_no' => 0,
                    'invoice_template' => 1,
                    'recurring_invoice_prefix_code' => '#RI',
                    'recurring_invoice_current_no' => 0,
                ]
            );


        }

    }
}
