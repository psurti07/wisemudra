<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\App\Http\Controllers\DashboardController;

Route::group([
    'prefix' => '/customer',
    'as' => 'customer.',
    'middleware' => ['auth','prevent.back.history']
], function () {
    Route::get('/license-agreement',[DashboardController::class,'licenseAgreement'])->name('license.agreement');
    Route::post('/accept-agreement',[DashboardController::class,'acceptAgreement'])->name('accept.agreement');
    Route::get('/logout',[DashboardController::class,'logout'])->name('logout');
});


Route::group([
    'prefix' => '/customer',
    'as' => 'customer.',
    'middleware' => ['auth','prevent.back.history']
], function () {
    Route::get('/dashboard',[DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/renew-plan',[DashboardController::class, 'renewPlan'])->name('renew.plan');
    Route::post('/renew-plan-store',[DashboardController::class, 'renewalPlanStore'])->name('renew.plan.store');
    Route::get('/download-report',[DashboardController::class, 'downloadReport'])->name('download.report');
    Route::get('/knowledge-section',[DashboardController::class, 'knowledgeSection'])->name('knowledge.section');
});
