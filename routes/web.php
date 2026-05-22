<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['web', backpack_middleware()])->group(function () {
    Route::get('/subscription/plans', [App\Http\Controllers\SubscriptionController::class, 'plans'])->name('subscription.plans');
    Route::post('/subscription/checkout', [App\Http\Controllers\SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/subscription/success', [App\Http\Controllers\SubscriptionController::class, 'success'])->name('subscription.success');
    Route::post('/subscription/cancel', [App\Http\Controllers\SubscriptionController::class, 'cancel'])->name('subscription.cancel');
});