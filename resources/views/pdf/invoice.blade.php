<?php

use App\Models\Item;
use App\Models\Team;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Customer;
use App\Models\TeamSetting;
use App\Models\PaymentMethod;

if (!isset($record)) {
    $hashid = $id;
    $id = str_replace('luqmanahmadnordin', "", base64_decode($id));
    $record = Invoice::where('id', $id)->first();
    $team = Team::where('id', $record->team_id)->first();
    $item = Item::with('product')->where('invoice_id', $record->id)->get();
    $customer = Customer::where('id', $record->customer_id)->first();
    $prefix = TeamSetting::where('team_id', $record->team_id)->first()->invoice_prefix_code ?? '#Q';
} elseif (isset($record)) {
    $hashid = base64_encode('luqmanahmadnordin'.$record->id);
    $id = $record->id;
    $record = Invoice::where('id', $id)->first();
    $team = Team::where('id', $record->team_id)->first();
    $item = Item::with('product')->where('invoice_id', $record->id)->get();
    $customer = Customer::where('id', $record->customer_id)->first();
    $prefix = TeamSetting::where('team_id', $record->team_id)->first()->invoice_prefix_code ?? '#Q';
}

    $paymentMethod = PaymentMethod::where('team_id', $record->team_id )
    ->where('status', 1)->get();

  
    if(isset($payment_method_id)){
        $payment_type = $paymentMethod->where('id', $payment_method_id)->first();
        
        if($payment_type->payment_gateway_id == '2'){ //toyyibpay
            if(isset($_GET['billcode'])){
                if($_GET['status_id'] == 1){
                   $status_payment = 'processing';
                   $status_invoice = 'process';
                }elseif($_GET['status_id'] == 2){
                    $status_payment = 'on_hold'; 
                    $status_invoice = 'process';
                }elseif($_GET['status_id'] == 3){
                    $status_payment = 'failed'; 
                    $status_invoice = 'process';
                }else{
                    $status_payment = 'on_hold';  
                    $status_invoice = 'process';
                }
                
                $payment = Payment::firstOrCreate(
                    ['reference' => $_GET['transaction_id']], 
                    [
                        'team_id' => $record->team_id,
                        'invoice_id' => $record->id,
                        'payment_method_id' => $paymentMethod->where('payment_gateway_id',2)->first()->id,
                        'payment_date' => date('Y-m-d'),
                        'total' => $record->balance,
                        'notes' => 'billcode:'.$_GET['billcode'].' transaction id:'.$_GET['transaction_id'],
                        'reference' => $_GET['transaction_id'],
                        'status' => $status_payment,
                    ]
                );
                $record->invoice_status = $status_invoice;
        
        
            }

        }

    }

    $totalPayment = Payment::where('team_id', $record->team_id)
    ->where('invoice_id', $record->id)
    ->where('status', 'completed')->sum('total');

?>

<!DOCTYPE html>
<html>

<head>
    <title>Print Page</title>
    <!-- <link href="{{ url('css/bootstrap.min.css' )}}" rel="stylesheet" > -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" ></script>

</head>
<!-- <body onload="window.print()"> -->

<body>

    <div class="btn-action m-3">
        @if(isset($mailtype))
        <a href="{{ url('invoicepdf').'/'.base64_encode('luqmanahmadnordin'.$record->id) }}">
            <button class="btn btn-success btn-print">
                    <i class="bi bi-printer"></i> View
            </button>
        </a>
        @else
            <button class="btn btn-success btn-print">
                    <i class="bi bi-printer"></i> Print / PDF
            </button>
            @if(!isset($payment))
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-credit-card"></i> Pay
                </button>
                <ul class="dropdown-menu">
                    @if (count($paymentMethod) > 0)
                        @foreach ($paymentMethod as $paymentMethodData)
                            @if ($paymentMethodData->payment_gateway_id == 1)  
                            <li><a href="#" class="dropdown-item">{{ $paymentMethodData->name }}</a></li>
                            @elseif ($paymentMethodData->payment_gateway_id == 2) 
                            <li><a href="{{ url('online-payment/toyyibpay/'.$hashid.'/'.$paymentMethodData->id) }}" class="dropdown-item">{{ $paymentMethodData->name }}</a></li>
                            @else
                            <li><a href="#" class="dropdown-item">{{ $paymentMethodData->name }}</a></li>
                            @endif
                           
                        @endforeach
                    @else
                        <li><a href="#" class="dropdown-item">No Payment Available</a></li>
                    @endif
                </ul>
            </div>
            @endif

        @endif

        @if (Session::has('message'))
        <div class="alert alert-danger">
            {{ Session::get('message') }}
        </div>
        @endif
    </div>


    <div class="p-3">
       


        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center p-2">
                    <img src="{{ asset('storage/'.$team->photo)  }}" alt="User Avatar" class="img-thumbnail rounded-circle" width="100" height="100">
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
                <div>Invoice Date: {{ date("j F, Y", strtotime($record->invoice_date) ) }}</div>
                <div>Pay Before: {{ date("j F, Y", strtotime($record->pay_before) ) }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <strong>Status : </strong>
                <span class="badge bg-success">
                    {{ $record->invoice_status ? ucwords($record->invoice_status) : 'Draft' }}
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
                    <tr>
                        <td colspan="3"></td>
                        <td><strong>Total Completed Payment</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalPayment, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td>
                            <h5 class="fw-bolder">Balance</h5>
                        </td>
                        <td class="text-right">
                            <h5 class="fw-bolder">{{ $record->balance  }}</h5>
                        </td>
                    </tr>
                    @if (isset($payment))
                    <tr>
                        <td colspan="3">
                        <span class="badge bg-success">
                        {{ ucwords($payment->status) }}
                        </span> Paid On {{ date("j F, Y H:i:s", strtotime($payment->updated_at) ) }}, {{ $payment->notes }}</td>
                        <td>
                            <h5 class="fw-bolder">Paid</h5>
                        </td>
                        <td class="text-right">
                            <h5 class="fw-bolder">{{ $record->balance  }}</h5>
                        </td>
                    </tr>
                    @endif
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

            document.querySelector('.btn-print').addEventListener('click', (e) => {
                document.querySelector('.btn-action').style.display = 'none';
                window.print();
                document.querySelector('.btn-action').style.display = 'block';
            });


        });
    </script>


</body>

</html>