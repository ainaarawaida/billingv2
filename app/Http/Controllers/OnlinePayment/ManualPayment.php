<?php

namespace App\Http\Controllers\OnlinePayment;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TeamSetting;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ManualPayment extends Controller
{
    //
    function index($id, $payment_method_id){
        $hashid = $id;
        $id = str_replace('luqmanahmadnordin', "", base64_decode($id));
        $invoice = Invoice::find($id);
        $record = $invoice ;

        $validatedData = request()->validate([
            'attachments' => 'required|file|mimes:pdf,jpg,jpeg,png|max:9048',
            'total' => 'required|numeric', // Adjust validation rules as needed
        ]);
        $fileName = time() . '.' . request()->file('attachments')->getClientOriginalExtension();
        $path = [Storage::disk('public')->put('payment-attachments', request()->file('attachments'))] ;

        $payment = Payment::Create(
            [
                'team_id' => $record->team_id,
                'invoice_id' => $record->id,
                'payment_method_id' => $payment_method_id,
                'payment_date' => date('Y-m-d'),
                'total' => request()->post('total'),
                'notes' => request()->post('notes'),
                'reference' => request()->post('reference'),
                'status' => 'processing',
                'attachments' => $path ,
            ]
        );
        $record->invoice_status = 'process';
        $record->save();

       

        return redirect('/invoicepdf/'.$hashid.'/'.$payment_method_id.'/?payment_id='.base64_encode('luqmanahmadnordin' . $payment->id))->with(['message' => 'Success Manual Payment', 'payment' => $payment ]);
    }
}
