<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
 

        Permission::create(['name' => 'view-any']);
        $roleAdmin = Role::create(['name' => 'admin','guard_name' => 'web']);
        $roleCustomer = Role::create(['name' => 'customer','guard_name' => 'web']);
        $roleAdmin->givePermissionTo(['view-any']);
        $roleCustomer->givePermissionTo(['view-any']);
        $user = User::all();
        foreach($user AS $key => $val){
            $val->assignRole($roleAdmin);
            $val->assignRole($roleCustomer);
        }

        
    
    }
}
