<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\SelfApplyController;
use App\Http\Controllers\LoanAgentController;
use App\Http\Controllers\CipherPayController;
use App\Http\Controllers\pg\PhonePayController;
use App\Http\Controllers\pg\RazorpayController;
use App\Http\Controllers\pg\SabpaisaController;
use App\Http\Controllers\pg\SubpaisaResponse;
use App\Http\Controllers\CalculatorsController;
use App\Http\Controllers\pg\PayuController;
use App\Http\Controllers\pg\LyraPgController;
use App\Http\Controllers\pg\HdfcPgController;
use App\Http\Controllers\pg\BilldeskController;
use App\Http\Controllers\pg\ZwitchController;
use App\Http\Controllers\pg\PaytmController;
use App\Http\Controllers\ScheduleSlotController;
use App\Http\Controllers\WebinarStepsController;

Route::group([
    'prefix' => '/',
    'as' => 'front.',
], function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/company', [HomeController::class, 'company'])->name('company');
    Route::get('/self-apply', [HomeController::class, 'selfApply'])->name('self.apply');
    Route::get('/loan-agent', [HomeController::class, 'loanAgent'])->name('loan.agent');
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

    Route::get('prime-offer', [SelfApplyController::class, 'PrimeOffer'])->name('prime.offer');
    Route::post('prime-offer', [SelfApplyController::class, 'submitPrimeOffer'])->name('prime-offer.submit');
    Route::post('prime-offer-response', [SelfApplyController::class, 'PrimeOfferResponse'])->name('prime-offer.response');

    Route::get('mega-offer', [SelfApplyController::class, 'MegaOffer'])->name('mega.offer');
    Route::post('mega-offer', [SelfApplyController::class, 'submitMegaOffer'])->name('mega-offer.submit');
    Route::post('mega-offer-response', [SelfApplyController::class, 'MegaOfferResponse'])->name('mega-offer.response');

    Route::get('premium-offer', [SelfApplyController::class, 'PremiumOffer'])->name('premium.offer');
    Route::post('premium-offer', [SelfApplyController::class, 'submitPremiumOffer'])->name('premium-offer.submit');
    Route::post('premium-offer-response', [SelfApplyController::class, 'PremiumOfferResponse'])->name('premium-offer.response');

    Route::get('star-offer', [SelfApplyController::class, 'StarOffer'])->name('star.offer');
    Route::post('star-offer', [SelfApplyController::class, 'submitStarOffer'])->name('star-offer.submit');
    Route::post('/star-offer-response', [SelfApplyController::class, 'StarOfferResponse'])->name('star-offer.response');

    Route::get('great-offer', [SelfApplyController::class, 'GreatOffer'])->name('great.offer');
    Route::post('great-offer', [SelfApplyController::class, 'submitGreatOffer'])->name('great-offer.submit');
    Route::post('/great-offer-response', [SelfApplyController::class, 'GreatOfferResponse'])->name('great-offer.response');

    Route::get('standard-offer', [SelfApplyController::class, 'StandardOffer'])->name('standard.offer');
    Route::post('standard-offer', [SelfApplyController::class, 'submitStandardOffer'])->name('standard-offer.submit');
    Route::post('/standard-offer-response', [SelfApplyController::class, 'StandardOfferResponse'])->name('standard-offer.response');
});

Route::group([
    'prefix' => '/loan-agent',
    'as' => 'loan.agent.'
], function () {

    Route::get('/', [LoanAgentController::class, 'main'])->name('main');
    Route::post('/send-otp', [LoanAgentController::class, 'sendOtp'])->name('send.otp');
    Route::post('/verify-otp', [LoanAgentController::class, 'verifyOtp'])->name('verify.otp');
    Route::get('/loan-details', [LoanAgentController::class, 'loanDetails'])->name('loan.details');
    Route::post('/loan-details-store', [LoanAgentController::class, 'loanDetailStore'])->name('loan.details.store');
    Route::get('/personal-details', [LoanAgentController::class, 'personalDetails'])->middleware('verifyApplied')->name('personal.details');
    Route::post('/postal-details', [LoanAgentController::class, 'postalDetails'])->middleware('verifyApplied')->name('postal.details');
    Route::post('/personal-details-store', [LoanAgentController::class, 'personalDetailStore'])->name('personal.details.store');
    Route::get('/get-best-offers', [LoanAgentController::class, 'getOffers'])->middleware('verifyApplied')->name('get.offers');
    Route::get('/buy-now', [LoanAgentController::class, 'buyNow'])->middleware('verifyApplied')->name('buyNow');
    Route::get('/callbackUrl', [LoanAgentController::class, 'callbackUrl'])->middleware('verifyApplied')->name('callbackUrl');
    Route::get('/paymentFailed', [LoanAgentController::class, 'paymentFailed'])->middleware('verifyApplied')->name('payment.failed');
    Route::get('/paymentSuccess', [LoanAgentController::class, 'paymentSuccess'])/*->middleware('verifyApplied')*/->name('payment.success');
    Route::post('/checkout', [LoanAgentController::class, 'checkout'])->name('checkout');

    Route::get('great-deal-offer', [LoanAgentController::class, 'GreatDealOffer'])->name('great-deal.offer');
    Route::post('great-deal-offer', [LoanAgentController::class, 'submitGreatDealOffer'])->name('great-deal-offer.submit');
    Route::post('great-deal-offer-response', [LoanAgentController::class, 'GreatDealOfferResponse'])->name('great-deal-offer.response');

    Route::get('elite-offer', [LoanAgentController::class, 'EliteOffer'])->name('elite.offer');
    Route::post('elite-offer', [LoanAgentController::class, 'submitEliteOffer'])->name('elite-offer.submit');
    Route::post('elite-offer-response', [LoanAgentController::class, 'EliteOfferResponse'])->name('elite-offer.response');

    Route::get('ultra-saver-offer', [LoanAgentController::class, 'UltraSaverOffer'])->name('ultra-saver.offer');
    Route::post('ultra-saver-offer', [LoanAgentController::class, 'submitUltraSaverOffer'])->name('ultra-saver-offer.submit');
    Route::post('ultra-saver-offer-response', [LoanAgentController::class, 'UltraSaverOfferResponse'])->name('ultra-saver-offer.response');

    Route::get('big-offer', [LoanAgentController::class, 'BigOffer'])->name('big.offer');
    Route::post('big-offer', [LoanAgentController::class, 'submitBigOffer'])->name('big-offer.submit');
    Route::post('big-offer-response', [LoanAgentController::class, 'BigOfferResponse'])->name('big-offer.response');

    Route::get('big-benefit-offer', [LoanAgentController::class, 'BigBenefitOffer'])->name('big-benefit.offer');
    Route::post('big-benefit-offer', [LoanAgentController::class, 'submitBigBenefitOffer'])->name('big-benefit-offer.submit');
    Route::post('big-benefit-offer-response', [LoanAgentController::class, 'BigBenefitOfferResponse'])->name('big-benefit-offer.response');

    Route::get('silver-offer', [LoanAgentController::class, 'SilverOffer'])->name('silver.offer');
    Route::post('silver-offer', [LoanAgentController::class, 'submitSilverOffer'])->name('silver-offer.submit');
    Route::post('silver-offer-response', [LoanAgentController::class, 'SilverOfferResponse'])->name('silver-offer.response');
});


/* calculators routes starts here */
Route::group([
    'prefix' => '/calculators',
    'as' => 'calculators.'
], function () {
    Route::get('/personal-loan-emi-calculator', [CalculatorsController::class, 'personalLoanEmiCalc'])->name('personal.loan.emi.calculator');
    Route::get('/personal-loan-eligibility-calculator', [CalculatorsController::class, 'personalLoanEligibilityCalc'])->name('personal.loan.eligibility.calculator');
    Route::get('/interest-rate-calculator', [CalculatorsController::class, 'interestRateCalc'])->name('interest.rate.calculator');
    Route::get('/topup-loan-calculator', [CalculatorsController::class, 'topUpLoanCalc'])->name('topup.loan.calculator');
    Route::get('/business-loan-calculator', [CalculatorsController::class, 'businessLoanCalc'])->name('business.loan.calculator');
});

Route::get('/application-pdf/{userid}', [HomeController::class, 'showPdf']);

Route::group([
    'prefix' => '/webinar',
], function () {

    Route::get('/', [WebinarStepsController::class, 'webinar'])->name('webinar.index');
    Route::get('/user-registration', [WebinarStepsController::class, 'webinarStep1'])->name('webinar.step1');
    Route::get('/otp-verification', [WebinarStepsController::class, 'webinarStep2'])->name('webinar.step2');
    Route::get('/personal-details', [WebinarStepsController::class, 'webinarStep3'])->name('webinar.step3');
    Route::get('/enroll-now', [WebinarStepsController::class, 'webinarStep4'])->name('webinar.step4');

    Route::post('/store-step1', [WebinarStepsController::class, 'storewebinarStep1'])->name('webinar.storeStep1');
    Route::post('/verify-otp-step', [WebinarStepsController::class, 'verifywebinarOtpStep'])->name('webinar.verifyOtpStep');
    Route::post('/resend-otp', [WebinarStepsController::class, 'resendwebinarOtp'])->name('webinar.resendOtp');
    Route::post('postal-details', [WebinarStepsController::class, 'postalDetails'])->name('webinar.postal.details');
    Route::post('/store-step3', [WebinarStepsController::class, 'storewebinarStep3'])->name('webinar.storeStep3');
    Route::post('/pay', [WebinarStepsController::class, 'initiatewebinarPayment'])->name('webinar.pay');
    Route::get('/thankyou', [WebinarStepsController::class, 'webinarThankyou'])->name('webinar.thankyou');
    Route::get('/payment-response/true', [WebinarStepsController::class, 'paymentSuccess'])->name('webinar.payment.success');
    Route::get('/payment-response/false', [WebinarStepsController::class, 'paymentFailed'])->name('webinar.payment.failed');
    Route::get('/process', [WebinarStepsController::class, 'userProcess'])->name('webinar.process');
});

//Schedule Slot
Route::get('schedule-slot', [ScheduleSlotController::class, 'getScheduleSlotPage'])->name('schedule-slot');
Route::post('schedule-slot', [ScheduleSlotController::class, 'scheduleSlot'])->name('schedule-slot');
Route::get('schedule-success', [ScheduleSlotController::class, 'scheduleSuccess'])->name('schedule-success');
Route::get('schedule-cancel', [ScheduleSlotController::class, 'scheduleCancel'])->name('schedule-cancel');
