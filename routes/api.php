<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SelfApplyController;
use App\Http\Controllers\LoanAgentController;
use App\Http\Controllers\OnboardTransactionController;
use App\Http\Controllers\pg\{BilldeskController, PaytmController};
use App\Http\Controllers\WebinarStepsController;
use Modules\Dashboard\App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['web'])->post('/buyDigitalPlan', [SelfApplyController::class, 'buyDigitalPlan'])->name('api.self.apply.buy.digital.plan');
Route::middleware(['web'])->post('/buyDigitalAgentPlan', [LoanAgentController::class, 'buyDigitalPlan'])->name('api.loan.agent.buy.digital.agent.plan');

Route::post('/customer/plan-upgrade', [DashboardController::class, 'upgradePlan'])->name('api.customer.plan-upgrade');

Route::post('/bdpg-response',[BilldeskController::class, 'bdResponse']);

// New Webinar routes
Route::get('/webinar/buy-now/{orderId}/{token}/{slug}',[WebinarStepsController::class, 'webinarBuyNow'])->name('api.webinar.buynow');
Route::post('/webinar/buy-now-response',[WebinarStepsController::class, 'webinarBuyNowResponse'])->name('api.webinar.buynow.response');
Route::get('/webinar/free-response/{orderid}',[WebinarStepsController::class, 'webinarFreeRegistration'])->name('api.webinar.free.response');

Route::post('/channel-partners/onboarding', [OnboardTransactionController::class, 'onboarding']);