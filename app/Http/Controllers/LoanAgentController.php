<?php

namespace App\Http\Controllers;

use App\Models\AirpayEntry;
use App\Models\Administrations;
use App\Models\ApplyLink;
use App\Models\Cardoffer;
use App\Models\CashfreeEntry;
use App\Models\ZaakpayEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\LoanApplications;
use App\Models\LyraEntry;
use App\Models\OtpVerification;
use App\Models\PhonrPeEntry;
use App\Models\Product;
use App\Models\Razorpayentry;
use App\Models\PaygicEntry;
use App\Models\UserRegistration;
use App\Models\UserTree;
use App\Models\SubpaisaEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use App\Models\MembershipOrder;
use App\Models\SiteOption;
use App\Models\Invoice;
use App\Models\FbAdsEntry;
use App\Models\CipherPay as CipherPayEntry;
use App\Models\VeegahPay as VeegahEntry;
use App\Utilities\Authuntication;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Http\Controllers\CipherPayController as CipherPay;
use Illuminate\Validation\Rule;

class LoanAgentController extends Controller
{

    public function __construct()
    {
        $this->lifetime = config('session.lifetime');
    }

    /* landing page function */
    public function main(Request $request)
    {
        $meta = selfApplyMeta();
        cookieHelper($request, $this->lifetime);
        if ($request->has('utm_referral')) {
            session()->forget('utm_referral');
            Cookie::queue('utm_referral', $request->input('utm_referral'), $this->lifetime, '/', null, false, true, false, 'lax');
            request()->session()->put('utm_referral', $request->input('utm_referral'));
        }
        return view('loanAgent.main', compact('meta'));
    }

    /* send and resend otp */
    public function sendOtp(Request $request)
    {
        try {
            /* store all request in $inputs variable */
            $inputs = $request->all();
            /* check the entered mobile number is present or not */
            $user = singleUserDetails(['mobile' => $inputs['mobile']]);
            if (!$user || (Cookie::has('user_mobile') && Cookie::get('user_mobile') != $inputs['mobile'])) {
                $keysToKeep = ['XSRF-TOKEN', 'wisemudra_session', 'utm_campaign', 'utm_medium', 'utm_source'];
                foreach (Cookie::get() as $key => $value) {
                    if (!in_array($key, $keysToKeep)) {
                        Cookie::queue(Cookie::forget($key));
                    }
                }
            }

            // Validate inputs
            $request->validate([
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ], [
                'mobile.regex' => 'Enter valid mobile number'
            ]);

            // Store mobile in cookie
            Cookie::queue('user_mobile', $inputs['mobile'], $this->lifetime, '/', null, false, true, false, 'lax');

            // Check OTP count limit
            $countSMS = countOTPs($inputs['mobile']);

            if ($user) {
                // If registered as customer already
                if ($user->isUser == 2) {
                    return response()->json([
                        'type' => 'ERROR',
                        'message' => 'This number is already registered as customer. Kindly login to customer portal.',
                        'data' => []
                    ]);
                }

                // Fetch latest loan application if any
                $loanApp = LoanApplications::where('userid', $user->id)->orderBy('id', 'DESC')->first();

                Cookie::queue('applyid', $loanApp->id, $this->lifetime, '/', null, false, true, false, 'lax');
                // Queue cookies related to user and loan
                Cookie::queue('isUser', 1, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('isVerified', 1, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('loan_amount', $loanApp->loan_amount, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('loan_type', $loanApp->loan_type, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('monthly_income', $request->input('monthly_income'), $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('current_emi', $request->input('current_emi') ?? 0, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('process_step', $user->process_step, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('user_type', $loanApp->user_type, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('acc_type', $user->acc_type, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('userid', $user->id, $this->lifetime, '/', null, false, true, false, 'lax');

                // Update acc_type in OTP verification table
                DB::table('otp_verifications')->where('mobile', $inputs['mobile'])->update(['acc_type' => $inputs['acc_type']]);

                // Insert source entry
                DB::table('source_entry')->insertGetId([
                    'user_id' => $user->id,
                    'utm_source' => Cookie::get('utm_source'),
                    'utm_campaign' => Cookie::get('utm_campaign'),
                    'utm_medium' => Cookie::get('utm_medium'),
                    'utm_referral' => Cookie::get('utm_referral'),
                    'source_id' => Cookie::get('sourceId'),
                    'client_ip' => $request->ip()
                ]);

                // Facebook ads entry if applicable
                if (Cookie::has('utm_source') && in_array(Cookie::get('utm_source'), ['facebook', 'instagram', 'ig', 'fb', 'meta', 'facebook_instagram', 'facebookads', 'instagramads'])) {
                    DB::table('fb_ads_entry')->insertGetId([
                        'rec_date' => now(),
                        'userid' => $user->id,
                        'fbclid' => Cookie::get('sourceId')
                    ]);
                }

                if ($user->process_step >= 3) {
                    Cookie::queue('fullname', $user->first_name . ' ' . $user->last_name, $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('email', $user->email, $this->lifetime, '/', null, false, true, false, 'lax');
                }

                $redirectUrl = route(loanagenturl($user->process_step));

                if (isset($inputs['offerPageRedirect']) && $inputs['offerPageRedirect']) {
                    return redirect()->route(loanagenturl($user->process_step));
                } else {
                    return response()->json([
                        'type' => 'SUCCESS',
                        'message' => 'User details successfully verified.',
                        'data' => '',
                        'redirectUrl' => $redirectUrl
                    ]);
                }
            } else {
                // New user case
                Cookie::queue('loan_type', $inputs['loan_type'], $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('acc_type', $inputs['acc_type'], $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('user_type', $inputs['user_type'], $this->lifetime, '/', null, false, true, false, 'lax');
                /* if the otp's already reach the limits */
                if (!$countSMS) {
                    return response()->json([
                        'type' => 'ERROR',
                        'message' => 'You\'ve reached your OTP limit. Contact customer support to proceed.',
                        'data' => []
                    ]);
                } else {

                    $generatedOtp = generateOtp($inputs['mobile'], $inputs['acc_type']);

                    if ($generatedOtp) {
                        return response()->json([
                            'type' => 'SUCCESS',
                            'message' => 'A one-time password has been sent to your registered mobile.',
                            'data' => $inputs['mobile']
                        ]);
                    } else {
                        return response()->json([
                            'type' => 'ERROR',
                            'message' => 'Sorry, there was a problem sending your one-time password. Please try again.',
                            'data' => []
                        ]);
                    }
                }
            }
        } catch (ValidationException $e) {
            return response()->json([
                'type' => 'ERROR',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('sendOtp error: ' . $e->getMessage());
            return response()->json([
                'type' => 'ERROR',
                'message' => 'Currently server is busy. Please try after some time.',
                'data' => []
            ]);
        }
    }

    /* verify otp function handle */
    public function verifyOtp(Request $request)
    {
        $inputs = $request->all();
        /* validate the inputs */
        $request->validate([
            'otp' => 'required|min:4|max:4'
        ], [
            'otp.min' => 'OTP must be 4 digits',
            'otp.max' => 'OTP must be 4 digits',
        ]);
        /* getting otp which is last inserted */
        $getOtp = OtpVerification::whereDate('rec_date', now())
            ->where('mobile', Cookie::get('user_mobile'))
            ->orderBy('id', 'desc')
            ->first();
        $otp = $inputs['otp'];
        /* match the entered otp and inserted otp is same or not */
        if ($otp == $getOtp->otp) {
            /* store is verified 1 in cookie when otp getting match */
            Cookie::queue('isVerified', 1, $this->lifetime, '/', null, false, true, false, 'lax');
            $redirectUrl = route('loan.agent.loan.details');
            return response()->json(['type' => 'SUCCESS', 'message' => 'Success! Your one-time password has been verified.', 'data' => '', 'redirectUrl' => $redirectUrl]);
        } else {
            return response()->json(['type' => 'ERROR', 'message' => 'The one-time password entered is incorrect. Please try again.', 'data' => '']);
        }
    }

    /* loan details page */
    public function loanDetails()
    {
        $meta = selfApplyMeta();
        if (Cookie::get('isVerified') === null) {
            return redirect()->route('loan.agent.main');
        } else {
            if (Cookie::get('process_step') === null) {
                return view('loanAgent.incomeDetails', compact('meta'));
            } else {
                $returnUrl = loanagenturl(Cookie::get('process_step'));
                return redirect()->route($returnUrl);
            }
        }
    }

    /* Submit loan details */
    public function loanDetailStore(Request $request)
    {
        $inputs = $request->all();

        $request->validate([
            'monthly_income' => 'required',
        ]);
        if (Cookie::get('process_step') === null) {
            /* loan_type, mobile_number, loan_amount, monthly_income, process_step = 2  */
            DB::beginTransaction();
            try {
                $userid = DB::table('user_registrations')->insertGetId([
                    'rec_date' => now(),
                    'update_date' => now(),
                    'mobile' => Cookie::get('user_mobile'),
                    'process_step' => 2,
                    'acc_type' => Cookie::get('acc_type')
                ]);
                if (Cookie::has('utm_referral') || session()->has('utm_referral')) {
                    $referral = DB::table('user_registrations')->where('refcode', request()->session()->get('utm_referral'))->select('id')->first();
                    $referralId = $referral->id ?? 0;

                    $userTree = DB::table('user_tree')->insertGetId([
                        'rec_date' => now(),
                        'refferaltype' => 1,
                        'refferaluserid' => $referralId,
                        'subuserid' => $userid,
                        'payout' => 0,
                    ]);
                }
                $sourceID = DB::table('source_entry')->insertGetId([
                    'user_id' => $userid,
                    'utm_source' => Cookie::get('utm_source'),
                    'utm_campaign' => Cookie::get('utm_campaign'),
                    'utm_medium' => Cookie::get('utm_medium'),
                    'utm_referral' => Cookie::get('utm_referral'),
                    'source_id' => Cookie::get('sourceId'),
                    'client_ip' => $request->ip()
                ]);

                /* fb start code */
                $fbid = DB::table('fb_ads_entry')->insertGetId([
                    'rec_date' => now(),
                    'userid' => $userid,
                    'fbclid' => Cookie::get('sourceId')
                ]);
                /* fb ends code */
                //Cookie::queue('loan_type',$request->input('loan_amount') > 500000 ? 1 : 1,$this->lifetime,'/',null,false,true,false,'lax');
                // Insert record into the loan_applications table using the userID from the user_registrations table
                $applyid = DB::table('loan_applications')->insertGetId([
                    'rec_date' => now(),
                    'userid' => $userid,
                    'loan_amount' => $request->input('loan_amount'),
                    'user_type' => Cookie::get('user_type'),
                    'loan_type' => Cookie::get('loan_type'),
                    'monthly_income' => $request->input('monthly_income'),
                    'currentemi' => $request->input('current_emi') ?? 0,
                    'application_number' => random_code(8)
                ]);
                DB::commit();
                Cookie::queue('userid', $userid, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('isUser', 1, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('applyid', $applyid, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('loan_amount', $request->input('loan_amount'), $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('monthly_income', $request->input('monthly_income'), $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('current_emi', $request->input('current_emi') ?? 0, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('process_step', 2, $this->lifetime, '/', null, false, true, false, 'lax');
                return response()->json(['type' => 'SUCCESS', 'message' => 'Loan details saved successfully!', 'data' => $userid]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['type' => 'ERROR', 'message' => $e->getMessage(), 'data' => '']);
            }
        } else {
            $returnUrl = loanagenturl(Cookie::get('process_step'));
            return redirect()->route($returnUrl);
        }
    }

    /* Personal details form step 2 */
    public function personalDetails()
    {
        $meta = selfApplyMeta();
        if (Cookie::get('isVerified') === null && Cookie::get('isUser') === null) {
            return redirect()->route('loan.agent.main');
        } else {
            if (Cookie::get('process_step') == 2) {
                return view('loanAgent.personalDetails', compact('meta'));
            } else {
                $returnUrl = loanagenturl(Cookie::get('process_step'));
                return redirect()->route($returnUrl);
            }
        }
    }

    /* postal details */
    public function postalDetails(Request $request)
    {
        try {
            $promise = getPostalDetailsByPincode($request->input('pincode'));
            return response()->json(['status' => 'success', 'district' => $promise['city'], 'state' => $promise['state'],]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'district' => '', 'state' => '', 'message' => 'Invalid Pincode']);
        }
    }

    /* store personal details */
    public function personalDetailStore(Request $request)
    {
        try {
            /* requested fields store in inputs variable */
            $inputs = $request->all();
            /* validate the requested fields */
            $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                'pincode' => 'required|digits:6',
                'city' => 'required',
                'state' => 'required'
            ]);
            /* create new array which is pass in create function for create the record */
            $newInputs = [
                'update_date' => now(),
                'first_name' => ucfirst(trim($request->input('firstname'))),
                'last_name' => ucfirst(trim($request->input('lastname'))),
                'email' => strtolower(trim($request->input('email'))),
                'pincode' => $request->input('pincode'),
                'city' => trim($request->input('city')),
                'state' => trim($request->input('state')),
                'process_step' => 3
            ];
            /* perform teh insertion in database */
            $result = UserRegistration::where('id', Cookie::get('userid'))->update($newInputs);
            /* if return teh true */
            if ($result) {
                Cookie::queue('process_step', 3, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('email', strtolower($request->input('email')), $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('fullname', ucfirst(trim($request->input('firstname'))) . ' ' . ucfirst(trim($request->input('lastname'))), $this->lifetime, '/', null, false, true, false, 'lax');
                return response()->json(['type' => 'SUCCESS', 'message' => 'Personal details saved successfully!', 'data' => '']);
            } else {
                return response()->json(['type' => 'ERROR', 'message' => 'Oops! Something went wrong.', 'data' => '']);
            }
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            //Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Currently server is busy. Please try after some time.', 'data' => []));
        }
    }

    /* get offers step 3 */
    public function getOffers()
    {
        $meta = selfApplyMeta();
        if (Cookie::get('isVerified') === null && Cookie::get('isUser') === null) {
            return redirect()->route('loan.agent.main');
        } else {
            if (Cookie::get('process_step') == 3) {
                $eligibilityAmt = calEligiblity(Cookie::get('monthly_income'), Cookie::get('current_emi'), ((Cookie::get('loan_type') == 2) ? 11.5 : 12.5), Cookie::get('loan_amount'));
                $record = DB::table('user_offers')->where('userid', Cookie::get('userid'))->first();
                $offersData = $record ? $record->offerdata : null;

                if (!$offersData || empty(json_decode($offersData, true))) {
                    $jsonData = offersBankList(
                        Cookie::get('monthly_income'),
                        Cookie::get('user_type'),
                        $eligibilityAmt
                    );

                    $resId = DB::table('user_offers')->insertGetId([
                        'rec_date' => Carbon::now(),
                        'userid' => Cookie::get('userid'),
                        'offerdata' => $jsonData
                    ]);

                    $offersData = json_decode($jsonData, true);
                } else {
                    $offersData = json_decode($offersData, true);
                }

                return view('loanAgent.getOffers', compact('meta', 'offersData'));
            } else {
                $returnUrl = loanagenturl(Cookie::get('process_step'));
                return redirect()->route($returnUrl);
            }
        }
    }

    /* buy now */
    public function buyNow()
    {
        $meta = selfApplyMeta();
        $eligibilityAmt = calEligiblity(Cookie::get('monthly_income'), Cookie::get('current_emi'), ((Cookie::get('loan_type') == 2) ? 11.5 : 12.5), Cookie::get('loan_amount'));
        $encUserId = customEncrypt(Cookie::get('userid'));

        /* send get offer message starts */
        $msg = DB::table('sms_list')->where('type', 2)->where('slug', 'get_offer')->first()->message;
        if ($msg != '#') {
            $msg = str_ireplace('{#varamount#}', $eligibilityAmt, $msg);
            $senderId = DB::table('info_pages')->where('slug', 'la-senderid')->first()->content;
            sendDynamicSMS($senderId, $msg, Cookie::get('user_mobile'), 'hire');
        }
        /* send get offer message ends */

        /* interakt code here starts */
        $data2 = array(
            'phoneNumber' => Cookie::get('user_mobile'),
            'countryCode' => '+91',
            'traits' => array(
                'name' => Cookie::get('fullname')
            ),
            'tags' => array('Hire Get Offer')
        );
        //Log::info('hire agent user track - '. json_encode($data2));
        $restrack1 = user_track($data2);
        //Log::info('hire agent user track - '. json_encode($restrack1));

        $data3 = array(
            'phoneNumber' => Cookie::get('user_mobile'),
            'countryCode' => '+91',
            'event' => 'Hire Get Offer',
            'traits' => array(
                'HireEligibleAmount' => $eligibilityAmt
            ),
        );
        //Log::info('hire agent event track - '. json_encode($data3));
        $restrack2 = event_track($data3);
        //Log::info('hire agent event track - '. json_encode($restrack2));

        $configs = DB::table('interakt_settings')->where('product', 'LA')->where('type', 'getoffer')->first();
        $data4 = array(
            "fullPhoneNumber" => '+91' . Cookie::get('user_mobile'),
            "callbackData" => "some text here",
            "type" => "Template",
            "template" => array(
                "name" => $configs->template_name,
                "languageCode" => "en",
                "headerValues" => array(
                    $configs->img_url
                ),
                "bodyValues" => array(
                    Cookie::get('fullname'),
                    $eligibilityAmt
                ),
            )
        );
        $restrack3 = interakt_message('hire', $data4, $configs->api_key);
        /* interakt code ends here */
        /* aisensy code starts */
        $aisensy = DB::table('aisensy_settings')->where('type', 'getoffer')->where('product', 'LA')->first();

        $data1 = array(
            "apiKey" => $aisensy->api_key,
            "campaignName" => $aisensy->campaign_name,
            "destination" => "+91" . Cookie::get('user_mobile'),
            "media" => array(
                "url" => $aisensy->media_url,
                "filename" => $aisensy->media_filename
            ),
            "userName" => Cookie::get('fullname'),
            "tags" => array("Hire_RM"),
            "attributes" => array(
                "EligibleAmount" => strval($eligibilityAmt)
            ),
            "templateParams" => array('$Name', '$EligibleAmount'),
        );
        $response = aisensy_track($data1);
        /* aisensy code ends */
        $record = DB::table('user_offers')->where('userid', Cookie::get('userid'))->first();
        $offersData = $record ? $record->offerdata : null;
        UserRegistration::where('id', Cookie::get('userid'))->update(['update_date' => now(), 'process_step' => 4]);
        $selfApply = Product::where('productslug', 'self-apply')->first();
        $hireAgent = Product::where('productslug', 'hire-loan-agent')->first();
        return view('loanAgent.buyNow', compact('meta', 'hireAgent', 'selfApply', 'eligibilityAmt', 'offersData'));
    }

    /* checkout the data */
    public function checkout(Request $request)
    {
        try {
            $inputs = $request->all();
            $loanAppUpdates = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'status' => 1,
                'isDelete' => 0
            );
            $res1 = LoanApplications::where('id', Cookie::get('applyid'))->update($loanAppUpdates);
            $productslug = 'hire-loan-agent';
            $entryfor = 12;
            $productData = Product::where('productslug', $productslug)->first();
            $amount = ($productData->inOffer == 1) ? $productData->offeramount : $productData->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == Cookie::get('user_mobile')) {
                    $grandAmount = 1;
                    break; // Exit the loop once a match is found
                }
            }
            //$orderid = "ZPLive" . number_format(microtime(true) * 1000, 0, '.', '');
            $orderid = "PPLive" . number_format(microtime(true) * 1000, 0, '.', '');
            $returnUrl = route('api.loan.agent.buy.digital.agent.plan');
            $callbackUrl = route('loan.agent.callbackUrl');

            /* if (env('PHONEPE_ENV') == "PRODUCTION") {
                $curlurl = 'https://api.phonepe.com/apis/hermes/pg/v1/pay';
            } else {
                $curlurl = 'https://api-preprod.phonepe.com/apis/hermes/pg/v1/pay';
            }

            //Log::info($curlurl);
            $phonePeData = array(
                'rec_date' => now(),
                'entryfor' => 12,
                'userid' => Cookie::get('userid'),
                'orderid' => $orderid,
                'orderamount' => $grandAmount,
                'ordernote' => $productData->productname
            );
            
            $res2 = PhonrPeEntry::create($phonePeData);
            $dataRes = array(
                "merchantId" => env('PHONEPE_MERCHANT_ID'),
                "merchantTransactionId" => strval($orderid),
                "merchantUserId" => strval(Cookie::get('userid')),
                "amount" => $grandAmount * 100,
                "redirectUrl" => $returnUrl,
                "redirectMode" => "POST",
                "callbackUrl" => $callbackUrl,
                "mobileNumber" => strval(Cookie::get('user_mobile')),
                "paymentInstrument" => array(
                    "type" => "PAY_PAGE"
                )
            );
            
            $payUrl = getPhonePePaymentUrl($curlurl, env('PHONEPE_SALT_KEY'), env('PHONEPE_SALT_INDEX'), $dataRes);
            if ($payUrl) {
                if ($payUrl->data->instrumentResponse->redirectInfo->url) {
                    header("location:" . $payUrl->data->instrumentResponse->redirectInfo->url);
                    die;
                } else {
                    return redirect("loan.agent.main");
                }
            } else {
                return redirect("loan.agent.main");
            }*/

            if (env('ZAAKPAY_ENV') == "PRODUCTION") {
                $curlurl = "https://api.zaakpay.com/api/paymentTransact/V8";
            } else {
                $curlurl = "https://zaakstaging.zaakpay.com/api/paymentTransact/V8";
            }

            $firstname = (Cookie::get('fullname') != "") ? Cookie::get('fullname') : Cookie::get('email');
            //Log::info($curlurl);
            $zaakpayPostData = array(
                "merchantIdentifier" => env('ZAAKPAY_MERCHANT_IDENTIFIER'),
                "orderId" => $orderid,
                "returnUrl" => $returnUrl,
                "currency" => 'INR',
                "amount" => $grandAmount * 100,
                "buyerEmail" => Cookie::get('email'),
                "buyerFirstName" => $firstname,
                "buyerPhoneNumber" => Cookie::get('user_mobile'),
                "buyerCountry" => 'India',
                "productDescription" => $productData->productname,
            );
            //Log::info('zaakpay data - '. json_encode($zaakpayPostData));
            ksort($zaakpayPostData);
            $checksumData = "";
            foreach ($zaakpayPostData as $key => $value) {
                $checksumData .= $key . '=' . $value . '&';
            }

            $checksum = hash_hmac('sha256', $checksumData, env('ZAAKPAY_SECRET_KEY'));

            $zaakPayData = array(
                'rec_date' => now(),
                'entryfor' => $entryfor, // 11 - selfapply, 12 - loanagent
                'userid' => Cookie::get('userid'),
                'orderid' => $orderid,
                'orderamount' => $grandAmount,
                'ordernote' => $productData->productname,
            );
            $response = ZaakpayEntry::create($zaakPayData);
            return View('pg.zaakpay-checkout', compact('zaakpayPostData', 'checksum', 'curlurl'));
        } catch (\Exception $e) {
            Log::error('loan agent checkout method error occured: ' . $e->getMessage());
            return redirect('/error')->with('error', 'Oops! Something went wrong.');
        }
    }

    /* callback url ofd loan agent */
    public function callbackUrl()
    {
        dd('Callback function call.Go Back and make further process');
    }

    public function buyDigitalPlan_phonepe(Request $request)
    {
        try {
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $meta = selfApplyMeta();

            Session::put('orderid', $request->input('transactionId'));

            $password = trim(random_code(6));
            Session::put('user_password', $password);
            if (!$request->has(['code', 'transactionId', 'providerReferenceId'])) {
                return redirect("loan-agent");
            }
            $paymentData = PhonrPeEntry::where('orderid', $request->input('transactionId'))->first();

            $txStatus = $request->input('code');
            Session::put('responsecode', $txStatus);

            $transactionId = $request->input('transactionId');
            $referenceId = $request->input('providerReferenceId');

            $phonepedata = array(
                'rec_date' => now(),
                'referenceid' => $referenceId,
                'txstatus' => $txStatus
            );

            $response1 = PhonrPeEntry::where('id', $paymentData->id)->update($phonepedata);
            $userData = $query = LoanApplications::select(
                'user_registrations.id as userid',
                'user_registrations.first_name',
                'user_registrations.last_name',
                'user_registrations.mobile',
                'user_registrations.email',
                'user_registrations.city',
                'user_registrations.state',
                'user_registrations.isUser',
                'user_registrations.acc_type',
                'user_registrations.process_step',
                'loan_applications.id',
                'loan_applications.loan_type',
                'loan_applications.loan_amount',
                'loan_applications.monthly_income',
                'loan_applications.currentemi'
            )
                ->join('user_registrations', 'user_registrations.id', '=', 'loan_applications.userid')
                ->where('user_registrations.id', $paymentData->userid)
                ->where('user_registrations.isDelete', 0)
                ->first();
            if ($txStatus == "PAYMENT_SUCCESS") {
                $isEntry = MembershipOrder::where('paymentid', $referenceId)
                    ->where('isDelete', 0)
                    ->count();
                if ($isEntry == 0) {
                    Cookie::queue('applyid', $userData->id, $this->lifetime, '/', null, false, true, false, 'lax');
                    $cardno = random_code_num(16);
                    $productslug = "hire-loan-agent";
                    $invprefix = "LA_";
                    $productData = Product::where('productslug', $productslug)->first();
                    $netamount = ($productData->inOffer == 1) ? $productData->offeramount : $productData->amount;

                    if ($userData->state == 'Gujarat') {
                        $cgstamount = $netamount * 0.09;
                        $sgstamount = $netamount * 0.09;
                    } else {
                        $igstamount = $netamount * 0.18;
                    }
                    $grandtotal = $netamount + $cgstamount + $sgstamount + $igstamount;

                    $membershipData = array(
                        'rec_date' => now(),
                        'userid' => $userData->userid,
                        'registration_date' => now()->format('Y-m-d'),
                        'expiry_date' => date('Y-m-d', strtotime('+9 months')),
                        'card_number' => $cardno,
                        'amount' => $grandtotal,
                        'paymentid' => $transactionId,
                        'isActive' => 1,
                        'isDelete' => 0
                    );
                    $existingMembership = MembershipOrder::where('userid', $userData->userid)
                        ->where('paymentid', $transactionId)
                        ->first();
                    $membershipId = $existingMembership ? $existingMembership->id : 0;

                    if (!$existingMembership) {
                        $membershipId = MembershipOrder::create($membershipData)->id;
                    }

                    $passwordkey = Hash::make($password);
                    $refcode = strtolower(substr(str_replace(" ", "", $userData->fullname), 0, 3));
                    $refcode .= substr($userData->mobile, -4);

                    $regData = array(
                        'rec_date' => now(),
                        'update_date' => now(),
                        'password' => $passwordkey,
                        'refcode' => $refcode,
                        'process_step' => 5,
                        'isUser' => 2,
                        'acc_type' => 2
                    );
                    $response2 =  UserRegistration::where('id', $userData->userid)->update($regData);

                    $invoiceNo = SiteOption::where('option_key', 'newinvoiceno')
                        ->select('option_value')
                        ->first();

                    $existingInvoice = Invoice::where('userid', $userData->userid)
                        ->where('cardid', $membershipId)
                        ->first();

                    $invData3 = array(
                        'rec_date' => $membershipData['rec_date'],
                        'userid' => $userData->userid,
                        'cardid' => $membershipId,
                        'inv_prefix' => $invprefix,
                        'inv_number' => $invoiceNo->option_value,
                        'inv_date' => $membershipData['registration_date'],
                        'inv_price' => $netamount,
                        'inv_cgst' => $cgstamount,
                        'inv_sgst' => $sgstamount,
                        'inv_igst' => $igstamount,
                        'inv_grandtotal' => $grandtotal,
                        'isdelete' => 0
                    );

                    if (!$existingInvoice) {
                        DB::beginTransaction();
                        try {
                            $responseinvoice = Invoice::create($invData3)->id;
                            $invNoData = array(
                                'rec_date' => now(),
                                'option_value' => $invoiceNo->option_value + 1
                            );
                            $updateInvoiceNo = SiteOption::where('option_key', 'newinvoiceno')->update($invNoData);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('Invoice creation failed', ['error' => $e->getMessage()]);
                        }
                    }

                    $staffID = assignAgent();
                    UserRegistration::where('id', $userData->userid)->update(['staff_id' => $staffID->id, 'process_step' => 5]);

                    $mailData = array(
                        'fullname' => $userData->first_name . ' ' . $userData->last_name,
                        'mobile' => $userData->mobile,
                        'email' => $userData->email,
                        'password' => $password,
                        'order_number' => $transactionId,
                        'order_date' => date('d-m-Y'),
                        'order_amount' => $grandtotal,
                        'transactionId' => $referenceId,
                        'agentName' => $staffID->fullname,
                        'agentMobile' => $staffID->mobile
                    );
                    $sendGreetings = view('mail.welcomeGreetingsla', $mailData)->render();
                    $invAttach = array_merge(
                        $invData3,
                        [
                            'fullname' => $userData->first_name . ' ' . $userData->last_name,
                            'city' => $userData->city,
                            'mobile' => $userData->mobile,
                            'email' => $userData->email,
                            'acc_type' => $userData->acc_type,
                            'state' => $userData->state,
                            'isCustomer' => 0
                        ],
                        [
                            'card_number' => $membershipData['card_number'],
                            'registration_date' => $membershipData['registration_date'],
                            'expiry_date' => $membershipData['expiry_date'],
                            'paymentid' => $membershipData['paymentid'],
                        ]
                    );
                    $invoiceData = view('mail.invoice', $invAttach)->render();
                    $pdf = Pdf::loadHTML($invoiceData)->setPaper('A4', 'portrait')->output();
                    $base64Pdf = base64_encode($pdf);

                    /* creating attachments array */
                    $attachments = [
                        [
                            'content' => $base64Pdf,
                            'name' => 'Invoice.pdf'
                        ]
                    ];

                    /* send email in brevo */
                    sendBrevoHtmlMail2($mailData, 'Congratulations! Payment for Wisemudra’s Hire Agent plan has been successful.', $sendGreetings, 3, $attachments);

                    DB::table('application_remarks')->updateOrInsert([
                        'service' => 5,
                        'subject' => 9,
                        'application_id' => $userData->id,
                    ], [
                        'rec_date' => now(),
                        'entry_at' => now(),
                        'notes' => '',
                        'staff_id' => $staffID->id
                    ]);

                    if ($response2 > 0) {
                        $remote_data = array(
                            'company_code' => config('constant.COMPANY_CODE'),
                            'company_local_ip' => '190.92.174.183',
                            'product_code' => 'HIRELOAN',
                            'customer_name' => $userData->first_name . ' ' . $userData->last_name,
                            'customer_email' => $userData->email,
                            'customer_mobile' => $userData->mobile,
                            'userid' => $userData->userid,
                            'card_number' => $cardno,
                            'rec_date' => now()->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                            'inv_prefix' => $invprefix,
                            'inv_number' => $invoiceNo->option_value,
                            'inv_date' => now()->setTimezone(config('app.timezone'))->format('Y-m-d'),
                            'inv_price' => $netamount,
                            'inv_cgst' => $cgstamount,
                            'inv_sgst' => $sgstamount,
                            'inv_igst' => $igstamount,
                            'inv_grandtotal' => $grandtotal,
                        );
                        $api_response = sendOrderData(json_encode($remote_data));

                        $redRoute = 'loan-agent/paymentSuccess'; // Row was updated
                    } else {
                        $redRoute = 'loan-agent/paymentFailed'; // No rows were updated
                    }
                    return redirect($redRoute);
                } else {
                    return redirect("loan-agent/paymentSuccess");
                }
            } else if ($txStatus == "PAYMENT_FAILURE") {
                return redirect("loan-agent/paymentFailed");
            } else {
                return redirect("loan-agent/paymentFailed");
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect("loan-agent/paymentFailed");
        }
    }

    /* buyDigitalPlan function handle */
    public function buyDigitalPlan(Request $request)
    {
        try {
            // Log::info('request data - '.json_encode($request->all()));
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $meta = selfApplyMeta();

            $password = trim(random_code(6));
            Session::put('user_password', $password);

            $orderId = $request->input('orderId');
            Session::put('orderid', $orderId);

            $responseCode = $request->input('responseCode');
            Session::put('responsecode', $responseCode);

            $orderAmount = $request->input('amount') / 100;
            $txnId = $request->input('pgTransId');
            $paymentMode = $request->input('paymentMode');
            $recd_checksum = $request->input('checksum');

            //Log::info($request->all());
            $checksum = $checksumData = '';

            $checksumsequence = array(
                "amount",
                "bank",
                "bankid",
                "cardId",
                "cardScheme",
                "cardToken",
                "cardhashid",
                "doRedirect",
                "orderId",
                "paymentMethod",
                "paymentMode",
                "responseCode",
                "responseDescription",
                "productDescription",
                "product1Description",
                "product2Description",
                "product3Description",
                "product4Description",
                "pgTransId",
                "pgTransTime"
            );

            foreach ($checksumsequence as $seqvalue) {
                if (array_key_exists($seqvalue, $request->all())) {
                    $checksumData .= $seqvalue;
                    $checksumData .= "=";
                    $checksumData .= $request->input($seqvalue);
                    $checksumData .= "&";
                }
            }

            $checksum = hash_hmac('sha256', $checksumData, env('ZAAKPAY_SECRET_KEY'));

            /*if ($checksum == $recd_checksum) {*/
            $paymentData = ZaakpayEntry::where('orderid', $orderId)->first();

            //Log::info('paymentData - '.json_encode($paymentData));

            $zaakPayData = array(
                'rec_date' => now(),
                'orderamount' => $orderAmount,
                'statuscode' => $responseCode,
                'transactionid' => $txnId,
                'paymentmode' => $paymentMode
            );
            //Log::info('zaakPayData - '. json_encode($zaakPayData));
            $response1 = ZaakpayEntry::where('id', $paymentData->id)->update($zaakPayData);
            $userData = $query = LoanApplications::select(
                'user_registrations.id as userid',
                'user_registrations.first_name',
                'user_registrations.last_name',
                'user_registrations.mobile',
                'user_registrations.email',
                'user_registrations.city',
                'user_registrations.state',
                'user_registrations.isUser',
                'user_registrations.acc_type',
                'user_registrations.process_step',
                'loan_applications.id',
                'loan_applications.loan_type',
                'loan_applications.loan_amount',
                'loan_applications.monthly_income',
                'loan_applications.currentemi'
            )
                ->join('user_registrations', 'user_registrations.id', '=', 'loan_applications.userid')
                ->where('user_registrations.id', $paymentData->userid)
                ->where('user_registrations.isDelete', 0)
                ->first();
            Cookie::queue('applyid', $userData->id, $this->lifetime, '/', null, false, true, false, 'lax');

            if ($responseCode == 100) {
                $cardno = random_code_num(16);
                $membershipData = array(
                    'rec_date' => now(),
                    'userid' => $userData->userid,
                    'registration_date' => now(),
                    'expiry_date' => date('Y-m-d', strtotime('+9 months')),
                    'card_number' => $cardno,
                    'amount' => $orderAmount,
                    'paymentid' => $txnId,
                    'isActive' => 1,
                    'isDelete' => 0
                );

                //Log::info('membership data - '. json_encode($membershipData));
                $existingMembership = MembershipOrder::where('userid', $userData->userid)
                    ->where('paymentid', $txnId)
                    ->first();
                $membershipId = $existingMembership ? $existingMembership->id : 0;

                if (!$existingMembership) {
                    $membershipId = MembershipOrder::create($membershipData)->id;
                }

                //Log::info('passsword user - ' . Cache::get('user_password'));

                $passwordkey = Hash::make($password);
                $refcode = strtolower(substr(str_replace(" ", "", $userData->fullname), 0, 3));
                $refcode .= substr($userData->mobile, -4);

                $regData = array(
                    'rec_date' => now(),
                    'update_date' => now(),
                    'password' => $passwordkey,
                    'refcode' => $refcode,
                    'process_step' => 5,
                    'isUser' => 2,
                    'acc_type' => 2
                );
                //Log::info('reg data'. json_encode($regData));
                $response2 =  UserRegistration::where('id', $userData->userid)->update($regData);

                /*if ($userData->acc_type == 2) {*/
                $productslug = "hire-loan-agent";
                //$invfor = 2;
                $invprefix = "LA_";
                /*} else {
                        $productslug = "self-apply";
                        //$invfor = 1;
                        $invprefix = "SA_";
                    }*/
                /*Log::info('product Slug - '. $productslug);
                    Log::info('invfor - '. $invfor);
                    Log::info('invprefix - '. $invprefix);*/
                $productData = Product::where('productslug', $productslug)->first();
                $netamount = ($productData->inOffer == 1) ? $productData->offeramount : $productData->amount;

                if ($userData->state == 'Gujarat') {
                    $cgstamount = $netamount * 0.09;
                    $sgstamount = $netamount * 0.09;
                } else {
                    $igstamount = $netamount * 0.18;
                }
                $grandtotal = $netamount + $cgstamount + $sgstamount + $igstamount;
                $invoiceNo = SiteOption::where('option_key', 'newinvoiceno')
                    ->select('option_value')
                    ->first();

                $existingInvoice = Invoice::where('userid', $userData->userid)
                    ->where('cardid', $membershipId)
                    //->where('inv_number', $invoiceNo->option_value)
                    ->first();

                $invData3 = array(
                    'rec_date' => $membershipData['rec_date'],
                    'userid' => $userData->userid,
                    'cardid' => $membershipId,
                    // 'inv_for' => $invfor,
                    'inv_prefix' => $invprefix,
                    'inv_number' => $invoiceNo->option_value,
                    'inv_date' => $membershipData['registration_date'],
                    'inv_price' => $netamount,
                    'inv_cgst' => $cgstamount,
                    'inv_sgst' => $sgstamount,
                    'inv_igst' => $igstamount,
                    'inv_grandtotal' => $grandtotal,
                    'isdelete' => 0
                );
                if (!$existingInvoice) {
                    DB::beginTransaction();
                    try {
                        //Log::info('invData - '.json_encode($invData3));
                        $responseinvoice = Invoice::create($invData3)->id;
                        $invNoData = array(
                            'rec_date' => now(),
                            'option_value' => $invoiceNo->option_value + 1
                        );
                        $updateInvoiceNo = SiteOption::where('option_key', 'newinvoiceno')->update($invNoData);
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Invoice creation failed', ['error' => $e->getMessage()]);
                    }
                    /*$data4 = array(
                            'payout' => 0,
                            'payout_amount' => $netamount * env('CU_PAYOUT_RATIO'),
                            'order_amount' => $netamount
                        );*/
                    $response4 = 'loan-agent/paymentFailed';
                    /* wp campaign */
                    /*$user = UserTree::where('subuserid', $userData->userid)
                            ->orderBy('id', 'desc')
                            ->first();*/

                    /*if ($user) {
                            // Update the record where the 'id' matches
                            $updated = UserTree::where('id', $user->id)->update($data4);
                        }*/

                    //Log::info('response 4 - '. $response4);

                    $staffID = assignAgent();
                    UserRegistration::where('id', $userData->userid)->update(['process_step' => 5, 'staff_id' => $staffID->id]);

                    $mailData = array(
                        'fullname' => $userData->first_name . ' ' . $userData->last_name,
                        'mobile' => $userData->mobile,
                        'email' => $userData->email,
                        'password' => $password,
                        'order_number' => $invoiceNo->option_value,
                        'order_date' => date('d-m-Y'),
                        'order_amount' => $grandtotal,
                        'transactionId' => $txnId,
                        'agentName' => $staffID->fullname,
                        'agentMobile' => $staffID->mobile
                    );
                    $sendGreetings = view('mail.welcomeGreetingsla', $mailData)->render();
                    //Log::info($sendGreetings);
                    $invAttach = array_merge(
                        $invData3,
                        [
                            'fullname' => $userData->first_name . ' ' . $userData->last_name,
                            'city' => $userData->city,
                            'mobile' => $userData->mobile,
                            'email' => $userData->email,
                            'acc_type' => $userData->acc_type,
                            'state' => $userData->state,
                            'isCustomer' => 0
                        ],
                        [
                            'card_number' => $membershipData['card_number'],
                            'registration_date' => $membershipData['registration_date'],
                            'expiry_date' => $membershipData['expiry_date'],
                            'paymentid' => $membershipData['paymentid'],
                        ]
                    );
                    /* invoice data */
                    $invoiceData = view('mail.invoice', $invAttach)->render();
                    $pdf = Pdf::loadHTML($invoiceData)->setPaper('A4', 'portrait')->output();
                    $base64Pdf = base64_encode($pdf);

                    /* creating attachments array */
                    $attachments = [
                        [
                            'content' => $base64Pdf,
                            'name' => 'Invoice.pdf'
                        ]
                    ];

                    /* send email in brevo */
                    sendBrevoHtmlMail2($mailData, 'Congratulations! Payment for Wisemudra’s Hire Agent plan has been successful.', $sendGreetings, 3, $attachments);

                    $remote_data = array(
                        'company_code' => config('constant.COMPANY_CODE'),
                        'company_local_ip' => '190.92.174.183',
                        'product_code' => 'HIRE AGENT',
                        'customer_name' => $userData->first_name . ' ' . $userData->last_name,
                        'customer_email' => $userData->email,
                        'customer_mobile' => $userData->mobile,
                        'userid' => $userData->userid,
                        'card_number' => $cardno,
                        'rec_date' => now()->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                        'inv_prefix' => $invprefix,
                        'inv_number' => $invoiceNo->option_value,
                        'inv_date' => now()->setTimezone(config('app.timezone'))->format('Y-m-d'),
                        'inv_price' => $netamount,
                        'inv_cgst' => $cgstamount,
                        'inv_sgst' => $sgstamount,
                        'inv_igst' => $igstamount,
                        'inv_grandtotal' => $grandtotal,
                    );
                    $api_response = sendOrderData(json_encode($remote_data));
                }
                if ($response2 > 0) {
                    $redRoute = 'loan-agent/paymentSuccess'; // Row was updated
                } else {
                    $redRoute = 'loan-agent/paymentFailed'; // No rows were updated
                }
                return redirect($redRoute);
            } else {
                return redirect("loan-agent/paymentFailed");
            }
            /*} else {
                Log::info('else checksum not matched');
                //$sent = $this->Site_Digital_Model->sendPaymentFailedGreetings($userdata->mobile, $userdata->email);
                //$key = stringCrypt($userdata->id, 'encrypt');
                //return redirect("digital/subscriptionorder/" . $key);
                return redirect("loan-agent/paymentFailed");
            }*/
        } catch (\Exception $e) {
            Log::error('loan agent buydigital checkout method error occured: ' . $e->getMessage());
            return redirect('/error')->with('error', 'Oops! Something went wrong.');
        }
    }

    /* paymentSuccess handle function */
    public function paymentSuccess()
    {
        $meta = selfApplyMeta();
        try {
            $loanType = Cookie::get('loan_type');
            $applyId = Cookie::get('applyid');
            $orderId = Session::get('orderid');
            $responsecode = Session::get('responsecode');

            $data = '';
            $orderData = '';

            if (isset($loanType, $applyId, $orderId) && $loanType !== null && $applyId !== null && $orderId !== null) {
                $data = array(
                    'loantype' => $loanType,
                    'status' => true
                );
                $userData = checkuserdata($applyId);
                $firstname = strtolower($userData->first_name);
                $lastname = strtolower($userData->last_name);
                $city = strtolower(preg_replace("/[^a-zA-Z]+/", "", $userData->city));
                $state = strtolower(getStateAbbreviation($userData->state));
                //$orderData = orderdata($orderId,'phonepe_entry');
                $orderData = orderdata($orderId, 'zaakpay_entry');

                $staff = Administrations::where('id', $userData->staff_id)->first();

                if (isset($responsecode) && $responsecode == 100) {
                    UserRegistration::where('id', $userData->userid)->update(['process_step' => 5]);

                    /* application remarks entry start */
                    $existingApplication = DB::table('application_remarks')->where(['service' => 5, 'subject' => 9, 'application_id' => $applyId])->first();
                    $staffID = assignAgent();
                    if (!$existingApplication) {
                        DB::table('application_remarks')->insert([
                            'rec_date' => now(),
                            'entry_at' => now(),
                            'service' => 5,
                            'subject' => 9,
                            'notes' => '',
                            'application_id' => $applyId,
                            'staff_id' => $staffID->id
                        ]);
                    }
                    /* application remarks entry ends */

                    /* send payment success message starts */
                    $msg = DB::table('sms_list')->where('type', 2)->where('slug', 'payment_successful')->first()->message;
                    if ($msg != '#') {
                        $senderId = DB::table('info_pages')->where('slug', 'la-senderid')->first()->content;
                        sendDynamicSMS($senderId, $msg, Cookie::get('user_mobile'), 'hire');
                    }
                    /* send payment success message ends */

                    /* fb conversion code starts here */
                    $fbleads = FbAdsEntry::where('userid', $userData->userid)->orderByDesc('id')->limit(1)->first();
                    Log::info("fbleads" . $fbleads);

                    $fbdata = array(
                        'type' => 'hire-agent',
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'mobile' => "91" . $userData->mobile,
                        'email' => strtolower($userData->email),
                        'city' => $city,
                        /*'dob' => date('Ymd',strtotime($userData->dob)),*/
                        'state' => $state,
                        'zip' => $userData->pincode,
                        'orderid' => $orderId,
                        'odamount' => $orderData->orderamount,
                        'sourceurl' => 'https://wisemudra.com/loan-agent/paymentSuccess'
                    );

                    if ($fbleads) {
                        if ($fbleads->fbclid != "") {
                            $fbclidpl = "fb.0." . round(microtime(true) * 1000) . "." . $fbleads->fbclid;
                            $fbdata['fbclid'] = $fbclidpl;
                        } else {
                            $fbdata['fbclid'] = '';
                        }
                    } else {
                        $fbdata['fbclid'] = '';
                    }

                    $fbresponse = fbconversioncurl($fbdata, 16);
                    Log::info("fbresponse" . $fbresponse);
                    $dataleads = array(
                        'rec_date' => now(),
                        'send_data' => json_encode($fbdata),
                        'received_data' => $fbresponse
                    );
                    if ($fbleads) {
                        $fbid = DB::table('fb_ads_entry')->where('id', $fbleads->id)->update($dataleads);
                        Log::info("fbid" . $fbid);
                    }
                    /* fb conversion code ends here */

                    /* interakt code starts here */
                    $data2 = array(
                        'phoneNumber' => Cookie::get('user_mobile'),
                        'countryCode' => '+91',
                        'traits' => array(
                            'name' => Cookie::get('fullname')
                        ),
                        'tags' => array('Hire Payment Successful')
                    );
                    $restrack1 = user_track($data2);

                    $data3 = array(
                        'phoneNumber' => Cookie::get('user_mobile'),
                        'countryCode' => '+91',
                        'event' => 'Hire Payment Successful',
                        'traits' => array(
                            'userid' => Cookie::get('user_mobile'),
                            'userpass' => Session::get('user_password')
                        )
                    );
                    //Log::info('Hire Payment Success '. json_encode($data3));
                    $restrack2 = event_track($data3);

                    /* interakt code ends here */
                }
            }
            return view('loanAgent.paymentSuccess', compact('data', 'orderData', 'meta'));
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            dd('catch');
            //return redirect('/loan-agent');
        }
    }

    /* paymentFailed handle function */
    public function paymentFailed()
    {
        $meta = selfApplyMeta();
        $data3 = array(
            'phoneNumber' => Cookie::get('user_mobile'),
            'countryCode' => '+91',
            'event' => 'Hire Payment Failed',
        );
        $restrack2 = event_track($data3);
        Log::info('hire agent event track - ' . json_encode($restrack2));

        /* send payment failed message starts */
        $msg = DB::table('sms_list')->where('type', 2)->where('slug', 'payment_unsuccessful')->first()->message;
        if ($msg != '#') {
            $senderId = DB::table('info_pages')->where('slug', 'la-senderid')->first()->content;
            sendDynamicSMS($senderId, $msg, Cookie::get('user_mobile'), 'hire');
        }
        /* send payment failed message ends */

        return view('loanAgent.paymentFailed', compact('meta'));
    }


    public function checkUserProcess($inputs)
    {
        /*$regUser = UserRegistration::where('mobile',$inputs['mobile'])->where(['isActive'=>1,'isDelete'=>0])->first();
        if($regUser){*/
        /* user found in user registration table */
        /*if($regUser->isUser == 2){
                return array('type'=>'SUCCESS','message'=>'You are already a customer.Kindly login your customer portal.','url'=>route('customer.login'));
            } else {
                return array(
                    'type'=>'SUCCESS',
                    'method' => 'POST',
                    'message'=>'Wait..We are redirecting to where you stop your process.',
                    'url'=>route('loan.agent.send.otp'),
                    'inputs' => [
                        'mobile' => $inputs['mobile'],
                        'acc_type' => $inputs['acc_type'],
                        'user_type' => $inputs['user_type'],
                        'allow_sms' => $inputs['allow_sms'],
                        'accept_tnc' => $inputs['accept_tnc'],
                        'offerPageRedirect' => TRUE
                    ]
                );
            }*/
        /*} else {*/
        /* user not found in user registration table */
        $userDetails = Cardoffer::where('mobile', $inputs['mobile'])->first();
        if ($userDetails) {
            /* Data found */
            if ($userDetails->paymentid != NULL && $userDetails->isActive == 1 && $userDetails->isDelete == 0) {
                /* User not in lead */
                if ($userDetails->isCustomer == 1) {
                    /* check in user registration table */
                    return array('type' => 'SUCCESS', 'message' => 'You are already a customer. Please login to your customer portal.', 'url' => route('customer.login'));
                } else {
                    /* payment done but not convert as a customer */
                    return array('type' => 'ERROR', 'message' => 'The user has not been converted to a customer. Please contact the support team.');
                }
            } else {
                /* user in lead */
                return FALSE;
            }
        } else {
            /* Data not found */
            return FALSE;
        }
        /*}*/
    }
    /* offer 1 */
    public function offer1()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.LA_OFFER_1'))->first();
        if ($products->inOffer == 1) {
            $productData = array(
                'inOffer' => $products->inOffer,
                'amount' => $products->amount,
                'offeramount' => $products->offeramount,
                'offerdate' => date('Y/m/d', strtotime('+1 days')) . ' 24:00:00',
                'payamount' => $products->offeramount + ($products->offeramount * 0.18)
            );
        } else {
            $productData = array(
                'inOffer' => 0,
                'amount' => $products->amount,
                'offeramount' => 0,
                'offerdate' => '',
                'payamount' => $products->amount + ($products->amount * 0.18)
            );
        }
        return view('loanAgent.offers.offer-1', compact('meta', 'productData'));
    }

    /* get offer one in this send on payment gateway */
    public function getOffer1(Request $request)
    {
        try {
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if ($profile) {
                return response()->json($profile);
            } else {
                $first_name = $inputs['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.LA_OFFER_1'))->first();
            //Log::info('products - '.json_encode($products));
            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array
            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == $mobile) {
                    $grandAmount = 1;
                    break; // Exit the loop once a match is found
                }
            }

            /* insert the adta in cardoffer */
            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $mobile], // Search condition
                [ // Values to update or insert
                    'rec_date' => now(),
                    'offerpage' => 1, //La offer 1 or great deal offer
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'emailid' => $email,
                    'amount' => floor($grandAmount),
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );

            // Get the ID of the updated or inserted record
            $record = DB::table('cardoffer')->where('mobile', $mobile)->first();
            $encData = null;
            $offerId = $record->id;

            //Log::info('Offer data - '. $offerId);
            $orderId = number_format(microtime(true) * 1000, 0, '.', '');
            $returnUrl = 'https://wisemudra.com/api/loan-agent/great-deal-offer-response';

            if (env('SABPAISA_MODE') == "PROD") {
                $curlurl = "https://securepay.sabpaisa.in/SabPaisa/sabPaisaInit?v=1";
            } else {
                $curlurl = "https://stage-securepay.sabpaisa.in/SabPaisa/sabPaisaInit?v=1";
            }
            $fullname = trim($first_name) . " " . trim($last_name);
            /* subpaisa encrypt data */
            $encData = "?clientCode=" . env('SABPAISA_CLIENT_CODE') . "&transUserName=" . env('SABPAISA_USERNAME') . "&transUserPassword=" . env('SABPAISA_PASSWORD') . "&amount=" . round($grandAmount) . "&amountType=INR&clientTxnId=" . $orderId . "&payerName=" . $fullname . "&payerMobile=" . $mobile . "&payerEmail=" . trim(strtolower($email)) . "&mcc=5137&channelId=#&callbackUrl=" . $returnUrl;

            /* generate subpaisa paymenturl */
            $AesCipher = new Authuntication();
            $encryptData = $AesCipher->encrypt(env('SABPAISA_AUTH_KEY'), env('SABPAISA_AUTH_IV'), $encData);

            /*$postData = array(
                'clientCode' => env('SABPAISA_CLIENT_CODE'),
                'encryptData' => $encryptData,
                'action' => $curlurl
            );*/

            $subpaisaData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 3, //la offer 1 or great deal offer
                'userid' => $offerId,
                'orderid' => $orderId,
                'orderamount' => round($grandAmount),
                'ordernote' => $products->productname
            );

            $response = SubpaisaEntry::insert($subpaisaData);
            $html = view('pg.pay', [
                'data' => $encryptData,
                'clientCode' => env('SABPAISA_CLIENT_CODE'),
                'action' => $curlurl
            ])->render();

            return response()->json(array('type' => 'SUCCESS', 'message' => 'Please wait... We are redirecting to the payment page.', 'html' => $html));
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.'));
        }
    }

    /* offer 1 response */
    public function offer1Response(Request $request)
    {
        try {
            //Log::info('request data - '. json_encode($request->all()));
            $meta = selfApplyMeta();
            $query = $request->input('encResponse');
            $authKey = env('SABPAISA_AUTH_KEY');
            $authIV = env('SABPAISA_AUTH_IV');

            $AesCipher = new Authuntication();
            $decText = $AesCipher->decrypt($authKey, $authIV, $query);

            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;

            $token = strtok($decText, "&");

            $i = 0;

            while ($token !== false) {
                $i = $i + 1;
                $token1 = strchr($token, "=");
                $token = strtok("&");
                $fstr = ltrim($token1, "=");

                if ($i == 1) {
                    $payerName = $fstr;
                }
                if ($i == 2)
                    $payerEmail = $fstr;
                if ($i == 3)
                    $payerMobile = $fstr;
                if ($i == 4)
                    $clientTxnId = $fstr;
                if ($i == 5)
                    $payerAddress = $fstr;
                if ($i == 6)
                    $amount = $fstr;
                if ($i == 7)
                    $clientCode = $fstr;
                if ($i == 8)
                    $paidAmount = $fstr;
                if ($i == 9)
                    $paymentMode = $fstr;
                if ($i == 10)
                    $bankName = $fstr;
                if ($i == 11)
                    $amountType = $fstr;
                if ($i == 12)
                    $status = $fstr;
                if ($i == 13)
                    $statusCode = $fstr;
                if ($i == 14)
                    $challanNumber = $fstr;
                if ($i == 15)
                    $sabpaisaTxnId = $fstr;
                if ($i == 16)
                    $sabpaisaMessage = $fstr;
                if ($i == 17)
                    $bankMessage = $fstr;
                if ($i == 18)
                    $bankErrorCode = $fstr;
                if ($i == 19)
                    $sabpaisaErrorCode = $fstr;
                if ($i == 20)
                    $bankTxnId = $fstr;
                if ($i == 21)
                    $transDate = $fstr;

                if ($token == true) {
                }
            }
            /* update client tax id in subpaisa_entry table */
            /* Log::info($clientTxnId);
            Log::info($paymentMode);
            Log::info($status);
            Log::info($statusCode);*/

            $paymentData = SubpaisaEntry::where('orderid', $clientTxnId)->first();
            $subpaisaData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'referenceid' => $sabpaisaTxnId,
                'txstatus' => $status,
                'paymentmode' => $paymentMode
            );
            //Log::info('subpaisa data - '. json_encode($subpaisaData));
            $response1 = SubpaisaEntry::where('id', $paymentData->id)->update($subpaisaData);
            //Log::info('subpaisa response - '. $response1);
            if ($statusCode == '0000') {
                $cardno = random_code_num(16);
                $userData = Cardoffer::where('id', $paymentData->userid)->first();
                $data = array(
                    'rec_date' => date('Y-m-d H:i:s'),
                    'card_number' => $cardno,
                    'registration_date' => date('Y-m-d'),
                    'expiry_date' => date('Y-m-d', strtotime('+3 months')),
                    'paymentid' => $sabpaisaTxnId,
                    'amount' => $paymentData->orderamount,
                    'isActive' => 1
                );
                $response = Cardoffer::where('id', $paymentData->userid)->update($data);
                if ($response) {
                    $regUser = UserRegistration::where('mobile', $userData->mobile)
                        ->where(['isActive' => 1, 'isDelete' => 0])
                        ->first();

                    if ($regUser) {
                        $converted = convertIntoCustomer($cardno, $regUser, $userData, $paymentData->orderamount, $sabpaisaTxnId, 2, 'hire-loan-agent', 'LA_', 1);
                        if (!$converted) {
                            Log::error("Conversion to customer failed for user: " . $regUser->id);
                            dd('check log');
                        }
                    } else {
                        $sent = sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);
                    }
                }
                session()->forget(['isMailSend', 'cardno']);
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => TRUE,
                ]);
            } else if ($statusCode == '0300') {
                //Log::info('else if');
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => FALSE,
                ]);
            } else {
                //Log::info('else');
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => FALSE,
                ]);
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return view('cardoffer-response', [
                'meta' => $meta,
                'response' => FALSE,
            ]);
        }
    }

    /* offer2 - phonepe */
    public function offer2()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.LA_OFFER_2'))->first();
        if ($products->inOffer == 1) {
            $productData = array(
                'inOffer' => $products->inOffer,
                'amount' => $products->amount,
                'offeramount' => $products->offeramount,
                'offerdate' => date('Y/m/d', strtotime('+1 days')) . ' 24:00:00',
                'payamount' => $products->offeramount + ($products->offeramount * 0.18)
            );
        } else {
            $productData = array(
                'inOffer' => 0,
                'amount' => $products->amount,
                'offeramount' => 0,
                'offerdate' => '',
                'payamount' => $products->amount + ($products->amount * 0.18)
            );
        }
        return view('loanAgent.offers.offer-2', compact('meta', 'productData'));
    }

    public function getOffer2(Request $request)
    {
        try {
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if ($profile) {
                return response()->json($profile);
            } else {
                $first_name = $inputs['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.LA_OFFER_2'))->first();
            // Log::info('products - '.json_encode($products));
            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == $mobile) {
                    $grandAmount = 1;
                    break; // Exit the loop once a match is found
                }
            }

            /* insert the adta in cardoffer */
            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $mobile], // Search condition
                [ // Values to update or insert
                    'rec_date' => now(),
                    'offerpage' => 2, // la offer 2 or elite offer
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'emailid' => $email,
                    'amount' => round($grandAmount),
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );

            // Get the ID of the updated or inserted record
            $record = DB::table('cardoffer')->where('mobile', $mobile)->first();
            $offerId = $record->id;

            $orderid = number_format(microtime(true) * 1000, 0, '.', '');
            $returnUrl = 'https://wisemudra.com/api/loan-agent/elite-offer-response';
            $password = trim(random_code(6));
            Session::put('orderid', $orderid);
            Session::save();
            Cache::put('user_password', $password, $this->lifetime);

            if (env('LYRA_MODE') == "PROD") {
                $curlurl = "https://api.in.lyra.com/pg/rest/v1/charge";
            } else {
                $curlurl = "https://api.in.lyra.com/pg/rest/v1/charge";
            }

            /* lyra post data */
            $postData = array(
                "orderId" => $orderid,
                "currency" => 'INR',
                "amount" => floor($grandAmount) * 100,
                "orderInfo" => $products->productname,
                "maxAgeInHours" => '240',
                "customer" => array(
                    "uid" => $offerId,
                    "name" => $first_name . ' ' . $last_name,
                    "emailId" => $email,
                    "phone" => $mobile
                ),
                "webhook" => array(
                    "url" => $returnUrl
                ),
                "return" => array(
                    "method" => 'POST',
                    "url" => $returnUrl,
                    "timeout" => '0'
                )
            );
            //Log::info('lyra entry - '. json_encode($postData));
            /* generate lyra paymenturl */
            $payurl = getlyrapaymenturl($curlurl, $postData);
            $lyraData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 4, //sa offer 1 or prime offer
                'userid' => $offerId,
                'orderid' => $orderid,
                'orderamount' => floor($grandAmount),
                'ordernote' => $products->productname,
            );
            //Log::info('data - '.json_encode($lyraData));

            $response = LyraEntry::insert(values: $lyraData);
            if ($payurl) {
                if ($payurl->paymentLink) {
                    return response()->json(array('type' => 'SUCCESS', 'message' => 'Please wait... We are redirecting to the payment page.', 'url' => $payurl->paymentLink));
                } else {
                    return response()->json(array('type' => 'ERROR', 'url' => route('loan.agent.offer2')));
                }
            } else {
                return response()->json(array('type' => 'ERROR', 'url' => route('loan.agent.offer4')));
            }
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['type' => 'ERROR', 'message' => 'Oops! Something went wrong.']);
        }
    }

    public function offer2Response(Request $request)
    {
        try {
            $inputs = $request->all();
            //Log::info(json_encode($inputs));
            $meta = selfApplyMeta();

            if (isset($inputs["vads_order_id"])) {
                $orderId = $inputs["vads_order_id"];
                $orderAmount = $inputs["vads_amount"];
                $responseCode = $inputs["vads_charge_status"];
                $txnId = $inputs["vads_trans_uuid"];

                $paymentData = LyraEntry::where('orderid', $orderId)->first();

                $lyraData = array(
                    'rec_date' => date('Y-m-d H:i:s'),
                    'orderamount' => $orderAmount / 100,
                    'statuscode' => $responseCode,
                    'transactionid' => $txnId
                );

                $response1 = LyraEntry::where('id', $paymentData->id)->update($lyraData);

                $userData = Cardoffer::where('id', $paymentData->userid)->first();

                if ($responseCode == "PAID") {
                    $cardno = random_code_num(16);
                    $orderAmountInRupees = $orderAmount / 100;

                    $data = [
                        'rec_date' => now(),
                        'card_number' => $cardno,
                        'registration_date' => date('Y-m-d'),
                        'expiry_date' => date('Y-m-d', strtotime('+9 months')),
                        'amount' => $orderAmountInRupees,
                        'paymentid' => $txnId,
                        'isActive' => 1
                    ];

                    $response = Cardoffer::where('id', $paymentData->userid)->update($data);

                    if ($response) {
                        $regUser = UserRegistration::where('mobile', $userData->mobile)
                            ->where(['isActive' => 1, 'isDelete' => 0])
                            ->first();

                        if ($regUser) {
                            $cardOffer = Cardoffer::where('id', $paymentData->userid)->first();
                            $converted = convertIntoCustomer($cardno, $regUser, $cardOffer, $orderAmountInRupees, $txnId, 2, 'hire-loan-agent', 'LA_', 2);
                            if (!$converted) {
                                Log::error("Conversion to customer failed for user: " . $regUser->id);
                                dd('check log');
                            }
                        } else {
                            sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);
                        }
                    }
                    session()->forget(['isMailSend', 'cardno']);
                    return view('cardoffer-response', compact('meta', 'response'));
                } else {
                    $response = false;
                    // Optionally log or send failed payment notification
                    return view('cardoffer-response', compact('meta', 'response'));
                }
            } else {
                $response = FALSE;
                return View('cardoffer-response', compact('meta', 'response'));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            dd('Ops! Something went wrong.');
        }
    }

    public function getOffer2_paygic(Request $request)
    {
        try {
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if ($profile) {
                return response()->json($profile);
            } else {
                $buyerFirstName = $inputs['first_name'];
                $buyerLastName = $inputs['last_name'];
                $buyerPhone = $inputs['mobile'];
                $buyerEmail = $inputs['email'];
                $buyerCountry = 'India';
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.LA_OFFER_4'))->first();
            // Log::info('products - '.json_encode($products));

            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = number_format($amount + ($amount * 0.18), 2);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == $buyerPhone) {
                    $grandAmount = 10;
                    break; // Exit the loop once a match is found
                }
            }

            /* ORDER & TOKEN */
            $response = createMerchantToken();
            $orderid  = "PAYGIC" . number_format(microtime(true) * 1000, 0, '.', '');
            $taxNote     = '';
            $token = $response['data']['token'];

            $returnUrl = route('api.loan.agent.offer2Response', [
                'orderId' => $orderid,
                'token'   => $token
            ]);

            $postData = [
                'mid' => env('PAYGIC_MERCHANT_ID'),
                'merchantReferenceId' => $orderid,
                'amount' => $grandAmount,
                'customer_mobile' => $buyerPhone,
                'customer_name' => $buyerFirstName . ' ' . $buyerLastName,
                'customer_email' => $buyerEmail,
                'redirect_URL' => $returnUrl,
                'failed_URL' => $returnUrl,
            ];

            $createresponse = createPaymentPage($postData, $token);
            $post_data = json_decode($createresponse, true);

            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $buyerPhone], // Search condition
                [ // Values to update or insert
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 2, // la offer 2 or elite offer
                    'first_name' => $buyerFirstName,
                    'last_name' => $buyerLastName,
                    'emailid' => $buyerEmail,
                    'amount' => $grandAmount,
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );
            $record = DB::table('cardoffer')->where('mobile', $buyerPhone)->first();

            /* PAYGIC LOG */
            $paygicData = array(
                'rec_date'    => now(),
                'entryfor'    => 4, //sa offer 1 or prime offer
                'userid'      => $record->id,
                'orderid'     => $orderid,
                'orderamount' => $grandAmount,
                'ordernote'   => $taxNote,
                'referenceid' => null,
                'txstatus'    => null,
                'paymentmode' => null
            );
            $response = PaygicEntry::create($paygicData);

            return response()->json([
                'type' => 'SUCCESS',
                'message' => 'Please wait we are redirecting...',
                'redirect' => $post_data['data']['payPageUrl'],
            ]);
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['type' => 'ERROR', 'message' => 'Oops! Something went wrong.']);
        }
    }

    public function offer2Response_paygic(Request $request, $orderId, $token)
    {
        try {
            //dd($request->all());
            $inputs = $request->all();
            $meta = selfApplyMeta();

            $createresponse = checkPaymentStatus($orderId, $token);
            $response_data = json_decode($createresponse, true);

            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $paymentdata = PaygicEntry::where('orderid', $response_data['data']['merchantReferenceId'])->firstOrFail();

            // Update Paygic log
            PaygicEntry::where('id', $paymentdata->id)->update([
                'rec_date'     => now(),
                'referenceid'  => $response_data['data']['paygicReferenceId'],
                'txstatus'     => $response_data['txnStatus'],
                'paymentmode'  => '',
            ]);

            if ($response_data['txnStatus'] == 'SUCCESS') {
                $userData = Cardoffer::where('id', $paymentdata->userid)->first();
                $cardno = random_code_num(16);

                $data = array(
                    'rec_date' => date('Y-m-d H:i:s'),
                    'card_number' => $cardno,
                    'registration_date' => date('Y-m-d'),
                    'expiry_date' => date('Y-m-d', strtotime('+9 months')),
                    'paymentid' => $response_data['data']['paygicReferenceId'],
                    'isActive' => 1
                );

                $response = Cardoffer::where('id', $paymentdata->userid)->update($data);

                $sent = sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);

                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => TRUE,
                ]);
            } else {
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => FALSE,
                ]);
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            dd('Ops! Something went wrong.');
        }
    }

    /* load offer 3 - cipherpay*/
    public function offer3_cipherpay()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.LA_OFFER_3'))->first();
        if ($products->inOffer == 1) {
            $productData = array(
                'inOffer' => $products->inOffer,
                'amount' => $products->amount,
                'offeramount' => $products->offeramount,
                'offerdate' => date('Y/m/d', strtotime('+1 days')) . ' 24:00:00',
                'payamount' => $products->offeramount + ($products->offeramount * 0.18)
            );
        } else {
            $productData = array(
                'inOffer' => 0,
                'amount' => $products->amount,
                'offeramount' => 0,
                'offerdate' => '',
                'payamount' => $products->amount + ($products->amount * 0.18)
            );
        }
        return view('loanAgent.offers.offer-3', compact('meta', 'productData'));
    }

    /* get offer two in this send on payment gateway */
    public function getOffer3_cipherpay(Request $request)
    {
        try {
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if ($profile) {
                return response()->json($profile);
            } else {
                $first_name = $inputs['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.LA_OFFER_3'))->first();
            //Log::info('products - '.json_encode($products));
            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == $mobile) {
                    $grandAmount = 1;
                    break; // Exit the loop once a match is found
                }
            }

            /* insert the adta in cardoffer */
            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $mobile], // Search condition
                [ // Values to update or insert
                    'rec_date' => now(),
                    'offerpage' => 3, //la offer 3 or ultra saver offer
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'emailid' => $email,
                    'amount' => round($grandAmount),
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );

            // Get the ID of the updated or inserted record
            $record = DB::table('cardoffer')->where('mobile', $mobile)->first();
            $offerId = $record->id;

            //Log::info('Offer data - '. $offerId);
            $orderId = number_format(microtime(true) * 1000, 0, '.', '');
            $encData = null;
            $returnUrl = 'https://wisemudra.com/api/loan-agent/ultra-saver-offer-response';

            /* cipherPay PG starts */
            $refId = rand(1000, 9999);
            $request = array(
                "method" => "POST",
                "url" => "payin/dynamic-qr",
                "parameter" => [
                    //'receiver_vpa' => "cpy.WIMDRA@fin",
                    'receiver_vpa' => "cpy.wisemudra@finobank",
                    'amount' => round($grandAmount), // amount
                    'remarks' => "Dynamic QR", // remarks
                    'refid' => $refId, //refrence id
                    'expiry' => "2", //in minutes
                    'type' => "QR"
                ]
            );
            Session::forget('refid');
            Session::put('refid', $refId);
            Session::save();
            //Log::info(json_encode($request));
            $cipherPay = new CipherPay();
            $response = $cipherPay->hit($request);
            $response = $cipherPay->finalResponse($response);
            //Log::info('final Response - ' .json_encode($response));
            /* cipherPay PG ends */

            $cipherPayData = array(
                'rec_date' => now(),
                'entryfor' => 5, // la offer 3 or ultra saver offer
                'userid' => $offerId,
                'orderid' => $response['data']['txnid'],
                'orderamount' => round($grandAmount),
                'ordernote' => $products->productname
            );
            //Log::info('Cipher insert - '. json_encode($cipherPayData));

            $res = CipherPayEntry::insert($cipherPayData);
            $html = view('pg.cipherQR', compact('response'))->render();
            return response()->json(array('type' => 'SUCCESS', 'message' => 'Please wait... We are redirecting to the payment page.', 'html' => $html));
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.'));
        }
    }


    public function offer3Response_cipherpay(Request $request)
    {
        try {
            $meta = selfApplyMeta();
            $datas = Session::get('cipherResponse');
            $paymentData = CipherPayEntry::where('orderid', $datas['data']['txnid'])->first();
            //Log::info('cipher table data - '. json_encode($paymentData));

            $cipherData = array(
                'rec_date' => now(),
                'referenceid' => $datas['data']['upiRefId'],
                'txstatus' => $datas['data']['status'],
                'paymentmode' => $datas['data']['remarks'],
                'ordernote' => $paymentData->ordernote . ' (utr - ' . $datas['data']['utr'] . ')'
            );

            $response1 = CipherPayEntry::where('id', $paymentData->id)->update($cipherData);

            $userData = Cardoffer::where('id', $paymentData->userid)->first();
            $cardno = random_code_num(16);
            $data = array(
                'rec_date' => now(),
                'card_number' => $cardno,
                'registration_date' => now(),
                'expiry_date' => date('Y-m-d', strtotime('+9 months')),
                'paymentid' => $datas['data']['txnid'],
                'isActive' => 1
            );

            $response = Cardoffer::where('id', $paymentData->userid)->update($data);

            $sent = sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);

            return view('cardoffer-response', [
                'meta' => $meta,
                'response' => $datas['data']['status'] == 1 ? TRUE : FALSE,
                /*'clientTxnId' => $datas['data']['txnid'],
                'amount' => $datas['data']['amount'],
                'paymentMode' => $datas['data']['remarks'],
	            'payerName' => $datas['data']['sender_name'],
	            'payerEmail' => $payerEmail,
	            'payerMobile' => $payerMobile,*/
                // Add other variables as needed
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back();
        }
    }


    /* offerpage 3 - veegah pay integrate starts */
    public function offer3()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.LA_OFFER_3'))->first();
        if ($products->inOffer == 1) {
            $productData = array(
                'inOffer' => $products->inOffer,
                'amount' => $products->amount,
                'offeramount' => $products->offeramount,
                'offerdate' => date('Y/m/d', strtotime('+1 days')) . ' 24:00:00',
                'payamount' => $products->offeramount + ($products->offeramount * 0.18)
            );
        } else {
            $productData = array(
                'inOffer' => 0,
                'amount' => $products->amount,
                'offeramount' => 0,
                'offerdate' => '',
                'payamount' => $products->amount + ($products->amount * 0.18)
            );
        }
        return view('loanAgent.offers.offer-3', compact('meta', 'productData'));
    }

    public function getOffer3(Request $request)
    {
        try {
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if ($profile) {
                return response()->json($profile);
            } else {
                $first_name = $inputs['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.LA_OFFER_3'))->first();
            //Log::info('products - '.json_encode($products));
            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == $mobile) {
                    $grandAmount = 1;
                    break; // Exit the loop once a match is found
                }
            }

            /* insert the adta in cardoffer */
            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $mobile], // Search condition
                [ // Values to update or insert
                    'rec_date' => now(),
                    'offerpage' => 3, // la offer 3 or ultrasaver offer
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'emailid' => $email,
                    'amount' => round($grandAmount),
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );

            // Get the ID of the updated or inserted record
            $record = DB::table('cardoffer')->where('mobile', $mobile)->first();
            $offerId = $record->id;

            //Log::info('Offer data - '. $offerId);
            $orderId = 'KRBZVGP' . number_format(microtime(true) * 1000, 0, '.', '');
            $encData = null;
            $returnUrl = 'https://wisemudra.com/api/loan-agent/ultra-saver-offer-response';

            /* veegah PG starts */

            $terminalId = env('VEEGAH_TERMINAL_ID');
            $password = env('VEEGAH_TERMINAL_PASSWORD');
            $mkey = env('VEEGAH_MERCHANT_KEY');

            // data sequence is - orderId|terminalId|password|merchantKey|amount|currency
            //$signdata = $orderid."|TER7990817|TER25041201011970543064|f5949cf7946afa557191b8a18504c2a847a6d9ff08c28ec2fd456322889d1451|".$roundamount."|INR";
            $signdata = $orderId . "|" . $terminalId . "|" . $password . "|" . $mkey . "|" . round($grandAmount) . "|INR";
            $signature = hash('sha256', $signdata);

            $postdata = array(
                "referenceId" => $orderId,
                "terminalId" => $terminalId,
                "password" => $password,
                "signature" =>  $signature, //Generated signature
                "paymentType" => "1",
                "amount" => round($grandAmount),
                "currency" => "INR",
                "order" => array(
                    "orderId" => $orderId,  // Related orderId
                    "description" => "Ultra Saver Offer"
                ),
                "customer" => array(
                    "customerEmail" => $email,
                    "billingAddressStreet" => '',
                    "billingAddressCity" => "",
                    "billingAddressState" => "",
                    "billingAddressPostalCode" => "",
                    "billingAddressCountry" => "IN"
                ),
                "additionalDetails" => array(
                    "userData" => "{\"entryone\":\"abc\",\"entrytwo\":\"def\",\"entrythree\":\"xyz\",\"receiptUrl\":\"$returnUrl\"}"
                ),
            );

            $veegahData = array(
                'rec_date' => now(),
                'entryfor' => 5, //sa offer 3 or ultra saver offer
                'userid' => $offerId,
                'orderid' => $orderId,
                'orderamount' => round($grandAmount),
                'ordernote' => $products->productname
            );

            $res = VeegahEntry::insert($veegahData);
            $prodUrl = "https://test-vegaah.concertosoft.com/vegaahpayments/v2/payments/pay-request";
            if (env('VEEGAH_PROD')) {
                $prodUrl = "https://checkout.vegaah.com/vegaahpayments/v2/payments/pay-request";
            }
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $prodUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS =>  json_encode($postdata),
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "accept: application/json"
                ],
            ]);
            $vegaahRes = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $post_decode_data =  json_decode($vegaahRes);
            /* veegah PG ends */
            if ($post_decode_data) {
                if ($post_decode_data->paymentLink->linkUrl && $post_decode_data->transactionId) {
                    $redirect_url = $post_decode_data->paymentLink->linkUrl . $post_decode_data->transactionId;
                    return response()->json(array('type' => 'SUCCESS', 'message' => 'Please wait... We are redirecting to the payment page.', 'url' => $redirect_url));
                } else {
                    return response()->json(array('type' => 'ERROR', 'url' => route('loan.agent.offer3')));
                }
            } else {
                return response()->json(array('type' => 'ERROR', 'url' => route('loan.agent.offer3')));
            }
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.'));
        }
    }

    public function offer3Response(Request $request)
    {
        try {
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $meta = selfApplyMeta();

            $jsonData = file_get_contents("php://input");
            parse_str($jsonData, $parsedData);
            unset($parsedData['termId']);

            $decodedData = urldecode($parsedData['data']);
            $decodedData = str_replace(' ', '+', $decodedData);

            $encryptedResponse = base64_decode($decodedData, true);

            $merKey = env('VEEGAH_MERCHANT_KEY');
            $binaryKey = hex2bin($merKey);

            $decryptedData = openssl_decrypt($encryptedResponse, 'AES-256-ECB', $binaryKey, OPENSSL_RAW_DATA);

            if ($decryptedData === false) {
                return view('cardoffer-response', ['meta' => $meta, 'response' => FALSE]);
            }

            $resultdata = json_decode($decryptedData, true);
            if ($resultdata === null) {
                return view('cardoffer-response', ['meta' => $meta, 'response' => FALSE]);
            }

            $paymentData = VeegahEntry::where('orderid', $resultdata['orderDetails']['orderId'])->first();

            $veegahData = array(
                'rec_date' => now(),
                'referenceid' => $resultdata['transactionId'],
                'txstatus' => $resultdata['result'],
                'paymentmode' => $resultdata['paymentInstrument']['paymentMethod']
            );
            $response1 = VeegahEntry::where('id', $paymentData->id)->update($veegahData);
            $userData = Cardoffer::where('id', $paymentData->userid)->first();
            if ($resultdata['result'] == 'SUCCESS') {
                $isEntry = Cardoffer::where('paymentid', $resultdata['transactionId'])->where('isDelete', 0)->count();
                if ($isEntry == 0) {
                    $cardno = random_code_num(16);

                    $data = array(
                        'rec_date' => now(),
                        'card_number' => $cardno,
                        'registration_date' => Carbon::now()->toDateString(),
                        'expiry_date' => Carbon::now()->addMonth()->toDateString(),
                        'paymentid' => $resultdata['transactionId'],
                        'isActive' => 1
                    );
                    $response = Cardoffer::where('id', $paymentData->userid)->update($data);

                    if ($response) {
                        $regUser = UserRegistration::where('mobile', $userData->mobile)
                            ->where(['isActive' => 1, 'isDelete' => 0])
                            ->first();

                        if ($regUser) {
                            $converted = convertIntoCustomer($cardno, $regUser, $userData, $paymentData->orderamount, $resultdata['transactionId'], 2, 'hire-loan-agent', 'LA_', 5);
                            if (!$converted) {
                                Log::error("Conversion to customer failed for user: " . $regUser->id);
                                dd('check log');
                            }
                        } else {
                            $sent = sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);
                        }
                    }
                    session()->forget(['isMailSend', 'cardno']);
                    return view('cardoffer-response', [
                        'meta' => $meta,
                        'response' => TRUE,
                    ]);
                } else {
                    //Log::info('Phone Pe TRUE Else');
                    return view('cardoffer-response', [
                        'meta' => $meta,
                        'response' => TRUE,
                    ]);
                }
            } else {
                //Log::info('PhonePe False');
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => FALSE,
                ]);
            }
        } catch (\Exception $e) {
            Log::info('An error occured in offer3 response - ' . $e->getMessage());
            dd('Ops! Something went wrong.');
        }
    }
    /* offerpage 3 - veegah pay integrate ends */

    /* offer4 - phonepe */
    public function offer4()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.LA_OFFER_4'))->first();
        if ($products->inOffer == 1) {
            $productData = array(
                'inOffer' => $products->inOffer,
                'amount' => $products->amount,
                'offeramount' => $products->offeramount,
                'offerdate' => date('Y/m/d', strtotime('+1 days')) . ' 24:00:00',
                'payamount' => $products->offeramount + ($products->offeramount * 0.18)
            );
        } else {
            $productData = array(
                'inOffer' => 0,
                'amount' => $products->amount,
                'offeramount' => 0,
                'offerdate' => '',
                'payamount' => $products->amount + ($products->amount * 0.18)
            );
        }
        return view('loanAgent.offers.offer-4', compact('meta', 'productData'));
    }

    public function getOffer4_paygic(Request $request)
    {
        try {
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if ($profile) {
                return response()->json($profile);
            } else {
                $buyerFirstName = $inputs['first_name'];
                $buyerLastName = $inputs['last_name'];
                $buyerPhone = $inputs['mobile'];
                $buyerEmail = $inputs['email'];
                $buyerCountry = 'India';
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.LA_OFFER_4'))->first();
            // Log::info('products - '.json_encode($products));

            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = number_format($amount + ($amount * 0.18), 2);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == $buyerPhone) {
                    $grandAmount = 10;
                    break; // Exit the loop once a match is found
                }
            }

            /* ORDER & TOKEN */
            $response = createMerchantToken();
            $orderid  = "PAYGIC" . number_format(microtime(true) * 1000, 0, '.', '');
            $taxNote     = '';
            $token = $response['data']['token'];

            $returnUrl = route('api.loan.agent.offer4Response', [
                'orderId' => $orderid,
                'token'   => $token
            ]);

            $postData = [
                'mid' => env('PAYGIC_MERCHANT_ID'),
                'merchantReferenceId' => $orderid,
                'amount' => $grandAmount,
                'customer_mobile' => $buyerPhone,
                'customer_name' => $buyerFirstName . ' ' . $buyerLastName,
                'customer_email' => $buyerEmail,
                'redirect_URL' => $returnUrl,
                'failed_URL' => $returnUrl,
            ];

            $createresponse = createPaymentPage($postData, $token);
            $post_data = json_decode($createresponse, true);

            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $buyerPhone], // Search condition
                [ // Values to update or insert
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 8, //big offer or LA offer 4
                    'first_name' => $buyerFirstName,
                    'last_name' => $buyerLastName,
                    'emailid' => $buyerEmail,
                    'amount' => $grandAmount,
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );
            $record = DB::table('cardoffer')->where('mobile', $buyerPhone)->first();

            /* PAYGIC LOG */
            $paygicData = array(
                'rec_date'    => now(),
                'entryfor'    => 10, // la offer 4 or big offer
                'userid'      => $record->id,
                'orderid'     => $orderid,
                'orderamount' => $grandAmount,
                'ordernote'   => $taxNote,
                'referenceid' => null,
                'txstatus'    => null,
                'paymentmode' => null
            );
            $response = PaygicEntry::create($paygicData);

            return response()->json([
                'type' => 'SUCCESS',
                'message' => 'Please wait we are redirecting...',
                'redirect' => $post_data['data']['payPageUrl'],
            ]);
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['type' => 'ERROR', 'message' => 'Oops! Something went wrong.']);
        }
    }

    public function offer4Response_paygic(Request $request, $orderId, $token)
    {
        try {
            //dd($request->all());
            $inputs = $request->all();
            $meta = selfApplyMeta();

            $createresponse = checkPaymentStatus($orderId, $token);
            $response_data = json_decode($createresponse, true);

            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $paymentdata = PaygicEntry::where('orderid', $response_data['data']['merchantReferenceId'])->firstOrFail();

            // Update Paygic log
            PaygicEntry::where('id', $paymentdata->id)->update([
                'rec_date'     => now(),
                'referenceid'  => $response_data['data']['paygicReferenceId'],
                'txstatus'     => $response_data['txnStatus'],
                'paymentmode'  => '',
            ]);

            if ($response_data['txnStatus'] == 'SUCCESS') {
                $userData = Cardoffer::where('id', $paymentdata->userid)->first();
                $cardno = random_code_num(16);

                $data = array(
                    'rec_date' => date('Y-m-d H:i:s'),
                    'card_number' => $cardno,
                    'registration_date' => date('Y-m-d'),
                    'expiry_date' => date('Y-m-d', strtotime('+9 months')),
                    'paymentid' => $response_data['data']['paygicReferenceId'],
                    'isActive' => 1
                );

                $response = Cardoffer::where('id', $paymentdata->userid)->update($data);

                $sent = sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);

                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => TRUE,
                ]);
            } else {
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => FALSE,
                ]);
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            dd('Ops! Something went wrong.');
        }
    }

    public function getOffer4(Request $request)
    {
        try {
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if ($profile) {
                return response()->json($profile);
            } else {
                $first_name = $inputs['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.LA_OFFER_4'))->first();
            // Log::info('products - '.json_encode($products));
            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == $mobile) {
                    $grandAmount = 1;
                    break; // Exit the loop once a match is found
                }
            }

            /* insert the adta in cardoffer */
            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $mobile], // Search condition
                [ // Values to update or insert
                    'rec_date' => now(),
                    'offerpage' => 8, //big offer or LA offer 4
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'emailid' => $email,
                    'amount' => round($grandAmount),
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );

            // Get the ID of the updated or inserted record
            $record = DB::table('cardoffer')->where('mobile', $mobile)->first();
            $offerId = $record->id;

            $orderid = number_format(microtime(true) * 1000, 0, '.', '');
            $returnUrl = 'https://wisemudra.com/api/loan-agent/big-offer-response';
            $password = trim(random_code(6));
            Session::put('orderid', $orderid);
            Session::save();
            Cache::put('user_password', $password, $this->lifetime);

            if (env('LYRA_MODE') == "PROD") {
                $curlurl = "https://api.in.lyra.com/pg/rest/v1/charge";
            } else {
                $curlurl = "https://api.in.lyra.com/pg/rest/v1/charge";
            }

            /* lyra post data */
            $postData = array(
                "orderId" => $orderid,
                "currency" => 'INR',
                "amount" => floor($grandAmount) * 100,
                "orderInfo" => $products->productname,
                "maxAgeInHours" => '240',
                "customer" => array(
                    "uid" => $offerId,
                    "name" => $first_name . ' ' . $last_name,
                    "emailId" => $email,
                    "phone" => $mobile
                ),
                "webhook" => array(
                    "url" => $returnUrl
                ),
                "return" => array(
                    "method" => 'POST',
                    "url" => $returnUrl,
                    "timeout" => '0'
                )
            );
            //Log::info('lyra entry - '. json_encode($postData));
            /* generate lyra paymenturl */
            $payurl = getlyrapaymenturl($curlurl, $postData);
            $lyraData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 10, // la offer 4 or big offer
                'userid' => $offerId,
                'orderid' => $orderid,
                'orderamount' => floor($grandAmount),
                'ordernote' => $products->productname,
            );
            //Log::info('data - '.json_encode($lyraData));

            $response = LyraEntry::insert(values: $lyraData);
            if ($payurl) {
                if ($payurl->paymentLink) {
                    return response()->json(array('type' => 'SUCCESS', 'message' => 'Please wait... We are redirecting to the payment page.', 'url' => $payurl->paymentLink));
                } else {
                    return response()->json(array('type' => 'ERROR', 'url' => route('loan.agent.offer4')));
                }
            } else {
                return response()->json(array('type' => 'ERROR', 'url' => route('loan.agent.offer4')));
            }
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['type' => 'ERROR', 'message' => 'Oops! Something went wrong.']);
        }
    }

    public function offer4Response(Request $request)
    {
        try {
            $inputs = $request->all();
            //Log::info(json_encode($inputs));
            $meta = selfApplyMeta();

            if (isset($inputs["vads_order_id"])) {
                $orderId = $inputs["vads_order_id"];
                $orderAmount = $inputs["vads_amount"];
                $responseCode = $inputs["vads_charge_status"];
                $txnId = $inputs["vads_trans_uuid"];

                $paymentData = LyraEntry::where('orderid', $orderId)->first();

                $lyraData = array(
                    'rec_date' => date('Y-m-d H:i:s'),
                    'orderamount' => $orderAmount / 100,
                    'statuscode' => $responseCode,
                    'transactionid' => $txnId
                );

                $response1 = LyraEntry::where('id', $paymentData->id)->update($lyraData);

                $userData = Cardoffer::where('id', $paymentData->userid)->first();

                if ($responseCode == "PAID") {
                    $cardno = random_code_num(16);
                    $orderAmountInRupees = $orderAmount / 100;

                    $data = [
                        'rec_date' => now(),
                        'card_number' => $cardno,
                        'registration_date' => date('Y-m-d'),
                        'expiry_date' => date('Y-m-d', strtotime('+9 months')),
                        'amount' => $orderAmountInRupees,
                        'paymentid' => $txnId,
                        'isActive' => 1
                    ];

                    $response = Cardoffer::where('id', $paymentData->userid)->update($data);

                    if ($response) {
                        $regUser = UserRegistration::where('mobile', $userData->mobile)
                            ->where(['isActive' => 1, 'isDelete' => 0])
                            ->first();

                        if ($regUser) {
                            $cardOffer = Cardoffer::where('id', $paymentData->userid)->first();
                            $converted = convertIntoCustomer($cardno, $regUser, $cardOffer, $orderAmountInRupees, $txnId, 4, 'hire-loan-agent', 'LA_', 4);
                            if (!$converted) {
                                Log::error("Conversion to customer failed for user: " . $regUser->id);
                                dd('check log');
                            }
                        } else {
                            sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);
                        }
                    }
                    session()->forget(['isMailSend', 'cardno']);
                    return view('cardoffer-response', compact('meta', 'response'));
                } else {
                    $response = false;
                    // Optionally log or send failed payment notification
                    return view('cardoffer-response', compact('meta', 'response'));
                }
            } else {
                $response = FALSE;
                return View('cardoffer-response', compact('meta', 'response'));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            dd('Ops! Something went wrong.');
        }
    }

    public function offer4Response_zaakpay(Request $request)
    {
        try {
            //Log::info('request data - '.json_encode($request->all()));
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $meta = selfApplyMeta();
            $orderId = $request->input('orderId');
            $responseCode = $request->input('responseCode');
            $orderAmount = $request->input('amount') / 100;
            $txnId = $request->input('pgTransId');
            $paymentMode = $request->input('paymentMode');
            $recd_checksum = $request->input('checksum');

            $checksum = $checksumData = '';
            $checksumsequence = array(
                "amount",
                "bank",
                "bankid",
                "cardId",
                "cardScheme",
                "cardToken",
                "cardhashid",
                "doRedirect",
                "orderId",
                "paymentMethod",
                "paymentMode",
                "responseCode",
                "responseDescription",
                "productDescription",
                "product1Description",
                "product2Description",
                "product3Description",
                "product4Description",
                "pgTransId",
                "pgTransTime"
            );

            foreach ($checksumsequence as $seqvalue) {
                if (array_key_exists($seqvalue, $request->all())) {
                    $checksumData .= $seqvalue;
                    $checksumData .= "=";
                    $checksumData .= $request->input($seqvalue);
                    $checksumData .= "&";
                }
            }
            //Log::info('checksum data - '. json_encode($checksumData));
            $checksum = hash_hmac('sha256', $checksumData, env('ZAAKPAY_SECRET_KEY'));
            //Log::info('generated checksum - '. $checksum);
            //Log::info('recd checksum - '. $recd_checksum);

            if ($checksum == $recd_checksum) {
                $paymentData = ZaakpayEntry::where('orderid', $orderId)->first();
                //Log::info('paymentData - '.json_encode($paymentData));

                $zaakPayData = array(
                    'rec_date' => now(),
                    'orderamount' => $orderAmount,
                    'statuscode' => $responseCode,
                    'transactionid' => $txnId,
                    'paymentmode' => $paymentMode
                );
                Log::info('zaakPayData - ' . json_encode($zaakPayData));
                $response1 = ZaakpayEntry::where('id', $paymentData->id)->update($zaakPayData);
                if ($responseCode == 100) {
                    $cardno = random_code_num(16);
                    $userData = Cardoffer::where('id', $paymentData->userid)->first();
                    $data = array(
                        'rec_date' => now(),
                        'card_number' => $cardno,
                        'registration_date' => now(),
                        'expiry_date' => date('Y-m-d', strtotime('+9 months')),
                        'amount' => $orderAmount,
                        'paymentid' => $txnId,
                        'isActive' => 1
                    );
                    $response = Cardoffer::where('id', $paymentData->userid)->update($data);
                    if ($response) {
                        $regUser = UserRegistration::where('mobile', $userData->mobile)
                            ->where(['isActive' => 1, 'isDelete' => 0])
                            ->first();

                        if ($regUser) {
                            $converted = convertIntoCustomer($cardno, $regUser, $userData, $paymentData->orderamount, $txnId, 2, 'hire-loan-agent', 'LA_', 10);
                            if (!$converted) {
                                Log::error("Conversion to customer failed for user: " . $regUser->id);
                                dd('check log');
                            }
                        } else {
                            $sent = sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);
                        }
                    }
                    session()->forget(['isMailSend', 'cardno']);
                    return view('cardoffer-response', [
                        'meta' => $meta,
                        'response' => TRUE,
                    ]);
                }
            } else {
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => FALSE,
                ]);
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            dd('Ops! Something went wrong.');
        }
    }

    /* offer 5 - airpay */
    public function offer5()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.LA_OFFER_4'))->first();

        if ($products->inOffer == 1) {
            $productData = array(
                'inOffer' => $products->inOffer,
                'amount' => $products->amount,
                'offeramount' => $products->offeramount,
                'offerdate' => date('Y/m/d', strtotime('+1 days')) . ' 24:00:00',
                'payamount' => $products->offeramount + ($products->offeramount * 0.18)
            );
        } else {
            $productData = array(
                'inOffer' => 0,
                'amount' => $products->amount,
                'offeramount' => 0,
                'offerdate' => '',
                'payamount' => $products->amount + ($products->amount * 0.18)
            );
        }
        return view('loanAgent.offers.offer-5', compact('meta', 'productData'));
    }

    public function getOffer5(Request $request)
    {
        try {
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if ($profile) {
                return response()->json($profile);
            } else {
                $first_name = $inputs['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.LA_OFFER_4'))->first();
            //Log::info('products - '.json_encode($products));
            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array
            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == $mobile) {
                    $grandAmount = 1;
                    break; // Exit the loop once a match is found
                }
            }

            /* insert the adta in cardoffer */
            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $mobile], // Search condition
                [ // Values to update or insert
                    'rec_date' => now(),
                    'offerpage' => 1, //La offer 1 or great deal offer
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'emailid' => $email,
                    'amount' => floor($grandAmount),
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );

            // Get the ID of the updated or inserted record
            $record = DB::table('cardoffer')->where('mobile', $mobile)->first();
            $encData = null;
            $offerId = $record->id;

            //Log::info('Offer data - '. $offerId);
            $orderId = number_format(microtime(true) * 1000, 0, '.', '');
            $returnUrl = 'https://wisemudra.com/api/loan-agent/big-benefit-response';

            if (env('SABPAISA_MODE') == "PROD") {
                $curlurl = "https://securepay.sabpaisa.in/SabPaisa/sabPaisaInit?v=1";
            } else {
                $curlurl = "https://stage-securepay.sabpaisa.in/SabPaisa/sabPaisaInit?v=1";
            }
            $fullname = trim($first_name) . " " . trim($last_name);
            /* subpaisa encrypt data */
            $encData = "?clientCode=" . env('SABPAISA_CLIENT_CODE') . "&transUserName=" . env('SABPAISA_USERNAME') . "&transUserPassword=" . env('SABPAISA_PASSWORD') . "&amount=" . round($grandAmount) . "&amountType=INR&clientTxnId=" . $orderId . "&payerName=" . $fullname . "&payerMobile=" . $mobile . "&payerEmail=" . trim(strtolower($email)) . "&mcc=5137&channelId=#&callbackUrl=" . $returnUrl;

            /* generate subpaisa paymenturl */
            $AesCipher = new Authuntication();
            $encryptData = $AesCipher->encrypt(env('SABPAISA_AUTH_KEY'), env('SABPAISA_AUTH_IV'), $encData);

            /*$postData = array(
                'clientCode' => env('SABPAISA_CLIENT_CODE'),
                'encryptData' => $encryptData,
                'action' => $curlurl
            );*/

            $subpaisaData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 22, //la offer 1 or great deal offer
                'userid' => $offerId,
                'orderid' => $orderId,
                'orderamount' => round($grandAmount),
                'ordernote' => $products->productname
            );

            $response = SubpaisaEntry::insert($subpaisaData);
            $html = view('pg.pay', [
                'data' => $encryptData,
                'clientCode' => env('SABPAISA_CLIENT_CODE'),
                'action' => $curlurl
            ])->render();

            return response()->json(array('type' => 'SUCCESS', 'message' => 'Please wait... We are redirecting to the payment page.', 'html' => $html));
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.'));
        }
    }

    /* offer 1 response */
    public function offer5Response(Request $request)
    {
        try {
            //Log::info('request data - '. json_encode($request->all()));
            $meta = selfApplyMeta();
            $query = $request->input('encResponse');
            $authKey = env('SABPAISA_AUTH_KEY');
            $authIV = env('SABPAISA_AUTH_IV');

            $AesCipher = new Authuntication();
            $decText = $AesCipher->decrypt($authKey, $authIV, $query);

            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;

            $token = strtok($decText, "&");

            $i = 0;

            while ($token !== false) {
                $i = $i + 1;
                $token1 = strchr($token, "=");
                $token = strtok("&");
                $fstr = ltrim($token1, "=");

                if ($i == 1) {
                    $payerName = $fstr;
                }
                if ($i == 2)
                    $payerEmail = $fstr;
                if ($i == 3)
                    $payerMobile = $fstr;
                if ($i == 4)
                    $clientTxnId = $fstr;
                if ($i == 5)
                    $payerAddress = $fstr;
                if ($i == 6)
                    $amount = $fstr;
                if ($i == 7)
                    $clientCode = $fstr;
                if ($i == 8)
                    $paidAmount = $fstr;
                if ($i == 9)
                    $paymentMode = $fstr;
                if ($i == 10)
                    $bankName = $fstr;
                if ($i == 11)
                    $amountType = $fstr;
                if ($i == 12)
                    $status = $fstr;
                if ($i == 13)
                    $statusCode = $fstr;
                if ($i == 14)
                    $challanNumber = $fstr;
                if ($i == 15)
                    $sabpaisaTxnId = $fstr;
                if ($i == 16)
                    $sabpaisaMessage = $fstr;
                if ($i == 17)
                    $bankMessage = $fstr;
                if ($i == 18)
                    $bankErrorCode = $fstr;
                if ($i == 19)
                    $sabpaisaErrorCode = $fstr;
                if ($i == 20)
                    $bankTxnId = $fstr;
                if ($i == 21)
                    $transDate = $fstr;

                if ($token == true) {
                }
            }
            /* update client tax id in subpaisa_entry table */
            /* Log::info($clientTxnId);
            Log::info($paymentMode);
            Log::info($status);
            Log::info($statusCode);*/

            $paymentData = SubpaisaEntry::where('orderid', $clientTxnId)->first();
            $subpaisaData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'referenceid' => $sabpaisaTxnId,
                'txstatus' => $status,
                'paymentmode' => $paymentMode
            );
            //Log::info('subpaisa data - '. json_encode($subpaisaData));
            $response1 = SubpaisaEntry::where('id', $paymentData->id)->update($subpaisaData);
            //Log::info('subpaisa response - '. $response1);
            if ($statusCode == '0000') {
                $cardno = random_code_num(16);
                $userData = Cardoffer::where('id', $paymentData->userid)->first();
                $data = array(
                    'rec_date' => date('Y-m-d H:i:s'),
                    'card_number' => $cardno,
                    'registration_date' => date('Y-m-d'),
                    'expiry_date' => date('Y-m-d', strtotime('+3 months')),
                    'paymentid' => $sabpaisaTxnId,
                    'amount' => $paymentData->orderamount,
                    'isActive' => 1
                );
                $response = Cardoffer::where('id', $paymentData->userid)->update($data);
                if ($response) {
                    $regUser = UserRegistration::where('mobile', $userData->mobile)
                        ->where(['isActive' => 1, 'isDelete' => 0])
                        ->first();

                    if ($regUser) {
                        $converted = convertIntoCustomer($cardno, $regUser, $userData, $paymentData->orderamount, $sabpaisaTxnId, 2, 'hire-loan-agent', 'LA_', 1);
                        if (!$converted) {
                            Log::error("Conversion to customer failed for user: " . $regUser->id);
                            dd('check log');
                        }
                    } else {
                        $sent = sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);
                    }
                }
                session()->forget(['isMailSend', 'cardno']);
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => TRUE,
                ]);
            } else if ($statusCode == '0300') {
                //Log::info('else if');
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => FALSE,
                ]);
            } else {
                //Log::info('else');
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => FALSE,
                ]);
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return view('cardoffer-response', [
                'meta' => $meta,
                'response' => FALSE,
            ]);
        }
    }

    public function offer6()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.LA_OFFER_6'))->first();

        if ($products->inOffer == 1) {
            $productData = array(
                'inOffer' => $products->inOffer,
                'amount' => $products->amount,
                'offeramount' => $products->offeramount,
                'offerdate' => date('Y/m/d', strtotime('+1 days')) . ' 24:00:00',
                'payamount' => $products->offeramount + ($products->offeramount * 0.18)
            );
        } else {
            $productData = array(
                'inOffer' => 0,
                'amount' => $products->amount,
                'offeramount' => 0,
                'offerdate' => '',
                'payamount' => $products->amount + ($products->amount * 0.18)
            );
        }
        return view('loanAgent.offers.offer-6', compact('meta', 'productData'));
    }

    public function getOffer6(Request $request)
    {
        try {
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if ($profile) {
                return response()->json($profile);
            } else {
                $first_name = $inputs['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.LA_OFFER_6'))->first();
            // Log::info('products - '.json_encode($products));
            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == $mobile) {
                    $grandAmount = 1;
                    break; // Exit the loop once a match is found
                }
            }

            /* insert the adta in cardoffer */
            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $mobile], // Search condition
                [ // Values to update or insert
                    'rec_date' => now(),
                    'offerpage' => 32, //LA offer 6 or silver offer
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'emailid' => $email,
                    'amount' => round($grandAmount),
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );

            // Get the ID of the updated or inserted record
            $record = DB::table('cardoffer')->where('mobile', $mobile)->first();
            $offerId = $record->id;

            //Log::info('Offer data - '. $offerId);
            $orderId = number_format(microtime(true) * 1000, 0, '.', '');
            $encData = null;
            $returnUrl = 'https://wisemudra.com/api/loan-agent/silver-offer-response';

            /* cipherPay PG starts */
            $refId = rand(1000, 9999);
            $request = array(
                "method" => "POST",
                "url" => "payin/dynamic-qr",
                "parameter" => [
                    //'receiver_vpa' => "cpy.WIMDRA@fin",
                    'receiver_vpa' => "cpy.wisemudra@finobank",
                    'amount' => round($grandAmount), // amount
                    'remarks' => "Dynamic QR", // remarks
                    'refid' => $refId, //refrence id
                    'expiry' => "2", //in minutes
                    'type' => "QR"
                ]
            );
            Session::forget('refid');
            Session::put('refid', $refId);
            Session::save();
            Log::info("request", [$request]);
            $cipherPay = new CipherPay();
            $response = $cipherPay->hit($request);
            $response = $cipherPay->finalResponse($response);
            Log::info('final Response - ' . json_encode($response));
            /* cipherPay PG ends */

            $cipherPayData = array(
                'rec_date' => now(),
                'entryfor' => 32, // LA Offer 6 - Silver Offer
                'userid' => $offerId,
                'orderid' => $response['data']['txnid'],
                'orderamount' => round($grandAmount),
                'ordernote' => $products->productname
            );
            Log::info('Cipher insert - ' . json_encode($cipherPayData));

            $res = CipherPayEntry::insert($cipherPayData);
            $html = view('pg.cipherQR', compact('response'))->render();
            return response()->json(array('type' => 'SUCCESS', 'message' => 'Please wait... We are redirecting to the payment page.', 'html' => $html));
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.'));
        }
    }

    public function offer6Response(Request $request)
    {
        try {
            $meta = selfApplyMeta();
            $datas = Session::get('cipherResponse');
            $paymentData = CipherPayEntry::where('orderid', $datas['data']['txnid'])->first();
            //Log::info('cipher table data - '. json_encode($paymentData));

            $cipherData = array(
                'rec_date' => now(),
                'referenceid' => $datas['data']['upiRefId'],
                'txstatus' => $datas['data']['status'],
                'paymentmode' => $datas['data']['remarks'],
                'ordernote' => $paymentData->ordernote . ' (utr - ' . $datas['data']['utr'] . ')'
            );

            $response1 = CipherPayEntry::where('id', $paymentData->id)->update($cipherData);

            $userData = Cardoffer::where('id', $paymentData->userid)->first();
            $cardno = random_code_num(16);
            $data = array(
                'rec_date' => now(),
                'card_number' => $cardno,
                'registration_date' => now(),
                'expiry_date' => date('Y-m-d', strtotime('+9 months')),
                'paymentid' => $datas['data']['txnid'],
                'isActive' => 1
            );

            $response = Cardoffer::where('id', $paymentData->userid)->update($data);

            $sent = sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);

            return view('cardoffer-response', [
                'meta' => $meta,
                'response' => $datas['data']['status'] == 1 ? TRUE : FALSE,
                /*'clientTxnId' => $datas['data']['txnid'],
                'amount' => $datas['data']['amount'],
                'paymentMode' => $datas['data']['remarks'],
	            'payerName' => $datas['data']['sender_name'],
	            'payerEmail' => $payerEmail,
	            'payerMobile' => $payerMobile,*/
                // Add other variables as needed
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back();
        }
    }
}
