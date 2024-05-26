<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Customer;
use App\Mail\InvoiceEmail;
use Illuminate\Console\Command;
use App\Models\RecurringInvoice;
use Illuminate\Support\Facades\Mail;

class sendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-notification';

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
        $invoice = Invoice::where('invoice_status', 'new')
        ->where('invoice_date', Carbon::now()->format('Y-m-d'))->get();

        foreach($invoice AS $key => $val){
            $link_url = url('invoicepdf')."/".base64_encode("luqmanahmadnordin".$val->id);
            $customer = Customer::where('id', $val->customer_id)->first();   
            Mail::to($customer->email)
                ->send(new InvoiceEmail($val, $customer));

        }



    }
}
