<?php

use App\Livewire\Home;
use App\Models\Invoice;
use App\Livewire\PublicInvoice;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Route;
use App\Livewire\Post\Show as PostShow;
use App\Http\Controllers\OnlinePayment\Toyyibpay;

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
Route::get('/article/{post:slug}', PostShow::class)->name('post.show');


// Route::get('/mydownload', function () {
//     return Pdf::view('pdf', [])
//     ->format('a4')
//     ->noSandbox()
//     ->save('invoice.pdf');
// });


Route::get('/quotationpdf/{id}', function ($id) {
    return view('pdf.quotation', ['id' => $id]); // View name 'about'
  })->name('pdf.quotation');

Route::get('/invoicepdf/{id}/{payment_method_id?}', function ($id, $payment_method_id = null) {
    return view('pdf.invoice', ['id' => $id, 'payment_method_id' => $payment_method_id]); // View name 'about'
  })->name('pdf.invoice');

Route::get('/public-invoice/{id}', PublicInvoice::class)->name('public.invoice');
  
Route::get('/online-payment/toyyibpay/{id}/{payment_method_id?}', [Toyyibpay::class, 'index']);
Route::get('/online-payment/toyyibpay-callback/{id}', [Toyyibpay::class, 'callback']);

