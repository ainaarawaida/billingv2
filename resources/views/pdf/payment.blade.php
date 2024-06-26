<?php

use App\Models\Item;
use App\Models\Team;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Customer;
use App\Models\TeamSetting;
use App\Models\PaymentMethod;

if (isset($id)) {
    $hashid = $id;
    $id = str_replace('luqmanahmadnordin', "", base64_decode($id));
    $payment = Payment::where('id', $id)->first();
    $record = Invoice::where('id', $payment->invoice_id)->first();
    $team = Team::where('id', $payment->team_id)->first();

    $item = Item::with('product')->where('invoice_id', $record?->id)->get();
    $customer = Customer::where('id', $record?->customer_id)->first();
    $prefix = TeamSetting::where('team_id', $record?->team_id)->first()->invoice_prefix_code ?? '#I';

    // dd($payment);
} else{
    exit();
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
        <a href="{{ url('paymentpdf').'/'.base64_encode('luqmanahmadnordin'.$record->id) }}">
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
                    <h2 class="text-right">Payment </h2>
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
                        @if($record)
                        {{ $customer->name }} <br>
                        {{ $customer->email }} <br>
                        {{ $customer->phone }} <br>
                        @else
                        {{ __('No Customer') }}
                        @endif

                    </div>
                </div>
            </div>
            <div class="col d-flex flex-column justify-content-start align-items-end">
                <p class="h3">
                    @if($record)
                    {{ $prefix }}{{ $record->numbering }} 
                    @else
                    {{ __('No Invoice') }}
                    @endif
                </p>
                <div>Payment Date: {{ date("j F, Y", strtotime($payment->payment_date) ) }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <strong>Status : </strong>
                <span class="badge bg-success">
                    {{ $payment->payment_status ? ucwords($payment->payment_status) : 'Draft' }}
                </span>

            </div>

        </div>



        <div class="row">
            <div class="col">
                {{ $payment->notes }}
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
                    @if($record)
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
                        <td>
                            <h5 class="fw-bolder">Balance</h5>
                        </td>
                        <td class="text-right">
                            <h5 class="fw-bolder">{{ $record->balance }}</h5>
                        </td>
                    </tr>
                    @endif
                    @if (isset($payment))
                    <tr>
                        <td colspan="3">
                            <span class="badge bg-success">
                                {{ ucwords($payment->status) }}
                            </span> Paid On {{ date("j F, Y H:i:s", strtotime($payment->updated_at) ) }}, {{ $payment->notes }}
                        </td>
                        <td>
                            <h5 class="fw-bolder">Paid</h5>
                        </td>
                        <td class="text-right">
                            <h5 class="fw-bolder">{{ number_format($payment->total, 2)  }}</h5>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col">
                @if($record)
                <p>{{ $record->terms_conditions}}</p>
                <p>{{ $record->footer}}</p>
                @endif
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
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Payment Name</label>
                            <input type="text" readonly class="form-control" id="mp-name">
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Bank Account</label>
                            <input type="text" readonly class="form-control" id="mp-bank_account">
                        </div>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" required class="form-control" id="mp-amount" name="total" id="amount">
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
            let balance = <?php echo json_encode($record?->balance ); ?>;
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
                console.log(detail, actionurl);
                document?.querySelector('#mp-form').setAttribute('action', actionurl);
                document.querySelector('#mp-name').value = detail.name;
                document.querySelector('#mp-bank_account').value = detail.bank_account;
                document.querySelector('#mp-amount').value = balance;
                myModal.toggle();
            });


        });
    </script>


</body>

</html>