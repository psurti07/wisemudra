<?php

use Illuminate\Support\Facades\Route;
use Modules\Loans\App\Http\Controllers\LoansController;

Route::group([
    'prefix' => '/loans/',
    'as' => 'customer.',
    'middleware' => ['auth','prevent.back.history']
], function () {
    Route::get('pre-approved-loans',[LoansController::class, 'preApprovedLoans'])->name('pre.approved.loans');
    Route::get('loan-history',[LoansController::class, 'loanHistory'])->name('loan.history');
    Route::get('loan-details/{loanAppId}',[LoansController::class, 'loanDetails'])->name('loan.details');
    Route::post('apply-now',[LoansController::class, 'applyNow'])->name('offers.apply.now');
    
    
    Route::post('faircent-apply-now',[LoansController::class, 'faircentApplyNow'])->name('pre.approved.faircent');
});
