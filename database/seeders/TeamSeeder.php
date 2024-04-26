<?php

namespace Database\Seeders;

use App\Models\Team;
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
        Team::factory()->count(5)->create();
        $team = Team::all() ;
        $user_id = '1'; //admin
        foreach($team AS $key => $val){
            $val->members()->sync($user_id);
        }

    }
}
