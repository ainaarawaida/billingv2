<?php

namespace App\Http\Controllers\OnlinePayment;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TeamSetting;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class Toyyibpay extends Controller
{
    //
    public function index($id, $payment_method_id){
       
        $hashid = $id;
        $id = str_replace('luqmanahmadnordin', "", base64_decode($id));
        $invoice = Invoice::find($id);
        $team_setting = TeamSetting::where('team_id', $invoice->team_id )->first();
        $userSecretKey = $team_setting->payment_gateway['Toyyibpay']['tp_ToyyibPay_User_Secret_Key'] ;
        // dd($invoice);
        $some_data = array(
            'userSecretKey'=> $userSecretKey,
            'categoryCode'=>'klirj00j',
            'billName'=> $team_setting->invoice_prefix_code . $invoice->numbering,
            'billDescription'=> $team_setting->invoice_prefix_code . $invoice->numbering ,
            'billPriceSetting'=>1,
            'billPayorInfo'=>1,
            'billAmount'=> $invoice->balance * 100,
            'billReturnUrl'=> url('invoicepdf/'.$hashid.'/'.$payment_method_id),
            'billCallbackUrl'=> url('online-payment/toyyibpay-callback/'.$hashid),
            'billExternalReferenceNo' => $invoice->numbering. ":id".$invoice->id,
            'billTo'=> $invoice->customer->name,
            'billEmail'=> $invoice->customer->email,
            'billPhone'=> $invoice->customer->phone != '' ? $invoice->customer->phone :'0123456789',
            'billSplitPayment'=>0,
            'billSplitPaymentArgs'=>'',
            'billPaymentChannel'=>2,
            'billContentEmail'=>'Thank you for purchasing our product!',
            'billChargeToCustomer'=>'',
            'billExpiryDate'=>'',
            'billExpiryDays'=>''
          );  
        
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_POST, 1);
          if('sandbox' == 'sandbox'){
            curl_setopt($curl, CURLOPT_URL, 'https://dev.toyyibpay.com/index.php/api/createBill');  
          }else{
            curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');  
          }
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
        
          $result = curl_exec($curl);
          $info = curl_getinfo($curl);  
          curl_close($curl);
          $obj = json_decode($result);
         
          if(isset($obj) && is_array($obj) && $obj[0]->BillCode){
            return redirect()->away('https://dev.toyyibpay.com/'.$obj[0]->BillCode);
          }else{
            return redirect()->back()->with('message', $obj->msg);
          }
       
    }

    public function callback($id){
      $hashid = $id;
      $id = str_replace('luqmanahmadnordin', "", base64_decode($id));
      $invoice = Invoice::find($id);

      if($_POST){
        if($_POST['status'] == '1'){
          $status_payment = 'completed';
        }elseif($_POST['status'] == '2'){
          $status_payment = 'processing';
        }elseif($_POST['status'] == '3'){
          $status_payment = 'failed';
        }else{
          $status_payment = 'processing';
        }
        $payment_method = PaymentMethod::where('team_id', $invoice->team_id )
        ->where('payment_gateway_id', 2)->first();

        $payment = Payment::where('invoice_id', $invoice->id)
        ->where('reference', $_POST['refno'])
        ->update([
          'team_id' => $invoice->team_id,
          'invoice_id' => $invoice->id,
          'payment_method_id' => $payment_method->id,
          'notes' => 'billcode:'.$_GET['billcode'].' transaction id:'.$_GET['transaction_id'],
          'status' => $status_payment,
        ]);

        if($status_payment == 'completed'){

        }

      }
    


    }
}
