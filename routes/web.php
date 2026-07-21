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
use App\Http\Controllers\pg\AirpayController;
use App\Http\Controllers\CalculatorsController;
use App\Http\Controllers\pg\PayuController;
use App\Http\Controllers\pg\LyraPgController;
use App\Http\Controllers\pg\HdfcPgController;
use App\Http\Controllers\pg\BilldeskController;
use App\Http\Controllers\pg\ZwitchController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\pg\PaytmController;
use App\Http\Controllers\ScheduleSlotController;
use App\Http\Controllers\WebinarStepsController;

Route::group([
    'prefix' => '/',
    'as' => 'front.',
], function () {
    /* Home Controller functions */
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
    /* Legal controller functions */
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
    /* SelfApply controller functions */
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

    Route::get('prime-offer', [SelfApplyController::class, 'offer1'])->name('offer1');
    Route::post('prime-offer-request',[SelfApplyController::class, 'getOffer1'])->name('get.offer1');

    Route::get('mega-offer', [SelfApplyController::class, 'offer2'])->name('offer2');
    Route::post('mega-offer-request',[SelfApplyController::class, 'getOffer2'])->name('get.offer2');

    Route::get('premium-offer', [SelfApplyController::class, 'offer3'])->name('offer3');
    Route::post('premium-offer-request',[SelfApplyController::class, 'getOffer3'])->name('get.offer3');
    Route::get('/premium-offer-response', [SelfApplyController::class, 'offer3Response'])->name('offer3.response');

    Route::get('star-offer', [SelfApplyController::class, 'offer4'])->name('offer4');
    Route::post('star-offer-request',[SelfApplyController::class, 'getOffer4'])->name('get.offer4');
    Route::get('/star-offer-response', [SelfApplyController::class, 'offer4Response'])->name('offer4.response');

    Route::get('great-offer', [SelfApplyController::class, 'offer5'])->name('offer5');
    Route::post('great-offer-request',[SelfApplyController::class, 'getOffer5'])->name('get.offer5');
    Route::get('/great-offer-response', [SelfApplyController::class, 'offer5Response'])->name('offer5.response');

    Route::get('standard-offer', [SelfApplyController::class, 'offer6'])->name('offer6');
    Route::post('standard-offer-request',[SelfApplyController::class, 'getOffer6'])->name('get.offer6');

    Route::get('offer-7',[SelfApplyController::class, 'offer7'])->name('offer7');
    Route::post('offer-7',[SelfApplyController::class, 'getOffer7'])->name('get.offer7');
});

Route::group([
    'prefix' => '/loan-agent',
    'as' => 'loan.agent.'
], function () {
    /* Hire Loan Agent controller functions */
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

    Route::get('great-deal-offer', [LoanAgentController::class, 'offer1'])->name('offer1');
    Route::post('great-deal-offer-request',[LoanAgentController::class, 'getOffer1'])->name('get.offer1');

    Route::get('elite-offer', [LoanAgentController::class, 'offer2'])->name('offer2');
    Route::post('elite-offer-request',[LoanAgentController::class, 'getOffer2'])->name('get.offer2');
    /* Route::post('/offer-2-response',[LoanAgentController::class, 'offer2Response']); */

    Route::get('ultra-saver-offer', [LoanAgentController::class, 'offer3'])->name('offer3');
    Route::post('ultra-saver-request',[LoanAgentController::class, 'getOffer3'])->name('get.offer3');
    Route::get('/ultra-saver-offer-response', [LoanAgentController::class, 'offer3Response'])->name('offer3.response');

    Route::get('big-offer', [LoanAgentController::class, 'offer4'])->name('offer4');
    Route::post('big-offer-request',[LoanAgentController::class, 'getOffer4'])->name('get.offer4');
    Route::get('/big-offer-response', [LoanAgentController::class, 'offer4Response'])->name('offer4.response');

    Route::get('big-benefit-offer', [LoanAgentController::class, 'offer5'])->name('offer5');
    Route::post('big-benefit-offer-request',[LoanAgentController::class, 'getOffer5'])->name('get.offer5');
    //Route::get('/big-benefit-offer-response', [LoanAgentController::class, 'offer5Response'])->name('offer5.response');

    Route::get('silver-offer', [LoanAgentController::class, 'offer6'])->name('offer6');
    Route::post('silver-offer-request',[LoanAgentController::class, 'getOffer6'])->name('get.offer6');
});

Route::group([
    'prefix' => '/offers',
    'as' => 'offer.'
], function () {
    Route::get('/offer-1', [\App\Http\Controllers\OfferController::class, 'offer1'])->name('offer.one');
    Route::get('/offer-2', [\App\Http\Controllers\OfferController::class, 'offer2'])->name('offer.two');
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

/* CipherPay route for Temporary */
Route::get('/cipher-pay', [CipherPayController::class, 'DynamicQr'])->name('cipher.pay');
Route::get('/initiate-collect', [CipherPayController::class, 'InitiateCollect'])->name('initiate.collect');
Route::get('/status-enquiry', [CipherPayController::class, 'StatusEnquiry'])->name('status.enquiry');
Route::get('/cipher-payment-failed', [CipherPayController::class, 'paymentFailed'])->name('payment.failed');
Route::get('/cipher-payment-success', [CipherPayController::class, 'paymentSuccess'])->name('payment.success');
//Route::post('/cipher-response',[CipherPayController::class, 'CipherResponse'])->name('cipher.response');

/* PhonePay PG */
Route::get('/phonepe-page', [PhonePayController::class, 'index'])->name('phonepe-page');
Route::post('/phonepe', [PhonePayController::class, 'phonePe'])->name('phonepe');
Route::any('/phonepe-response', [PhonePayController::class, 'response'])->name('response');
Route::any('/phonepe-response1', [PhonePayController::class, 'response1'])->name('response1');


Route::get('/razorpay', [RazorpayController::class, 'index'])->name('razorpay');
Route::get('/razorpay-fail/{key}', [RazorpayController::class, 'failUrl'])->name('razorpay-fail');


Route::get('/subpaisa', [SabpaisaController::class, 'index'])->name('subpaisa');
Route::post('/subpaisa-store', [SabpaisaController::class, 'initiatePayment'])->name('subpaisa-send');
Route::post('/subpaisa-response', [SubpaisaResponse::class, 'Response'])->name('payment-response');

/* Route::get('/airpay-pg', [AirpayController::class, 'index'])->name('airpay');
Route::post('/airpay-send', [AirpayController::class, 'sendairpay'])->name('airpay.send');*/
Route::post('/airpay-response', [SelfApplyController::class, 'offer5Response'])->name('airpay.response');

Route::get('/lyra-pg', [LyraPgController::class, 'index'])->name('lyra.pg');
Route::get('/lyra-process', [LyraPgController::class, 'lyraProcess'])->name('lyra.process');
Route::post('/lyra-response', [LyraPgController::class, 'lyraResponse'])->name('lyra.response');

Route::get('/payu-pg', [PayuController::class, 'index'])->name('payu.pg');
Route::post('/payu-checkout', [PayuController::class, 'payuCheckout'])->name('payu.checkout');
Route::post('/payu-callback', [PayuController::class, 'callbackURL'])->name('payu.callbackUrl');

Route::get('zaakpay-pg',[\App\Http\Controllers\pg\ZaakpayController::class,'index'])->name('zaakpay-pg');


Route::get('/application-pdf/{userid}', [HomeController::class, 'showPdf']);



/* hdfc payment gateway */
Route::get('/hdfc-offer', [HdfcPgController::class, 'index'])->name('hdfc.offer');
Route::post('/hdfc-initiatepayment', [HdfcPgController::class, 'initiatePayment'])->name('hdfc.initiatePayment');
Route::post('/hdfc-handlePaymentResponse', [HdfcPgController::class, 'handlePaymentResponse'])->name('hdfc.handlePaymentResponse');


/* Billdesk pg */
Route::get('sandbox-billdesk',[BilldeskController::class, 'index']);

// veegah routes start here
Route::group([
    'prefix' => '/veegah/',
    'as' => 'veegah.'
], function() {
    Route::get('test-payment',[\App\Http\Controllers\pg\VeegahController::class, 'index'])->name('index');
    Route::post('veegah-process', [\App\Http\Controllers\pg\VeegahController::class, 'process'])->name('process');
});

// zwitch routes start here
Route::group([
    'prefix' => '/zwitch/',
    'as' => 'zwitch.'
], function() {
    Route::get('/test-payment', [ZwitchController::class, 'index'])->name('index');
    Route::post('/pay/create-token', [ZwitchController::class, 'createToken'])->name('pay.createToken');
    Route::post('/payment-response', [ZwitchController::class, 'paymentResponse'])->name('payment.response');
});

Route::get('/paytm/checkout', [PaytmController::class, 'checkout'])->name('paytm.checkout');
Route::post('/paytm/initiate', [PaytmController::class, 'initiate'])->name('paytm.initiate');
//Route::post('/paytm/callback', [PaytmController::class, 'callback'])->name('paytm.callback');


// Webinar Routes
Route::get('/webinar', [WebinarStepsController::class, 'webinar'])->name('webinar.index');
Route::get('/webinar/user-registration', [WebinarStepsController::class, 'webinarStep1'])->name('webinar.step1');
Route::get('/webinar/otp-verification', [WebinarStepsController::class, 'webinarStep2'])->name('webinar.step2');
Route::get('/webinar/personal-details', [WebinarStepsController::class, 'webinarStep3'])->name('webinar.step3');
Route::get('/webinar/enroll-now', [WebinarStepsController::class, 'webinarStep4'])->name('webinar.step4');

Route::post('/webinar/store-step1', [WebinarStepsController::class, 'storewebinarStep1'])->name('webinar.storeStep1');
Route::post('/webinar/verify-otp-step', [WebinarStepsController::class, 'verifywebinarOtpStep'])->name('webinar.verifyOtpStep');
Route::post('/webinar/resend-otp', [WebinarStepsController::class, 'resendwebinarOtp'])->name('webinar.resendOtp');
Route::post('webinar/postal-details', [WebinarStepsController::class, 'postalDetails'])->name('webinar.postal.details');
Route::post('/webinar/store-step3', [WebinarStepsController::class, 'storewebinarStep3'])->name('webinar.storeStep3');
Route::post('/webinar/pay', [WebinarStepsController::class, 'initiatewebinarPayment'])->name('webinar.pay');
Route::get('/webinar/thankyou', [WebinarStepsController::class, 'webinarThankyou'])->name('webinar.thankyou');
Route::get('/webinar/payment-response/true', [WebinarStepsController::class, 'paymentSuccess'])->name('webinar.payment.success');
Route::get('/webinar/payment-response/false', [WebinarStepsController::class, 'paymentFailed'])->name('webinar.payment.failed');

Route::get('/webinar/process', [WebinarStepsController::class, 'userProcess'])->name('webinar.process');


//Schedule Slot
Route::get('schedule-slot', [ScheduleSlotController::class, 'getScheduleSlotPage'])->name('schedule-slot');
Route::post('schedule-slot', [ScheduleSlotController::class, 'scheduleSlot'])->name('schedule-slot');
Route::get('schedule-success', [ScheduleSlotController::class, 'scheduleSuccess'])->name('schedule-success');
Route::get('schedule-cancel', [ScheduleSlotController::class, 'scheduleCancel'])->name('schedule-cancel');

Route::get('/testdata', [WebinarStepsController::class, 'testdata']);
