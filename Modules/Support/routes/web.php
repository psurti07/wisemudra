<?php

use Illuminate\Support\Facades\Route;
use Modules\Support\App\Http\Controllers\SupportController;

Route::group([
    'prefix' => '/customer/',
    'as' => 'customer.',
    'middleware' => ['auth','prevent.back.history']
], function () {
    Route::get('support', [SupportController::class, 'support'])->name('support');
    Route::post('post-support', [SupportController::class, 'supportPost'])->name('support.post');
});
