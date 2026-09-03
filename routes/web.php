<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\SelfApplyController;
use App\Http\Controllers\CalculatorsController;
use App\Http\Controllers\ScheduleSlotController;
use App\Http\Controllers\WebinarStepsController;

Route::group([
    'prefix' => '/',
    'as' => 'front.',
], function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/company', [HomeController::class, 'company'])->name('company');
    Route::get('/self-apply', [HomeController::class, 'selfApply'])->name('self.apply');
    // Route::get('/loan-agent', [HomeController::class, 'loanAgent'])->name('loan.agent');
    Route::get('/emi-calculator', [HomeController::class, 'emiCalculator'])->name('emi.calculator');
    Route::get('/career', [HomeController::class, 'career'])->name('career');
    Route::get('/apply-career/{code}', [HomeController::class, 'applycareer'])->name('apply-career');
    Route::post('/store-career', [HomeController::class, 'storeCareer'])->name('careerPost');
    Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact.us');
    Route::post('/contact-us/store', [HomeController::class, 'contactUsStore'])->name('contact.us.store');
    Route::get('/service', [HomeController::class, 'service'])->name('service');
    Route::get('/faqs', [HomeController::class, 'faqs'])->name('faqs');
    Route::get('/credit-score', [HomeController::class, 'creditScore'])->name('credit.score');
    Route::get('/sitemap', [HomeController::class, 'sitemap'])->name('sitemap');
    Route::get('/generate-sitemap', [HomeController::class, 'generateSitemap']);
    Route::get('/testdata', [HomeController::class, 'testdata']);

    Route::get('/raise-request', [LegalController::class, 'raiseRequest'])->name('raise.request');
    Route::post('/request-raised', [LegalController::class, 'requestRaisedPost'])->name('request.raised.post');
    Route::get('/privacy-policy', [LegalController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::get('/disclaimer', [LegalController::class, 'disclaimer'])->name('disclaimer');
    Route::get('/cancellation-and-refund-policy', [LegalController::class, 'refundPolicy'])->name('refund.policy');
    Route::get('/terms-and-conditions', [LegalController::class, 'termsConditions'])->name('terms.conditions');
});

Route::group([
    'prefix' => '/self-apply',
    'as' => 'self.apply.'
], function () {

    Route::get('/', [SelfApplyController::class, 'main'])->name('main');
    Route::post('/send-otp', [SelfApplyController::class, 'sendOtp'])->name('send.otp');
    Route::post('/verify-otp', [SelfApplyController::class, 'verifyOtp'])->name('verify.otp');
    Route::get('/loan-details', [SelfApplyController::class, 'loanDetails'])->name('loan.details');
    Route::post('/loan-details-store', [SelfApplyController::class, 'loanDetailStore'])->name('loan.details.store');
    Route::get('/personal-details', [SelfApplyController::class, 'personalDetails'])->middleware('verifyApplied')->name('personal.details');
    Route::post('/postal-details', [SelfApplyController::class, 'postalDetails'])->middleware('verifyApplied')->name('postal.details');
    Route::post('/personal-details-store', [SelfApplyController::class, 'personalDetailStore'])->name('personal.details.store');
    Route::get('/get-best-offers', [SelfApplyController::class, 'getOffers'])->middleware('verifyApplied')->name('get.offers');
    Route::get('/buy-now', [SelfApplyController::class, 'buyNow'])->middleware('verifyApplied')->name('buyNow');
    Route::get('/callbackUrl', [SelfApplyController::class, 'callbackUrl'])->middleware('verifyApplied')->name('callbackUrl');
    Route::get('/paymentFailed', [SelfApplyController::class, 'paymentFailed'])->middleware('verifyApplied')->name('payment.failed');
    Route::get('/paymentSuccess', [SelfApplyController::class, 'paymentSuccess'])/*->middleware('verifyApplied')*/->name('payment.success');
    Route::post('/checkout', [SelfApplyController::class, 'checkout'])->name('checkout');

    Route::get('prime-offer', [SelfApplyController::class, 'primeOffer'])->name('prime.offer');
    Route::post('prime-offer', [SelfApplyController::class, 'submitPrimeOffer'])->name('prime-offer.submit');
    Route::post('prime-offer-response', [SelfApplyController::class, 'primeOfferResponse'])->name('prime-offer.response');

    Route::get('mega-offer', [SelfApplyController::class, 'megaOffer'])->name('mega.offer');
    Route::post('mega-offer', [SelfApplyController::class, 'submitMegaOffer'])->name('mega-offer.submit');
    Route::post('mega-offer-response', [SelfApplyController::class, 'megaOfferResponse'])->name('mega-offer.response');

    Route::get('premium-offer', [SelfApplyController::class, 'premiumOffer'])->name('premium.offer');
    Route::post('premium-offer', [SelfApplyController::class, 'submitPremiumOffer'])->name('premium-offer.submit');
    Route::post('premium-offer-response', [SelfApplyController::class, 'premiumOfferResponse'])->name('premium-offer.response');

    Route::get('star-offer', [SelfApplyController::class, 'starOffer'])->name('star.offer');
    Route::post('star-offer', [SelfApplyController::class, 'submitStarOffer'])->name('star-offer.submit');
    Route::post('/star-offer-response', [SelfApplyController::class, 'starOfferResponse'])->name('star-offer.response');

    Route::get('great-offer', [SelfApplyController::class, 'greatOffer'])->name('great.offer');
    Route::post('great-offer', [SelfApplyController::class, 'submitGreatOffer'])->name('great-offer.submit');
    Route::post('/great-offer-response', [SelfApplyController::class, 'greatOfferResponse'])->name('great-offer.response');

    Route::get('standard-offer', [SelfApplyController::class, 'standardOffer'])->name('standard.offer');
    Route::post('standard-offer', [SelfApplyController::class, 'submitStandardOffer'])->name('standard-offer.submit');
    Route::post('/standard-offer-response', [SelfApplyController::class, 'standardOfferResponse'])->name('standard-offer.response');
});

// Route::group([
//     'prefix' => '/loan-agent',
//     'as' => 'loan.agent.'
// ], function () {

//     Route::get('/', [LoanAgentController::class, 'main'])->name('main');
//     Route::post('/send-otp', [LoanAgentController::class, 'sendOtp'])->name('send.otp');
//     Route::post('/verify-otp', [LoanAgentController::class, 'verifyOtp'])->name('verify.otp');
//     Route::get('/loan-details', [LoanAgentController::class, 'loanDetails'])->name('loan.details');
//     Route::post('/loan-details-store', [LoanAgentController::class, 'loanDetailStore'])->name('loan.details.store');
//     Route::get('/personal-details', [LoanAgentController::class, 'personalDetails'])->middleware('verifyApplied')->name('personal.details');
//     Route::post('/postal-details', [LoanAgentController::class, 'postalDetails'])->middleware('verifyApplied')->name('postal.details');
//     Route::post('/personal-details-store', [LoanAgentController::class, 'personalDetailStore'])->name('personal.details.store');
//     Route::get('/get-best-offers', [LoanAgentController::class, 'getOffers'])->middleware('verifyApplied')->name('get.offers');
//     Route::get('/buy-now', [LoanAgentController::class, 'buyNow'])->middleware('verifyApplied')->name('buyNow');
//     Route::get('/callbackUrl', [LoanAgentController::class, 'callbackUrl'])->middleware('verifyApplied')->name('callbackUrl');
//     Route::get('/paymentFailed', [LoanAgentController::class, 'paymentFailed'])->middleware('verifyApplied')->name('payment.failed');
//     Route::get('/paymentSuccess', [LoanAgentController::class, 'paymentSuccess'])/*->middleware('verifyApplied')*/->name('payment.success');
//     Route::post('/checkout', [LoanAgentController::class, 'checkout'])->name('checkout');

//     Route::get('great-deal-offer', [LoanAgentController::class, 'greatDealOffer'])->name('great-deal.offer');
//     Route::post('great-deal-offer', [LoanAgentController::class, 'submitGreatDealOffer'])->name('great-deal-offer.submit');
//     Route::post('great-deal-offer-response', [LoanAgentController::class, 'greatDealOfferResponse'])->name('great-deal-offer.response');

//     Route::get('elite-offer', [LoanAgentController::class, 'eliteOffer'])->name('elite.offer');
//     Route::post('elite-offer', [LoanAgentController::class, 'submitEliteOffer'])->name('elite-offer.submit');
//     Route::post('elite-offer-response', [LoanAgentController::class, 'eliteOfferResponse'])->name('elite-offer.response');

//     Route::get('ultra-saver-offer', [LoanAgentController::class, 'ultraSaverOffer'])->name('ultra-saver.offer');
//     Route::post('ultra-saver-offer', [LoanAgentController::class, 'submitUltraSaverOffer'])->name('ultra-saver-offer.submit');
//     Route::post('ultra-saver-offer-response', [LoanAgentController::class, 'ultraSaverOfferResponse'])->name('ultra-saver-offer.response');

//     Route::get('big-offer', [LoanAgentController::class, 'bigOffer'])->name('big.offer');
//     Route::post('big-offer', [LoanAgentController::class, 'submitBigOffer'])->name('big-offer.submit');
//     Route::post('big-offer-response', [LoanAgentController::class, 'bigOfferResponse'])->name('big-offer.response');

//     Route::get('big-benefit-offer', [LoanAgentController::class, 'bigBenefitOffer'])->name('big-benefit.offer');
//     Route::post('big-benefit-offer', [LoanAgentController::class, 'submitBigBenefitOffer'])->name('big-benefit-offer.submit');
//     Route::post('big-benefit-offer-response', [LoanAgentController::class, 'bigBenefitOfferResponse'])->name('big-benefit-offer.response');

//     Route::get('silver-offer', [LoanAgentController::class, 'silverOffer'])->name('silver.offer');
//     Route::post('silver-offer', [LoanAgentController::class, 'submitSilverOffer'])->name('silver-offer.submit');
//     Route::post('silver-offer-response', [LoanAgentController::class, 'silverOfferResponse'])->name('silver-offer.response');
// });


Route::get('/application-pdf/{userid}', [HomeController::class, 'showPdf']);

Route::group([
    'prefix' => '/webinar',
    'as' => 'webinar.'
], function () {

    Route::get('/', [WebinarStepsController::class, 'webinar'])->name('index');
    Route::get('/user-registration', [WebinarStepsController::class, 'userRegistration'])->name('user.registration');
    Route::get('/otp-verification', [WebinarStepsController::class, 'otpVerification'])->name('otp.verification');
    Route::get('/personal-details', [WebinarStepsController::class, 'personalDetails'])->name('personal.details');
    Route::get('/enroll-now', [WebinarStepsController::class, 'enrollNow'])->name('enroll-now');

    Route::post('/user-registration-submit', [WebinarStepsController::class, 'userRegistrationSubmit'])->name('user.registration.submit');
    Route::post('/verify-otp-step', [WebinarStepsController::class, 'verifywebinarOtpStep'])->name('verifyOtpStep');
    Route::post('/resend-otp', [WebinarStepsController::class, 'resendwebinarOtp'])->name('resendOtp');
    Route::post('postal-details', [WebinarStepsController::class, 'postalDetails'])->name('postal.details');
    Route::post('/personal-details-submit', [WebinarStepsController::class, 'personalDetailsSubmit'])->name('personal.details-submit');
    Route::post('/pay', [WebinarStepsController::class, 'initiatewebinarPayment'])->name('pay');
    Route::get('/thankyou', [WebinarStepsController::class, 'webinarThankyou'])->name('thankyou');
    Route::get('/payment-response/true', [WebinarStepsController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment-response/false', [WebinarStepsController::class, 'paymentFailed'])->name('payment.failed');
    Route::get('/process', [WebinarStepsController::class, 'userProcess'])->name('process');
});

//Schedule Slot
Route::get('schedule-slot', [ScheduleSlotController::class, 'getScheduleSlotPage'])->name('schedule-slot');
Route::post('schedule-slot', [ScheduleSlotController::class, 'scheduleSlot'])->name('schedule-slot');
Route::get('schedule-success', [ScheduleSlotController::class, 'scheduleSuccess'])->name('schedule-success');
Route::get('schedule-cancel', [ScheduleSlotController::class, 'scheduleCancel'])->name('schedule-cancel');
