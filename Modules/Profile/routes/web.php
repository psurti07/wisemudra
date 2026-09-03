<?php

use Illuminate\Support\Facades\Route;
use Modules\Profile\App\Http\Controllers\ProfileController;


Route::group([
    'prefix' => '/customer',
    'as' => 'customer.',
    'middleware' => ['auth','prevent.back.history']
], function () {
    Route::get('/profile',[ProfileController::class,'index'])->name('profile');
    Route::get('/profile/change-password',[ProfileController::class,'changePassword'])->name('profile.change-password');
    Route::post('/profile/change-password',[ProfileController::class,'updatePassword'])->name('profile.update.password');
    Route::post('/profile/update-profile',[ProfileController::class,'updateProfile'])->name('profile.update.profile');
    Route::post('/profile/postal-details',[ProfileController::class,'postalDetails'])->name('profile.postal.details');
    Route::post('/profile/company-details',[ProfileController::class,'companyDetails'])->name('profile.create.company');
});
