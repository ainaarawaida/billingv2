<?php

use App\Models\Item;
use App\Models\Team;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\TeamSetting;

if(!isset($record)){
  $id = str_replace('luqmanahmadnordin', "", base64_decode($id)) ;
  $record = Invoice::where('id',$id)->first();
  $team = Team::where('id', $record->team_id)->first();
  $item = Item::with('product')->where('invoice_id', $record->id)->get();
  $customer = Customer::where('id', $record->customer_id)->first();
  $prefix = TeamSetting::where('team_id', $record->team_id )->first()->invoice_prefix_code ?? '#Q' ;
  
}elseif(isset($record)){
  $id = $record->id ;
  $record = Invoice::where('id',$id)->first();
  $team = Team::where('id', $record->team_id)->first();
  $item = Item::with('product')->where('invoice_id', $record->id)->get();
  $customer = Customer::where('id', $record->customer_id)->first();
  $prefix = TeamSetting::where('team_id', $record->team_id )->first()->invoice_prefix_code ?? '#Q' ;
   
}

// dd($record);

?>

<div class="m-3">

  <div class="actionbtn flex gap-2 m-3">

    <x-filament::button icon="heroicon-m-printer" class="bg-blue-500 btn-print">
      Print
    </x-filament::button>

    <x-filament::button icon="heroicon-m-banknotes" class="bg-green-500">
      Pay
    </x-filament::button>
 
     
  </div>

  <div class="px-4 py-8 flex flex-col bg-gray-100">
    <header class="flex justify-between items-center mb-8">
      <div class="flex items-center">
      <x-filament::avatar
            src="{{ asset('storage/'.$team->photo) }}"
            alt="Company Logo"
            :circular="true"
            size="lg"
        />

        <div class="ml-4">
          <h1 class="text-xl font-bold tracking-tight text-gray-800">{{ $team->name }}</h1>
          <p class="text-sm text-gray-600">{{ $team->address }}</p>
          <p class="text-sm text-gray-600">{{ $team->email }}</p>
          <p class="text-sm text-gray-600">{{ $team->phone }}</p>

        </div>
      </div>
      <div class="text-right">
        <h2 class="text-xl font-bold tracking-tight text-gray-800">Invoice</h2>
        <p class="text-sm text-gray-600">{{ $prefix }}{{ $record->numbering }}</p>
        <p class="text-sm text-gray-600">Invoice Date: {{ date("j F, Y", strtotime($record->invoice_date) ) }}</p>
        <p class="text-sm text-gray-600">Pay Before: {{ date("j F, Y", strtotime($record->pay_before) ) }}</p>
        
      </div>
    </header>

    <main class="mb-8">
      <h3 class="text-lg font-bold tracking-tight text-gray-800">To:</h3>
      <div class="flex flex-col space-y-2 pt-2">
        <p class="text-sm text-gray-600"><span id="client-name">{{ $customer->name }}</span></p>
        <p class="text-sm text-gray-600"><span id="client-address">{{ $customer->address }}</span></p>
        <p class="text-sm text-gray-600"><span id="client-email">{{ $customer->email }}</span></p>
        <p class="text-sm text-gray-600"><span id="client-email">{{ $customer->phone }}</span></p>
      </div>
    </main>

    <section class="overflow-x-auto rounded-lg shadow mb-8">
      <table class="w-full min-w-8xl table-auto">
        <thead>
          <tr class="text-left bg-gray-200 text-sm font-medium">
            <th class="px-4 py-2">Item</th>
            <th class="px-4 py-2">Description</th>
            <th class="px-4 py-2 text-right">Qty</th>
            <th class="px-4 py-2 text-right">Price</th>
            <th class="px-4 py-2 text-right">Amount</th>
          </tr>
        </thead>
        <tbody id="invoice-items">
            <tr class="">
                <td class="px-4 py-2">Item</td>
                <td class="px-4 py-2">Description</td>
                <td class="px-4 py-2 text-right">Qty</td>
                <td class="px-4 py-2 text-right">Price</td>
                <td class="px-4 py-2 text-right">Amount</td>
            </tr>
            <tr class="">
                <td class="px-4 py-2">Item</td>
                <td class="px-4 py-2">Description</td>
                <td class="px-4 py-2 text-right">Qty</td>
                <td class="px-4 py-2 text-right">Price</td>
                <td class="px-4 py-2 text-right">Amount</td>
            </tr>
            <tr class="">
                <td class="px-4 py-2">Item</td>
                <td class="px-4 py-2">Description</td>
                <td class="px-4 py-2 text-right">Qty</td>
                <td class="px-4 py-2 text-right">Price</td>
                <td class="px-4 py-2 text-right">Amount</td>
            </tr>
          </tbody>
        <tfoot>
          <tr class="text-left font-medium border-t border-gray-200">
            <td colspan="4" class="px-4 py-4">Subtotal:</td>
            <td class="px-4 py-4 text-right" id="subtotal"></td>
          </tr>
          <tr class="text-left font-medium">
            <td colspan="4" class="px-4 py-4">Tax (optional):</td>
            <td class="px-4 py-4 text-right" id="tax"></td>
          </tr>
          <tr class="text-left font-bold border-t border-b border-gray-200">
            <td colspan="4" class="px-4 py-4">Total:</td>
            <td class="px-4 py-4 text-right" id="total"></td>
          </tr>
        </tfoot>
        </table>
    </section>
  </div>
   
</div>


<script>
    window.addEventListener('DOMContentLoaded', () => {
 
        window.onfocus = function() {
          // window.close();
        }
        window.onafterprint = function(e) {
            // window.close();
        }

        document.querySelector('.btn-print').addEventListener('click', (e) => {
          document.querySelector('.actionbtn').style.display = 'none';
          window.print();
          document.querySelector('.actionbtn').style.display = 'block';
        });
        

    });
</script>