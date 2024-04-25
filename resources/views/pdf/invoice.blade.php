<?php

use App\Models\Item;
use App\Models\Team;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;

if(!isset($record)){
  $id = str_replace('luqmanahmadnordin', "", base64_decode($id)) ;
  $record = Invoice::where('id',$id)->first();
  $team = Team::where('id', $record->team_id)->first();
  $item = Item::with('product')->where('invoice_id', $record->id)->get();
  $customer = Customer::where('id', $record->customer_id)->first();
  // dd($customer);
}elseif(isset($record)){
  $id = $record->id ;
  $record = Invoice::where('id',$id)->first();
  $team = Team::where('id', $record->team_id)->first();
  $item = Item::with('product')->where('invoice_id', $record->id)->get();
  $customer = Customer::where('id', $record->customer_id)->first();
  
}

// dd($record);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Print Page</title>
  <!-- <link href="{{ url('css/bootstrap.min.css' )}}" rel="stylesheet" > -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >

</head>
<!-- <body onload="window.print()"> -->
<body>

<button class="btn-print btn btn-success m-3">Print / PDF</button>


  <div class="p-3">
    <div class="row">
      <div class="col">
  
        <div class="invoice-title d-flex justify-content-end align-items-start p-2 rounded-3 border-3 border-bottom border-success mb-3">
          <h2 class="text-right">Invoice </h2>
        </div>
  
        <div class="row">
          <div class="col">
            <div class="row">
              <div class="col-3"><b>From : </b></div>
              <div class="col-9">
                {{ $team->name }} <br>
                {{ $team->email }} <br>
                {{ $team->phone }} <br>
                
              </div>
            </div>
            <div class="row">
              <div class="col-3"><b>To : </b></div>
              <div class="col-9">
                {{ $customer->name }} <br>
                {{ $customer->email }} <br>
                {{ $customer->phone }} <br>
                
              </div>
            </div>
          </div>
          <div class="col d-flex flex-column justify-content-start align-items-end">
            <p class="h3">#I{{ $record->numbering }} </p>
            <div>Invoice Date: {{ date("j F, Y", strtotime($record->invoice_date) ) }}</div>
            <div>Pay Before: {{ date("j F, Y", strtotime($record->pay_before) ) }}</div>
          </div>
        </div>
  
        <div class="row">
          <div class="col">
            <strong>Status : </strong>
            <span class="badge bg-success">
              {{ $record->quote_status ?? 'draft' }}
            </span>
           
          </div>
         
        </div>
  
       
  
        <div class="row">
          <div class="col">
            {{ $record->title }}
          </div>
        </div>
  
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Item / Description</th>
                <th>Price </th>
                <th>Quantity</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($item as $key => $val){ ?>
              <tr>
                <td>{{ $key +1 }}</td>
                <td>{{ $val?->title }} {{ $val?->product?->title }}</td>
                <td>{{ $val?->price }}</td>
                <td>{{ $val?->quantity }} {{ $val?->unit }}</td>
                <td class="text-right">{{ $val?->total }}</td>
              </tr>
            <?php } ?>
  
             
              <tr>
                <td colspan="3"></td>
                <td><strong>Subtotal</strong></td>
                <td class="text-right"><strong>{{ $record->sub_total }}</strong></td>
              </tr>
              <tr>
                <td colspan="3"></td>
                <td><strong>Taxes</strong></td>
                <td class="text-right"><strong>{{ $record->taxes }}</strong></td>
              </tr>
              <tr>
                <td colspan="3"></td>
                <td><strong>Percentage tax</strong></td>
                <td class="text-right"><strong>{{ $record->percentage_tax }}</strong></td>
              </tr>
              <tr>
                <td colspan="3"></td>
                <td><strong>Delivery</strong></td>
                <td class="text-right"><strong>{{ $record->delivery }}</strong></td>
              </tr>
              <tr>
                <td colspan="3"></td>
                <td><h5 class="fw-bolder">Final amount</h5></td>
                <td class="text-right"><h5 class="fw-bolder">{{ $record->final_amount }}</h5></td>
              </tr>
  
            </tbody>
          </table>
        </div>
  
        <div class="row">
          <div class="col">
            <p>Thank you for your business!</p>
          </div>
        </div>
  
      </div>
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
          e.target.style.display = 'none';
          window.print();
          e.target.style.display = 'block';
        });
        

    });
  </script>


  </body>
</html>