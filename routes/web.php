<?php

use App\Livewire\Home;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Route;
use App\Livewire\Post\Show as PostShow;

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
