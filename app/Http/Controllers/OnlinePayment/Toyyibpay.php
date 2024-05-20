<?php

namespace App\Http\Controllers\OnlinePayment;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TeamSetting;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\RecurringInvoice;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class Toyyibpay extends Controller
{
    //
    public function index($id, $payment_method_id){
       
        $hashid = $id;
        $id = str_replace('luqmanahmadnordin', "", base64_decode($id));
        $invoice = Invoice::find($id);
        $team_setting = TeamSetting::where('team_id', $invoice->team_id )->first();
        $toyyibpay_setting = $team_setting->payment_gateway['Toyyibpay'] ;
        // dd($invoice);
        $some_data = array(
            'userSecretKey'=> $toyyibpay_setting['sandbox'] ? $toyyibpay_setting['tp_ToyyibPay_Sandbox_User_Secret_Key'] : $toyyibpay_setting['tp_ToyyibPay_User_Secret_Key'],
            'categoryCode'=>  $toyyibpay_setting['sandbox'] ? $toyyibpay_setting['tp_ToyyibPay_Sandbox_categoryCode'] : $toyyibpay_setting['tp_ToyyibPay_categoryCode'],
            'billName'=> $team_setting->invoice_prefix_code . $invoice->numbering,
            'billDescription'=> $team_setting->invoice_prefix_code . $invoice->numbering ,
            'billPriceSetting'=>1,
            'billPayorInfo'=>1,
            'billAmount'=> $invoice->balance * 100 ,
            'billReturnUrl'=> url('invoicepdf/'.$hashid.'/'.$payment_method_id),
            'billCallbackUrl'=> url('online-payment/toyyibpay-callback/'.$hashid),
            'billExternalReferenceNo' => $team_setting->invoice_prefix_code.$invoice->numbering. ":id".$invoice->id,
            'billTo'=> $invoice->customer->name,
            'billEmail'=> $invoice->customer->email,
            'billPhone'=> $invoice->customer->phone != '' ? $invoice->customer->phone :'0123456789',
            'billSplitPayment'=>0,
            'billSplitPaymentArgs'=>'',
            'billPaymentChannel'=>2,
            'billContentEmail'=>'Thank you for purchasing our product!',
            'billChargeToCustomer'=>isset($toyyibpay_setting['billChargeToCustomer']) && $toyyibpay_setting['billChargeToCustomer'] ? 0 : '',
            'billExpiryDate'=>'',
            'billExpiryDays'=>''
          );  
        
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_POST, 1);
          if($toyyibpay_setting['sandbox']){
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
            if($toyyibpay_setting['sandbox']){
              return redirect()->away('https://dev.toyyibpay.com/'.$obj[0]->BillCode);
            }else{
              return redirect()->away('https://toyyibpay.com/'.$obj[0]->BillCode);
            }
           
          }else{
            return redirect()->back()->with('message', $obj->msg);
          }
       
    }

    public function callback($id){
      Log::build([
        'driver' => 'single',
        'path' => storage_path('logs/custom.log'),
      ])->info(json_encode($_POST));

      
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

        $payment = Payment::updateOrCreate(
          ['reference' => $_POST['refno']],
          [
              'team_id' => $invoice->team_id,
              'invoice_id' => $invoice->id,
              'payment_method_id' => $payment_method->id,
              'payment_date' => date('Y-m-d'),
              'total' => $_POST['amount'] ,
              'notes' => 'billcode:' . $_POST['billcode'] . ' transaction id:' . $_POST['refno'],
              'reference' => $_POST['refno'],
              'status' => $status_payment,
          ]
      );


        if($status_payment == 'completed'){
          $totalPayment = Payment::where('team_id',  $invoice->team_id)
            ->where('invoice_id', $invoice->id)
            ->where('status', 'completed')->sum('total');
          $totalRefunded = Payment::where('team_id', $invoice->team_id)
            ->where('invoice_id', $invoice->id)
            ->where('status', 'refunded')->sum('total');

            $invoice->balance = $invoice->final_amount - $totalPayment + $totalRefunded;
            if($invoice->balance == 0){
              $invoice->invoice_status = 'done';
            }
          $invoice->save();

        }

      }
    


    }
    public function recurring($id, $payment_method_id){
      $hashid = $id;
      $id = str_replace('luqmanahmadnordin', "", base64_decode($id));
      $recurring_invoice = RecurringInvoice::find($id);
      $invoice = Invoice::where('recurring_invoice_id',$id)->get();
      $team_setting = TeamSetting::where('team_id', $recurring_invoice->team_id )->first();
      $toyyibpay_setting = $team_setting->payment_gateway['Toyyibpay'] ;
      
      $payment_collection = $this->gen_recurring_payment($recurring_invoice, $invoice, $invoice->where('invoice_status','new')->sum('balance'), $payment_method_id );
    
      $some_data = array(
          'userSecretKey'=> $toyyibpay_setting['sandbox'] ? $toyyibpay_setting['tp_ToyyibPay_Sandbox_User_Secret_Key'] : $toyyibpay_setting['tp_ToyyibPay_User_Secret_Key'],
          'categoryCode'=>  $toyyibpay_setting['sandbox'] ? $toyyibpay_setting['tp_ToyyibPay_Sandbox_categoryCode'] : $toyyibpay_setting['tp_ToyyibPay_categoryCode'],
          'billName'=> $team_setting->recurring_invoice_prefix_code . $recurring_invoice->numbering,
          'billDescription'=> $team_setting->recurring_invoice_prefix_code . $recurring_invoice->numbering ,
          'billPriceSetting'=>1,
          'billPayorInfo'=>1,
          'billAmount'=> $invoice->where('invoice_status','new')->sum('balance') * 100 ,
          'billReturnUrl'=> url('recurringInvoicepdf/'.$hashid.'/?payment_method_id='.$payment_method_id.'&payment_id='.base64_encode('luqmanahmadnordin' . json_encode($payment_collection))),
          'billCallbackUrl'=> url('online-payment/toyyibpay-recurring-callback/'.$hashid),
          'billExternalReferenceNo' => base64_encode(json_encode($payment_collection)) ,
          'billTo'=> $recurring_invoice->customer->name,
          'billEmail'=> $recurring_invoice->customer->email,
          'billPhone'=> $recurring_invoice->customer->phone != '' ? $recurring_invoice->customer->phone :'0123456789',
          'billSplitPayment'=>0,
          'billSplitPaymentArgs'=>'',
          'billPaymentChannel'=>2,
          'billContentEmail'=>'Thank you for purchasing our product!',
          'billChargeToCustomer'=>isset($toyyibpay_setting['billChargeToCustomer']) && $toyyibpay_setting['billChargeToCustomer'] ? 0 : '',
          'billExpiryDate'=>'',
          'billExpiryDays'=>''
        );  
      
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        if($toyyibpay_setting['sandbox']){
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
          //update invoice status
          foreach($invoice AS $key3 => $val3){
            $val3->invoice_status = 'process';
            $val3->save();
          }
          if($toyyibpay_setting['sandbox']){
            return redirect()->away('https://dev.toyyibpay.com/'.$obj[0]->BillCode);
          }else{
            return redirect()->away('https://toyyibpay.com/'.$obj[0]->BillCode);
          }
         
        }else{
          return redirect()->back()->with('message', $obj->msg);
        }
     
    }

    public function recurring_callback($id){
      Log::build([
        'driver' => 'single',
        'path' => storage_path('logs/custom.log'),
      ])->info(json_encode($_POST));

      
      $hashid = $id;
      $id = str_replace('luqmanahmadnordin', "", base64_decode($id));
      $recurring_invoice = RecurringInvoice::find($id)->first();
      $invoice_id_all = json_decode(base64_decode($_POST['order_id'])) ;
      $invoice = Invoice::whereIn('id',$invoice_id_all)->get();

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
        $payment_method = PaymentMethod::where('team_id', $recurring_invoice->team_id )
        ->where('payment_gateway_id', 2)->first();

        $total_payment = $_POST['amount']  ; 
        $payment_collection = [];
        $lastInvoice = count($invoice)-1;
        foreach($invoice AS $key => $val){
            if($total_payment >= $val->balance ){
                if($key == $lastInvoice){
                    $total = $total_payment ;
                }else{
                    $total = $val->balance ;  
                }
            }else if($total_payment >= 0 && $total_payment < $val->balance){
                $total = $total_payment ;
            }else{
               continue ;
            }
            $payment = Payment::updateOrCreate(
                ['reference' => $_POST['refno']],
                [
                    'team_id' => $recurring_invoice->team_id,
                    'invoice_id' => $val->id,
                    'recurring_invoice_id' => $id,
                    'payment_method_id' => $payment_method->id,
                    'payment_date' => date('Y-m-d'),
                    'total' =>  $total,
                    'notes' => 'billcode:' . $_POST['billcode'] . ' transaction id:' . $_POST['refno'],
                    'reference' => $_POST['refno'],
                    'status' => $status_payment,
                    'attachments' => '' ,
                ]
            );
            $payment_collection[] = $payment->toArray() ;
            $val->invoice_status = 'process';
            $val->save();

            $total_payment = $total_payment - $val->balance ;
            if($status_payment == 'completed'){
              $totalPayment = Payment::where('team_id',  $invoice->team_id)
                ->where('invoice_id', $invoice->id)
                ->where('status', 'completed')->sum('total');
              $totalRefunded = Payment::where('team_id', $invoice->team_id)
                ->where('invoice_id', $invoice->id)
                ->where('status', 'refunded')->sum('total');

                $invoice->balance = $invoice->final_amount - $totalPayment + $totalRefunded ;
                if($invoice->balance == 0){
                  $invoice->invoice_status = 'done';
                }
              $invoice->save();
    
            }
    

        }
       
      }
    


    }

    public function gen_recurring_payment($recurring_invoice, $invoice, $total_payment,$payment_method_id ){
      $payment_collection[] = [
        'invoice_id_all' => $invoice->pluck(['id'])->all(),
        'updated_at' => date('Y-m-d'),
        'total'=>  $total_payment,
      ] ;

      return $payment_collection;

    }
}
