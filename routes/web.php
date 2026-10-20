<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController; 

Route::get('/', function () {
    return view('welcome');
});

// rutas
Route::get('/checkout', [PaymentController::class, 'showCheckout']);
Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook']);