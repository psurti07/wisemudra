<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscription\App\Http\Controllers\SubscriptionController;

Route::group([
    'prefix' => '/customer',
    'as' => 'customer.',
    'middleware' => ['auth','prevent.back.history']
], function () {
    Route::get('/subscription',[SubscriptionController::class,'index'])->name('subscription');
    Route::get('/download-invoice',[SubscriptionController::class,'invoice'])->name('invoice');
});
