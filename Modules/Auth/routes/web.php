<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\AuthController;

Route::group([
    'prefix' => '/customer',
    'as' => 'customer.',
    'middleware' => ['guest','prevent.back.history']
], function() {
    Route::get('/login',[AuthController::class, 'login'])->name('login');
    Route::post('/authenticate',[AuthController::class, 'authenticate'])->name('authenticate');
    Route::get('/authenticate',[AuthController::class, 'authenticate2'])->name('authenticate2');
    Route::get('/forget-password',[AuthController::class, 'forgetPassword'])->name('forget.password');
    Route::post('/forget-password-update',[AuthController::class, 'forgetPasswordStore'])->name('forget.password.update');
});
