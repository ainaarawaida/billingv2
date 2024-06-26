<?php

use App\Models\Item;
use App\Models\Team;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Customer;
use App\Models\TeamSetting;
use App\Models\PaymentMethod;
use App\Models\RecurringInvoice;

if (isset($id)) {
    $hashid = $id;
    $id = str_replace('luqmanahmadnordin', "", base64_decode($id));
    $recurring_invoice = RecurringInvoice::where('id', $id)->first();
    $team = Team::where('id', $recurring_invoice->team_id)->first();
    $invoices = Invoice::where('recurring_invoice_id', $recurring_invoice->id)->where('invoice_status', $status)->get();
    $customer = Customer::where('id', $recurring_invoice->customer_id)->first();
    $prefix = TeamSetting::where('team_id', $recurring_invoice->team_id)->first();
    $recurring_invoice_prefix = $prefix?->recurring_invoice_prefix ?? '#RI';
    $invoice_prefix = $prefix?->invoice_prefix_code ?? '#I';
    $paymentMethod = PaymentMethod::where('team_id', $recurring_invoice->team_id)
    ->where('status', 1)->get();

    $totalPayment = 0;
    $recurring_invoice->balance = 0;
} else{
 exit();
}

if(isset($_GET['payment_id'])){
    $payment_method = PaymentMethod::where('id', $_GET['payment_method_id'])->first();
    $payment_collection = json_decode(str_replace('luqmanahmadnordin', "", base64_decode($_GET['payment_id'])));
   
    if(collect($payment_collection)->pluck(['invoice_id'])->first()){
        $invoice_id_all = collect($payment_collection)->pluck(['invoice_id']) ;
        $invoices = Invoice::whereIn('id', $invoice_id_all->all())->get();
    }else{
        $invoice_id_all = collect($payment_collection)->first()->invoice_id_all ;
        $invoices = Invoice::whereIn('id', $invoice_id_all)->get();
    }
 
  
    
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Print Page</title>
    <!-- <link href="{{ url('css/bootstrap.min.css' )}}" rel="stylesheet" > -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
            @if (!isset($_GET['payment_id']))
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-cpu"></i> Status {{ ucwords($status) }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('recurringInvoicepdf/'.$hashid).'/draft' }}" class="dropdown-item">Draft</a></li>
                        <li><a href="{{ url('recurringInvoicepdf/'.$hashid).'/new' }}" class="dropdown-item">New</a></li>
                        <li><a href="{{ url('recurringInvoicepdf/'.$hashid).'/process' }}" class="dropdown-item">Process</a></li>
                        <li><a href="{{ url('recurringInvoicepdf/'.$hashid).'/done' }}" class="dropdown-item">Done</a></li>
                    </ul>
                </div>
                @if(!isset($payment_method_id) && $status == 'new' && $invoices->count() > 0)
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-credit-card"></i> Pay
                    </button>
                    <ul class="dropdown-menu">
                        @if (count($paymentMethod) > 0)
                            @foreach ($paymentMethod as $paymentMethodData)
                                @if ($paymentMethodData->payment_gateway_id == 1)
                                    <li><a href="#" class="dropdown-item">{{ $paymentMethodData->bank_name }}</a></li>
                                @elseif ($paymentMethodData->payment_gateway_id == 2)
                                    <li><a href="{{ url('online-payment/toyyibpay-recurring/'.$hashid.'/'.$paymentMethodData->id) }}" class="dropdown-item">{{ $paymentMethodData->bank_name }}</a></li>
                                @else
                                    <li><a href="#" data-detail="{{ base64_encode(json_encode($paymentMethodData->toArray())) }}" data-url="{{ url('online-payment/manual-payment-recurring/'.$hashid.'/'.$paymentMethodData->id) }}" class="dropdown-item payment-list">{{ $paymentMethodData->bank_name }}</a></li>
                                @endif

                            @endforeach
                            @else
                            <li><a href="#" class="dropdown-item">No Payment Available</a></li>
                        @endif
                    </ul>
                </div>
                @endif
            @endif
        @endif

        @if (Session::has('message'))
        <div class="mt-2 alert alert-warning alert-dismissible fade show">
            {{ Session::get('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @elseif (isset($status_payment))
        <div class="mt-2 alert alert-warning alert-dismissible fade show">
            {{ $status_payment }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if ($errors->any())
            <div class="mt-2 alert alert-danger alert-dismissible fade show">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>


    <div class="p-3">
        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center p-2">
                    <img src="{{ $team->photo ? asset('storage/'.$team->photo) : asset('image.psd.png')  }}" alt="User Avatar" class="img-thumbnail" width="100" height="100">
                    <h2 class="text-right">Recurring Invoice </h2>
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
                <p class="h3">{{ $recurring_invoice_prefix }}{{ $recurring_invoice->numbering }} </p>
                <div>Start Date: {{ date("j F, Y", strtotime($recurring_invoice->start_date) ) }}</div>
                <div>Stop Date: {{ date("j F, Y", strtotime($recurring_invoice->stop_date) ) }}</div>
            </div>
        </div>

        



        <div class="row">
            <div class="col">
                {{ $recurring_invoice->summary }}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoices</th>
                        <th>Date</th>
                        <th>Summary</th>
                        <th>Status</th>
                        <th>Amount (RM)</th>
                        <th>Balance (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($invoices))
                    @php
                        $final_balance = 0 ;
                    @endphp
                    <?php foreach ( $invoices as $key => $val) { ?>
                        <tr>
                            <td>{{ $key +1 }}</td>
                            <td>{{ $invoice_prefix.$val?->numbering }}</td>
                            <td>{{ date("j F, Y", strtotime($val?->invoice_date))  }}</td>
                            <td>{{ $val?->summary }}</td>
                            <td>
                                <span class="badge bg-success">
                                {{ ucwords($val?->invoice_status) }}
                                </span>
                            </td>
                            <td class="text-right">{{ $val?->final_amount }}</td>
                            <td class="text-right">{{ $val?->balance }}</td>
                        </tr>

                        @php
                            $final_balance += $val?->balance ;
                        @endphp
                    <?php } ?>
                    @endif

                 
                    <tr>
                        <td colspan="5"></td>
                        <td>
                            <h5 class="fw-bolder">Final Balance</h5>
                        </td>
                        <td class="text-right">
                            <h5 class="fw-bolder">{{ $final_balance }}</h5>
                        </td>
                    </tr>
                  
                    @if (isset($payment_collection))
                    @foreach($payment_collection AS $key2 => $val2)
                    <?php
                           
                    ?>
                    <tr>
                        <td colspan="5">
                            <span class="badge bg-success">
                                {{ ucwords($val2?->status ?? 'Processing') }}
                              
                            </span> 
                            @if($val2->invoice_id ?? false)
                                Paid using {{ $payment_method->name }} {{ $invoice_prefix.collect($invoices)->where('id', $val2->invoice_id)->first()->numbering  }} On {{ date("j F, Y H:i:s", strtotime($val2->updated_at) ) }}, {{ $val2->notes }}
                            @else
                                Paid using {{ $payment_method->name }} On {{ date("j F, Y H:i:s", strtotime($val2->updated_at) ) }}
                            @endif

                        
                        </td>
                        <td>
                            Paid
                        </td>
                        <td class="text-right">
                            {{ number_format($val2->total, 2)  }}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="5"></td>
                        <td>
                            <h5 class="fw-bolder">Total Paid</h5>
                        </td>
                        <td class="text-right">
                            <h5 class="fw-bolder">{{ number_format(collect($payment_collection)->sum('total'), 2)  }}</h5>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col">
                <p>{{ $recurring_invoice->terms_conditions}}</p>
                <p>{{ $recurring_invoice->footer}}</p>
            </div>
        </div>


    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <form id="mp-form" method="post" enctype="multipart/form-data">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Manual Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="mp-id-all" name="id-all">
                        <div class="mb-3">
                            <label for="recipient-bank_name" class="col-form-label">Bank Name</label>
                            <input type="text" readonly class="form-control" id="mp-bank_name" name="bank_name">
                        </div>
                        <div class="mb-3">
                            <label for="recipient-account_name" class="col-form-label">Account Name</label>
                            <input type="text" readonly class="form-control" id="mp-account_name" name="account_name">
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Bank Account</label>
                            <input type="text" readonly class="form-control" id="mp-bank_account" name="bank_account">
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" required class="form-control" id="mp-amount" name="amount">
                            @if ($errors->has('total'))
                                <span class="text-danger">{{ $errors->first('total') }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Reference</label>
                            <input type="text" class="form-control" name="reference" id="reference">
                            @if ($errors->has('reference'))
                                <span class="text-danger">{{ $errors->first('reference') }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Notes</label>
                            <textarea class="form-control" name="notes" id="notes"></textarea>
                            @if ($errors->has('notes'))
                                <span class="text-danger">{{ $errors->first('notes') }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="attachments" class="col-form-label">Receipt (docx,pdf,jpg,jpeg,png ) <span class="text-danger">*</span></label>
                            <input class="form-control" required accept=".docx,.pdf,.jpg,.jpeg,.png" type="file" name="attachments" id="attachments">
                            @if ($errors->has('attachments'))
                                <span class="text-danger">{{ $errors->first('attachments') }}</span>
                            @endif
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>






    <script>
        window.addEventListener('DOMContentLoaded', () => {
            let final_balance = <?php echo json_encode($final_balance ); ?>;
            let id_all = <?php echo json_encode(base64_encode(json_encode($invoices->pluck('id')->all())) ?? null ); ?>;
            let myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
                keyboard: false
            })


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

            document?.querySelector('.payment-list')?.addEventListener('click', (e) => {
                let detail = JSON.parse(atob(e.target.getAttribute('data-detail')));
                let actionurl = e.target.getAttribute('data-url');
                // console.log(detail, actionurl);
                document?.querySelector('#mp-form').setAttribute('action', actionurl);
                document.querySelector('#mp-bank_name').value = detail.bank_name;
                document.querySelector('#mp-account_name').value = detail.account_name;
                document.querySelector('#mp-id-all').value = id_all;
                document.querySelector('#mp-bank_account').value = detail.bank_account;
                document.querySelector('#mp-amount').value = final_balance.toFixed(2);
                myModal.toggle();
            });


        });
    </script>


</body>

</html>