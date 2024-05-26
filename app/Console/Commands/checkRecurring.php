<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Invoice;
use App\Models\TeamSetting;
use Illuminate\Console\Command;
use App\Models\RecurringInvoice;

class checkRecurring extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        // 'Daily' => 'Daily',
        // 'Monthly' => 'Monthly',
        // 'Yearly' => 'Yearly',

        $getallRecurring = RecurringInvoice::where('status', 1)
        ->whereIn('every', ['Daily', 'Monthly', 'Yearly'])->get();

        foreach($getallRecurring AS $key => $val){
            $latestInvoice = Invoice::where('team_id', $val->team_id)
            ->where('recurring_invoice_id', $val->id) 
            ->orderBy('invoice_date', 'desc')->first();  

            $endDate = Carbon::parse(date('Y-m-d'))->addDays($val->generate_before);
            $startDate = Carbon::parse($latestInvoice->invoice_date) ;
           

            if($val->every == 'Daily'){
                $days = $endDate->diffInDays($startDate);
                for($a = 0 ; $a < $days; $a ++ ){
                    $startDate->addDays(1);
                    if($startDate->format('Y-m-d') >= $val->stop_date){
                        break ;
                    }
                    $team_setting = TeamSetting::where('team_id', $latestInvoice->team_id )->first();
                    $invoice_current_no = $team_setting->invoice_current_no ?? '0' ;    
                    $team_setting->invoice_current_no = $invoice_current_no + 1 ;
                    $team_setting->save();
                 
                    $invoice =  Invoice::create([
                        'customer_id' => $latestInvoice->customer_id ,
                        'team_id' => $latestInvoice->team_id ,
                        'numbering' => str_pad(($invoice_current_no + 1), 6, "0", STR_PAD_LEFT),
                        'invoice_date' => $startDate->format('Y-m-d'),
                        'pay_before' => date('Y-m-d', strtotime($startDate->format('Y-m-d') . ' + 1 day')), // Valid days between 7 and 30
                        'invoice_status' => 'new',
                        'summary' => $latestInvoice->summary,
                        'sub_total' => $latestInvoice->sub_total, // Subtotal between 1000 and 10000
                        'taxes' => $latestInvoice->taxes, // Can be calculated based on percentage_tax and sub_total later
                        'percentage_tax' => $latestInvoice->percentage_tax, // Tax percentage between 0 and 20
                        'delivery' => $latestInvoice->delivery, // Delivery cost between 0 and 100
                        'final_amount' => $latestInvoice->final_amount, //
                        'balance' => $latestInvoice->final_amount, //
                        'recurring_invoice_id' => $val->id, //
                        'terms_conditions' => $latestInvoice->terms_conditions, //
                        'footer' => $latestInvoice->footer, //
                    ]);

                    dump("create invoice ". $invoice->numbering);

                    foreach ($latestInvoice->items()->get() as $key2 => $val2){
                        $item = Item::create([
                            'invoice_id' => $invoice->id,
                            'product_id' => $val2->id,
                            'title' => $val2->title,
                            'price' => $val2->price,
                            'tax' => $val2->tax,
                            'quantity' => $val2->quantity,
                            'unit' => $val2->unit,
                            'total' => $val2->total,
                        ]);
    
                    }

                }
                
            }elseif($val->every == 'Monthly'){
                $months = $endDate->diffInMonths($startDate);
                for($a = 0 ; $a < $months; $a ++ ){
                    $startDate->addMonths(1);
                    if($startDate->format('Y-m-d') >= $val->stop_date){
                        break ;
                    }
                    $team_setting = TeamSetting::where('team_id', $latestInvoice->team_id )->first();
                    $invoice_current_no = $team_setting->invoice_current_no ?? '0' ;    
                    $team_setting->invoice_current_no = $invoice_current_no + 1 ;
                    $team_setting->save();
                 
                    $invoice =  Invoice::create([
                        'customer_id' => $latestInvoice->customer_id ,
                        'team_id' => $latestInvoice->team_id ,
                        'numbering' => str_pad(($invoice_current_no + 1), 6, "0", STR_PAD_LEFT),
                        'invoice_date' => $startDate->format('Y-m-d'),
                        'pay_before' => date('Y-m-d', strtotime($startDate->format('Y-m-d') . ' + 1 day')), // Valid days between 7 and 30
                        'invoice_status' => 'new',
                        'summary' => $latestInvoice->summary,
                        'sub_total' => $latestInvoice->sub_total, // Subtotal between 1000 and 10000
                        'taxes' => $latestInvoice->taxes, // Can be calculated based on percentage_tax and sub_total later
                        'percentage_tax' => $latestInvoice->percentage_tax, // Tax percentage between 0 and 20
                        'delivery' => $latestInvoice->delivery, // Delivery cost between 0 and 100
                        'final_amount' => $latestInvoice->final_amount, //
                        'balance' => $latestInvoice->final_amount, //
                        'recurring_invoice_id' => $val->id, //
                        'terms_conditions' => $latestInvoice->terms_conditions, //
                        'footer' => $latestInvoice->footer, //
                    ]);

                    dump("create invoice ". $invoice->numbering);

                    foreach ($latestInvoice->items()->get() as $key2 => $val2){
                        $item = Item::create([
                            'invoice_id' => $invoice->id,
                            'product_id' => $val2->id,
                            'title' => $val2->title,
                            'price' => $val2->price,
                            'tax' => $val2->tax,
                            'quantity' => $val2->quantity,
                            'unit' => $val2->unit,
                            'total' => $val2->total,
                        ]);
    
                    }

                }

            }elseif($val->every == 'Yearly'){
                $years = $endDate->diffInYears($startDate);
                for($a = 0 ; $a < $years; $a ++ ){
                    $startDate->addYears(1);
                    if($startDate->format('Y-m-d') >= $val->stop_date){
                        break ;
                    }
                    $team_setting = TeamSetting::where('team_id', $latestInvoice->team_id )->first();
                    $invoice_current_no = $team_setting->invoice_current_no ?? '0' ;    
                    $team_setting->invoice_current_no = $invoice_current_no + 1 ;
                    $team_setting->save();
                 
                    $invoice =  Invoice::create([
                        'customer_id' => $latestInvoice->customer_id ,
                        'team_id' => $latestInvoice->team_id ,
                        'numbering' => str_pad(($invoice_current_no + 1), 6, "0", STR_PAD_LEFT),
                        'invoice_date' => $startDate->format('Y-m-d'),
                        'pay_before' => date('Y-m-d', strtotime($startDate->format('Y-m-d') . ' + 1 day')), // Valid days between 7 and 30
                        'invoice_status' => 'new',
                        'summary' => $latestInvoice->summary,
                        'sub_total' => $latestInvoice->sub_total, // Subtotal between 1000 and 10000
                        'taxes' => $latestInvoice->taxes, // Can be calculated based on percentage_tax and sub_total later
                        'percentage_tax' => $latestInvoice->percentage_tax, // Tax percentage between 0 and 20
                        'delivery' => $latestInvoice->delivery, // Delivery cost between 0 and 100
                        'final_amount' => $latestInvoice->final_amount, //
                        'balance' => $latestInvoice->final_amount, //
                        'recurring_invoice_id' => $val->id, //
                        'terms_conditions' => $latestInvoice->terms_conditions, //
                        'footer' => $latestInvoice->footer, //
                    ]);

                    dump("create invoice ". $invoice->numbering);

                    foreach ($latestInvoice->items()->get() as $key2 => $val2){
                        $item = Item::create([
                            'invoice_id' => $invoice->id,
                            'product_id' => $val2->id,
                            'title' => $val2->title,
                            'price' => $val2->price,
                            'tax' => $val2->tax,
                            'quantity' => $val2->quantity,
                            'unit' => $val2->unit,
                            'total' => $val2->total,
                        ]);
    
                    }

                }

            }
           

        }



    }
}
