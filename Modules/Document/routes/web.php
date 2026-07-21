<?php

use Illuminate\Support\Facades\Route;
use Modules\Document\App\Http\Controllers\DocumentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix' => '/document',
    'as' => 'customer.',
    'middleware' => ['auth','prevent.back.history']
], function () {
    Route::get('/kyc-document',[DocumentController::class,'index'])->name('document.kyc.document');
    Route::post('/user/addhar', [DocumentController::class, 'storeAddhar'])->name('addhar.store');
    Route::post('/user/pan', [DocumentController::class, 'storePan'])->name('pan.store');
    Route::post('/user/bill', [DocumentController::class, 'storeBill'])->name('bill.store');
    Route::post('/user/cancel-cheque', [DocumentController::class, 'storeCancelCheque'])->name('cheque.store');
    Route::post('/user/statement', [DocumentController::class, 'storeStatement'])->name('statement.store');
    Route::post('/user/form-16', [DocumentController::class, 'storeForm'])->name('form16.store');
    Route::post('/user/salaryslip', [DocumentController::class, 'storeSalarySlip'])->name('salaryslip.store');
    Route::post('/user/businessproof', [DocumentController::class, 'storeBusinessProof'])->name('businessProof.store');
    Route::post('/user/itreturn', [DocumentController::class, 'storeItReturn'])->name('itReturn.store');
    Route::post('/user/remark', [DocumentController::class, 'storeRemark'])->name('remark.store');

});
