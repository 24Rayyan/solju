<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::resource('orders', OrderController::class);
Route::get('orders/{order}/download-invoice', [OrderController::class, 'downloadInvoice'])->name('orders.downloadInvoice');
Route::post('/orders/{order}/lock', [OrderController::class, 'lock'])->name('orders.lock');
Route::get('/sales-chart', [OrderController::class, 'salesChart'])->name('orders.salesChart');
Route::get('/orders/export', [OrderController::class, 'exportExcel'])->name('orders.export');