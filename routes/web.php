<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::resource('orders', OrderController::class);
Route::get('orders/{order}/download-invoice', [OrderController::class, 'downloadInvoice'])->name('orders.downloadInvoice');
