<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Invoice;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        
        //
        // Invoice::factory()->count(20)->create();
        for ($i = 0; $i < 30; $i++) {
            $invoice = Invoice::factory()->create();
            $lastid = Invoice::where('team_id', $invoice->team_id)->count('id') ;
            $numbering = str_pad($lastid, 6, "0", STR_PAD_LEFT) ;
            $invoice->update(['numbering' => $numbering]);
            echo $lastid. " ".$numbering . PHP_EOL;


            //item
            $itemlist = $faker->numberBetween(1, 5) ;
            $final_amount = 0 ;
            $sub_total = 0 ;
            $taxes = 0 ;

            for ($j = 0; $j < $itemlist; $j++) {

                $product = Product::where('team_id', $invoice->team_id)
                            ->inRandomOrder()
                            ->first() ?? null;
                if(!$product)
                    continue;

                $total = $product->price * $product->quantity;

                $item = Item::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->price,
                    'tax' => $product->tax,
                    'quantity' => $product->quantity,
                    'unit' => $faker->randomElement([
                        'Unit' => 'Unit',
                        'Kg' => 'Kg',
                        'Gram' => 'Gram',
                        'Box' => 'Box',
                        'Pack' => 'Pack',
                        'Day' => 'Day',
                        'Month' => 'Month',
                        'Year' => 'Year',
                        'People' => 'People',
                    ]),
                    'total' => $total,
                ]);

                $sub_total = $sub_total + $total;
                if($product->tax){
                    $taxes = $taxes + ($invoice->percentage_tax / 100 * $total);

                }



            }

            $final_amount = ($sub_total + $taxes + $invoice->delivery);

            $invoice->update([
                'sub_total' => $sub_total,
                'taxes' => $taxes,
                'final_amount' => $final_amount,
            
            ]);



        }
    }
}
