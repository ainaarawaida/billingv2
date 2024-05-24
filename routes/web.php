<?php

use App\Livewire\Home;
use App\Models\Invoice;
use App\Livewire\PublicInvoice;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Route;
use App\Livewire\Post\Show as PostShow;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\OnlinePayment\Toyyibpay;
use App\Http\Controllers\OnlinePayment\ManualPayment;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', Home::class)->name('home');
// Route::get('/article/{post:slug}', PostShow::class)->name('post.show');


// Route::get('/mydownload', function () {
//   $ali = 0;
//   $ahmad = 0 ;
//   if($ahmad == 0){
//     $ali = 1;
//    dump("salam", $ali);
//   }
//   if($ali == 1){
//     dd("salam2");
//   }
  
// });

Route::get('login', function () {
    return redirect(url('app/login')); 
})->name('login');


Route::get('/quotationpdf/{id}', function ($id) {
    return view('pdf.quotation', ['id' => $id]); // View name 'about'
  })->name('pdf.quotation');

Route::get('/invoicepdf/{id}/{payment_method_id?}', function ($id, $payment_method_id = null) {
    return view('pdf.invoice', ['id' => $id, 'payment_method_id' => $payment_method_id]); // View name 'about'
  })->name('pdf.invoice');

Route::get('/recurringInvoicepdf/{id}/{status?}/{payment_method_id?}', function ($id, $status = 'new', $payment_method_id = null) {
    return view('pdf.recurringInvoice', ['id' => $id, 'status' => $status, 'payment_method_id' => $payment_method_id]); // View name 'about'
  })->name('pdf.recurringInvoice');  

Route::get('/paymentpdf/{id}', function ($id) {
    return view('pdf.payment', ['id' => $id]); // View name 'about'
  })->name('pdf.payment');  
// Route::get('/public-invoice/{id}', PublicInvoice::class)->name('public.invoice');
  
//toyyibpay
Route::get('/online-payment/toyyibpay/{id}/{payment_method_id?}', [Toyyibpay::class, 'index']);
Route::post('/online-payment/toyyibpay-callback/{id}', [Toyyibpay::class, 'callback'])
->withoutMiddleware([VerifyCsrfToken::class]);
Route::get('/online-payment/toyyibpay-recurring/{id}/{payment_method_id?}', [Toyyibpay::class, 'recurring']);
Route::post('/online-payment/toyyibpay-recurring-callback/{id}', [Toyyibpay::class, 'recurring_callback'])
->withoutMiddleware([VerifyCsrfToken::class]);

//manual payment
Route::post('/online-payment/manual-payment/{id}/{payment_method_id?}', [ManualPayment::class, 'index']);
Route::post('/online-payment/manual-payment-recurring/{id}/{payment_method_id?}', [ManualPayment::class, 'recurring']);


