<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'luqman',
            'email' => 'admin@admin.test',
            'password' => Hash::make('admin'),
        ]);
        
        // Post::factory()
        //     ->count(25)
        //     ->create();

        Notification::make()
            ->title('Welcome to Sistem Billing')
            ->body('You can try this billing system')
            ->success()
            ->sendToDatabase($user);

        $this->call([
            TeamSeeder::class,
            RoleSeeder::class,
            PaymentMethodSeeder::class,
            CustomerSeeder::class, // Example seeder class
            ProductSeeder::class,
            QuotationSeeder::class, 
            InvoiceSeeder::class,   // Example seeder class
            RecurringInvoiceSeeder::class,
            PaymentSeeder::class,
        ]);

    }
}
