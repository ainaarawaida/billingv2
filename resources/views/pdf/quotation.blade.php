<?php

use App\Models\Item;
use App\Models\Team;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\TeamSetting;
use Filament\Facades\Filament;

if (!isset($record)) {
  $id = str_replace('luqmanahmadnordin', "", base64_decode($id));
  $record = Quotation::where('id', $id)->first();
  $team = Team::where('id', $record->team_id)->first();
  $item = Item::with('product')->where('quotation_id', $record->id)->get();
  $customer = Customer::where('id', $record->customer_id)->first();
  $prefix = TeamSetting::where('team_id', $record->team_id)->first()->quotation_prefix_code ?? '#Q';
  // dd($customer);
} elseif (isset($record)) {
  $id = $record->id;
  $record = Quotation::where('id', $id)->first();
  $team = Team::where('id', $record->team_id)->first();
  $item = Item::with('product')->where('quotation_id', $record->id)->get();
  $customer = Customer::where('id', $record->customer_id)->first();
  $prefix = TeamSetting::where('team_id', $record->team_id)->first()->quotation_prefix_code ?? '#Q';
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Print Page</title>
  <!-- <link href="{{ url('css/bootstrap.min.css' )}}" rel="stylesheet" > -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<!-- <body onload="window.print()"> -->

<body>

<div class="btn-action m-3">
@if(isset($mailtype))
        <a href="{{ url('quotationpdf').'/'.base64_encode('luqmanahmadnordin'.$record->id) }}">
            <button class="btn btn-success btn-print">
                    <i class="bi bi-printer"></i> View
            </button>
        </a>
        @else
            <button class="btn btn-success btn-print">
                    <i class="bi bi-printer"></i> Print / PDF
            </button>
          
        @endif
    </div>


  <div class="p-3">
    <div class="row">
      <div class="col">
        <div class="d-flex justify-content-between align-items-center p-2">
          <img src="{{ $team->photo ? asset('storage/'.$team->photo) : asset('image.psd.png')  }}" alt="User Avatar" class="img-thumbnail" width="100" height="100">
          <h2 class="text-right">Invoice </h2>
        </div>

      </div>
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
        <p class="h3">{{ $prefix }}{{ $record->numbering }} </p>
        <div>Issue Date: {{ date("j F, Y", strtotime($record->quotation_date) ) }}</div>
        <?php $validday = '+ ' . $record->valid_days . ' days'; ?>
        <div>Due Date: {{ date("j F, Y", strtotime($validday , strtotime($record->quotation_date)) ) }}</div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <strong>Status : </strong>
        <span class="badge bg-success">
          {{ $record->quote_status ? ucwords($record->quote_status) : 'Draft' }}
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
            <th>Price (RM)</th>
            <th>Quantity</th>
            <th>Total (RM)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($item as $key => $val) { ?>
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
            <td>
              <h5 class="fw-bolder">Final amount</h5>
            </td>
            <td class="text-right">
              <h5 class="fw-bolder">{{ $record->final_amount }}</h5>
            </td>
          </tr>

        </tbody>
      </table>
    </div>

    <div class="row">
      <div class="col">
          <p>{{ $record->terms_conditions}}</p>
          <p>{{ $record->footer}}</p>
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

      document?.querySelector('.btn-print')?.addEventListener('click', (e) => {
                document.querySelector('.btn-action').style.display = 'none';
                window.print();
                document.querySelector('.btn-action').style.display = 'block';
            });


    });
  </script>


</body>

</html>