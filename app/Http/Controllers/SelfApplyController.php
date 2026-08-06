<?php

namespace App\Http\Controllers;

use App\Models\Cardoffer;
use App\Models\ZaakpayEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LoanApplications;
use App\Models\LyraEntry;
use App\Models\OtpVerification;
use App\Models\Product;
use App\Models\UserRegistration;
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
use App\Models\VeegahPay as VeegahEntry;
use App\Utilities\Authuntication;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SelfApplyController extends Controller
{

    public function __construct()
    {
        $this->lifetime = config('session.lifetime');
    }

    public function main(Request $request)
    {
        $meta = selfApplyMeta();
        cookieHelper($request, $this->lifetime);
        if($request->has('utm_referral')){
            session()->forget('utm_referral');
            Cookie::queue('utm_referral', $request->input('utm_referral'), $this->lifetime, '/', null, false, true, false, 'lax');
            request()->session()->put('utm_referral', $request->input('utm_referral'));
        }
        
        return view('selfApply.main', compact('meta'));
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
            /* validate the request fields */
            $request->validate([
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ], [
                'mobile.regex' => 'Enter valid mobile number'
            ]);
            /* create cookie/session for entered mobile number */
            Cookie::queue('user_mobile', $inputs['mobile'], $this->lifetime, '/', null, false, true, false, 'lax');
            /* count the otp sent in current day */
            $countSMS = countOTPs($inputs['mobile']);
            /* here, if condition check the user present and else condition check the user are not present */
            if ($user) {
                /* check what's the user status is customer or not */
                if ($user && $user->isUser == 2) {
                    return response()->json([
                        'type' => 'ERROR',
                        'message' => 'This number is already registered as customer. Kindly login to customer portal.',
                        'data' => []
                    ]);
                } else {
                    $loanApp = LoanApplications::where('userid', $user->id)->orderBy('id', 'DESC')->first();

                    Cookie::queue('applyid', $loanApp->id, $this->lifetime, '/', null, false, true, false, 'lax');
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

                    $otp = DB::table('otp_verifications')->where('mobile', $inputs['mobile'])->update(['acc_type' => $inputs['acc_type']]);
                    $sourceID = DB::table('source_entry')->insertGetId([
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
                    $redirectUrl = route(selfapplyurl($user->process_step));
                    return response()->json(['type' => 'SUCCESS', 'message' => 'User details successfully verified.', 'data' => '', 'redirectUrl' => $redirectUrl]);
                }
            } else {
                /* store user type weather its salaried or self-employed */
                Cookie::queue('loan_type', $inputs['loan_type'], $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('acc_type', $inputs['acc_type'], $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('user_type', $inputs['user_type'], $this->lifetime, '/', null, false, true, false, 'lax');
                /* if the otp's already reach the limits */
                if (!$countSMS) {
                    return response()->json([
                        'type' => 'ERROR',
                        'message' => 'You`ve reached your OTP limit. Contact customer support to proceed.',
                        'data' => []
                    ]);
                } else {
                    /* otp doesn't reach the limit */
                    $generatedOtp = generateOtp($inputs['mobile'], $inputs['acc_type']);
                    if ($generatedOtp) {
                        return response()->json(array('type' => 'SUCCESS', 'message' => 'A one-time password has been sent to your register mobile.', 'data' => $inputs['mobile']));
                    } else {
                        return response()->json(array('type' => 'ERROR', 'message' => 'Sorry, there was a problem sending your one-time password. Please try again.', 'data' => []));
                    }
                }
            }
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            Log::info('self apply send otp function error - '.$e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'The server is currently busy. Please try again later.', 'data' => []));
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
        $getOtp = OtpVerification::whereDate('rec_date', now()->format('Y-m-d'))
            ->where('mobile', Cookie::get('user_mobile'))
            ->orderBy('id', 'desc')
            ->first();
        $otp = $inputs['otp'];
        /* match the entered otp and inserted otp is same or not */
        if ($otp == $getOtp->otp) {
            /* store is verified 1 in cookie when otp getting match */
            Cookie::queue('isVerified', 1, $this->lifetime, '/', null, false, true, false, 'lax');
            $redirectUrl = route('self.apply.loan.details');
            return response()->json(['type' => 'SUCCESS', 'message' => 'Success! Your one-time password has been verified.', 'data' => '', 'redirectUrl' => $redirectUrl]);
        } else {
            return response()->json(['type' => 'ERROR', 'message' => 'The one-time password entered is incorrect. Please try again. ', 'data' => '']);
        }
    }

    /* loan details page */
    public function loanDetails()
    {
        $meta = selfApplyMeta();
        if (Cookie::get('isVerified') === null) {
            return redirect()->route('self.apply.main');
        } else {
            if (Cookie::get('process_step') === null) {
                return view('selfApply.incomeDetails', compact('meta'));
            } else {
                $returnUrl = selfapplyurl(Cookie::get('process_step'));
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
                if(Cookie::has('utm_referral') || session()->has('utm_referral')){
                    $referral = DB::table('user_registrations')->where('refcode',request()->session()->get('utm_referral'))->select('id')->first();
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

                //  fb code starts 
            	$fbid = DB::table('fb_ads_entry')->insertGetId([
			        'rec_date' => now(),
					'userid' => $userid,
					'fbclid' => Cookie::get('sourceId')
		        ]);
		        // fb ends code
                
                //Cookie::queue('loan_type', $request->input('loan_amount') > 500000 ? 1 : 1, $this->lifetime, '/', null, false, true, false, 'lax');
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
                return response()->json(['type' => 'SUCCESS', 'message' => 'Loan details saved successfully! ', 'data' => $userid]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['type' => 'ERROR', 'message' => $e->getMessage(), 'data' => '']);
            }
        } else {
            $returnUrl = selfapplyurl(Cookie::get('process_step'));
            return redirect()->route($returnUrl);
        }
    }

    /* Personal details form step 2 */
    public function personalDetails()
    {
        $meta = selfApplyMeta();
        if (Cookie::get('isVerified') === null && Cookie::get('isUser') === null) {
            return redirect()->route('self.apply.main');
        } else {
            if (Cookie::get('process_step') == 2) {
                return view('selfApply.personalDetails', compact('meta'));
            } else {
                $returnUrl = selfapplyurl(Cookie::get('process_step'));
                return redirect()->route($returnUrl);
            }
        }
    }

    /* postal details */
    public function postalDetails(Request $request)
    {
        try{
            $promise = getPostalDetailsByPincode($request->input('pincode'));
            return response()->json(['status' => 'success', 'district' => $promise['city'], 'state' => $promise['state'],]);
        } catch(\Exception $e){
            return response()->json(['status' => 'false', 'district' => '', 'state' => '','message'=>'Invalid Pincode']);
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
                return response()->json(['type' => 'SUCCESS', 'message' => 'Personal details saved successfully! ', 'data' => '']);
            } else {
                return response()->json(['type' => 'ERROR', 'message' => 'Oops! Something went wrong.', 'data' => '']);
            }
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            return response()->json(array('type' => 'ERROR', 'message' => 'The server is currently busy. Please try again later.', 'data' => []));
        }
    }

    /* get offers step 3 */
    public function getOffers()
    {
        $meta = selfApplyMeta();
        if (Cookie::get('isVerified') === null && Cookie::get('isUser') === null) {
            return redirect()->route('self.apply.main');
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

                return view('selfApply.getOffers', compact('meta', 'offersData'));
            } else {
                $returnUrl = selfapplyurl(Cookie::get('process_step'));
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
        $msg = DB::table('sms_list')->where('type',1)->where('slug','get_offer')->first()->message;
        if($msg != '#'){
            $msg = str_ireplace('{#varamount#}',$eligibilityAmt,$msg);
            $senderId = DB::table('info_pages')->where('slug','sa-senderid')->first()->content;
            sendDynamicSMS($senderId, $msg, Cookie::get('user_mobile'), 'self');    
        }
        /* send get offer message ends */

        /* interakt code here starts */
        $data2 = array(
            'phoneNumber' => Cookie::get('user_mobile'),
            'countryCode' => '+91',
            'traits' => array(
                'name' => Cookie::get('fullname')
            ),
            'tags' => array('Self Get Offer')
        );
        $restrack1 = user_track($data2);

        $data3 = array(
            'phoneNumber' => Cookie::get('user_mobile'),
            'countryCode' => '+91',
            'event' => 'Self Get Offer',
            'traits' => array(
                'SelfEligibleAmount' => $eligibilityAmt
            ),
        );
        $restrack2 = event_track($data3);
        $configs = DB::table('interakt_settings')->where('product','SA')->where('type','getoffer')->first();
        $data4 = array(
			"fullPhoneNumber" => '+91'.Cookie::get('user_mobile'),
			"callbackData"=> "some text here",
			"type"=> "Template",
			"template"=> array(
					"name"=> $configs->template_name,
					"languageCode"=> "en",
					"headerValues"=> array(
						$configs->img_url
					),
					"bodyValues"=> array(
						Cookie::get('fullname'), $eligibilityAmt
					),
				)
		);
		$restrack3 = interakt_message('self', $data4, $configs->api_key);
		
        $record = DB::table('user_offers')->where('userid', Cookie::get('userid'))->first();
        $offersData = $record ? $record->offerdata : null;
        UserRegistration::where('id', Cookie::get('userid'))->update(['update_date' => date('Y-m-d H:i:s'), 'process_step' => 4]);
        $selfApply = Product::where('productslug', 'self-apply')->first();
        $hireAgent = Product::where('productslug', 'hire-loan-agent')->first();
        return view('selfApply.buyNow', compact('meta', 'selfApply', 'hireAgent','offersData','eligibilityAmt'));
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
            $productslug = $inputs['plan'] == 2 ? 'hire-loan-agent' : 'self-apply';
            $entryfor = $inputs['plan'] == 2 ? 12 : 11;
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
            
            $orderid = "ZPLive" . number_format(microtime(true) * 1000, 0, '.', '');

            $returnUrl = $inputs['plan'] == 2 ? route('api.loan.agent.buy.digital.agent.plan') : route('api.self.apply.buy.digital.plan');
            
            if (env('ZAAKPAY_ENV') == "PRODUCTION") {
                $curlurl = "https://api.zaakpay.com/api/paymentTransact/V8";
            } else {
                $curlurl = "https://zaakstaging.zaakpay.com/api/paymentTransact/V8";
            }

            $firstname = (Cookie::get('fullname') != "") ? Cookie::get('fullname') : Cookie::get('email');
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
            Log::error('selfapply checkout - checkout method error occured: ' . $e->getMessage());
            return redirect('/error')->with('error', 'Oops! Something went wrong.');
        }
    }

    /* callback url ofd selfapply */
    public function callbackUrl()
    {
        dd('Callback function call.Go Back and make furthur process');
    }

    /* buy digital plan zaakpay function handle */
    public function buyDigitalPlan(Request $request)
    {
        $redRoute = 'self-apply/paymentFailed';
        try {
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
    				$checksumData .= htmlspecialchars($request->input($seqvalue));
    				$checksumData .= "&";
    			}
    		}

    		$checksum = hash_hmac('sha256', $checksumData, env('ZAAKPAY_SECRET_KEY'));

    		$paymentData = ZaakpayEntry::where('orderid', $orderId)->first();
    		$zaakPayData = array(
                'rec_date' => now(),
                'orderamount' => $orderAmount,
                'statuscode' => $responseCode,
                'transactionid' => $txnId,
                'paymentmode' => $paymentMode
            );

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
            if ($responseCode == 100){
                $cardno = random_code_num(16);

                $membershipData = array(
                    'rec_date' => now()->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                    'userid' => $userData->userid,
                    'registration_date' => now()->setTimezone(config('app.timezone'))->format('Y-m-d'),
                    'expiry_date' => now()->setTimezone(config('app.timezone'))->addMonth()->format('Y-m-d'),
                    'card_number' => $cardno,
                    'amount' => $orderAmount,
                    'paymentid' => $txnId,
                    'isActive' => 1,
                    'isDelete' => 0
                );

                $existingMembership = MembershipOrder::where('userid', $userData->userid)->first();
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
                    'isUser' => 2,
                    'process_step' => 5,
                    'acc_type' => 1
                );
                $response2 = UserRegistration::where('id', $userData->userid)->update($regData);

                $productslug = "self-apply";
                $invprefix = "SA_";
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
                    ->first();

                $invData3 = array(
                    'rec_date' => now()->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                    'userid' => $userData->userid,
                    'cardid' => $membershipId,
                    'inv_prefix' => $invprefix,
                    'inv_number' => $invoiceNo->option_value,
                    'inv_date' => now()->setTimezone(config('app.timezone'))->format('Y-m-d'),
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
                    } catch(\Exception $e){
                        DB::rollBack();
                        Log::error('Invoice creation failed', ['error' => $e->getMessage()]);
                    }
                }

                $mailData = array(
                    'fullname' => $userData->first_name . ' ' . $userData->last_name,
                    'mobile' => $userData->mobile,
                    'email' => $userData->email,
                    'password' => $password,
                    'order_number' => $invoiceNo->option_value,
                    'order_date' => now()->format('d-m-Y'),
                    'order_amount' => $grandtotal,
                    'transactionId' => $txnId
                );

                $sendGreetings = view('mail.welcomeGreetings', $mailData)->render();

                $invAttach = array_merge($invData3,
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
                sendBrevoHtmlMail2($mailData, 'Congratulations! Payment Successful for Wisemudra’s Self-Apply Plan.', $sendGreetings, 3, $attachments);

                // application remarks data insert
                $staffID = assignAgentSelf();
                DB::table('application_remarks')->updateOrInsert(
                    [
                        'application_id' => $userData->id,
                        'service'        => 5,
                        'subject'        => 9,
                    ],
                    [
                        'rec_date' => now(),
                        'entry_at' => now(),
                        'notes'    => '',
                        'staff_id' => $staffID->id,
                    ]
                );
                
                UserRegistration::where('id', $userData->userid)->update(['process_step' => 5, 'staff_id' => $staffID->id]);

                if ($response2 > 0) {
                    $remote_data = array(
						'company_code' => config('constant.COMPANY_CODE'),
						'company_local_ip' => '190.92.174.183',
						'product_code' => config('constant.PRODUCT_CODE_SELFAPPLY'),
						'customer_name' => $userData->first_name.' '.$userData->last_name,
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
					
                    $redRoute = 'self-apply/paymentSuccess'; // Row was updated
                } else {
                    $redRoute = 'self-apply/paymentFailed'; // No rows were updated
                }
                return redirect($redRoute);
            } else {
                return redirect("self-apply/paymentFailed");
            }
        } catch (\Exception $e) {
            Log::error('self apply buydigital checkout method error occured: ' . $e->getMessage());
            return redirect('/error')->with('error', 'Ops! Something went wrong.');
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
            
            $orderData = '';
            $data = '';
                
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
                $orderData = orderdata($orderId, 'zaakpay_entry');

                if (isset($responsecode) && $responsecode == 100) {
                    UserRegistration::where('id', $userData->userid)->update(['process_step'=>5]);
                    
                    /* application remarks entry start */
                    $staffID = assignAgentSelf();
                    DB::table('application_remarks')->updateOrInsert(
                        [
                            'application_id' => $userData->id,
                            'service'        => 5,
                            'subject'        => 9,
                        ],
                        [
                            'rec_date' => now(),
                            'entry_at' => now(),
                            'notes'    => '',
                            'staff_id' => $staffID->id,
                        ]
                    );
                    /* application remarks entry ends */
                    
                    /* fb conversion code starts here */
                    $fbleads = FbAdsEntry::where('userid', $userData->userid)->orderByDesc('id')->limit(1)->first();
                
                    $fbdata = array(
						'type' => 'self-apply',
						'firstname' => $firstname,
						'lastname' => $lastname,
						'mobile' => "91" . $userData->mobile,
						'email' => strtolower($userData->email),
						'city' => $city,
						'state' => $state,
						'zip' => $userData->pincode,
						'orderid' => $orderId,
						'odamount' => $orderData->orderamount,
						'sourceurl' => 'https://wisemudra.com/self-apply/paymentSuccess'
					);
				
    				if ($fbleads) {
    				    if($fbleads->fbclid != ""){
        					$fbclidpl = "fb.0." . round(microtime(true) * 1000) . "." . $fbleads->fbclid;
        					$fbdata['fbclid'] = $fbclidpl;
    				    } else {
				            $fbdata['fbclid'] = '';
    				    }
    				} else {
    					$fbdata['fbclid'] = '';
    				}

    				$fbresponse = fbconversioncurl($fbdata, 16);
                	$dataleads = array(
    					'rec_date' => date('Y-m-d H:i:s'),
    					'send_data' => json_encode($fbdata),
    					'received_data' => $fbresponse
    				);

    				if ($fbleads) {
    				    $fbid = DB::table('fb_ads_entry')->where('id',$fbleads->id)->update($dataleads);
                	}
                    /* fb conversion code ends here */  
                    
                    /* send payment success message starts */
                    $msg = DB::table('sms_list')->where('type',1)->where('slug','payment_successful')->first()->message;
                    if($msg != '#'){
                        $senderId = DB::table('info_pages')->where('slug','sa-senderid')->first()->content;
                        sendDynamicSMS($senderId, $msg, Cookie::get('user_mobile'), 'self');
                    }
                    /* send payment success message ends */

                    /* interakt code starts here */
                    $data2 = array(
                        'phoneNumber' => Cookie::get('user_mobile'),
                        'countryCode' => '+91',
                        'traits' => array(
                            'name' => Cookie::get('fullname')
                        ),
                        'tags' => array('Self Payment Successful')
                    );
                    $restrack1 = user_track($data2);

                    $data3 = array(
                        'phoneNumber' => Cookie::get('user_mobile'),
                        'countryCode' => '+91',
                        'event' => 'Self Payment Successful',
                        'traits' => array(
                            'userid' => Cookie::get('user_mobile'),
                            'userpass' => Session::get('user_password')
                        )
                    );
                    $restrack2 = event_track($data3);
                    /* interakt code ends here */
                }
            }
            return view('selfApply.paymentSuccess', compact('meta','data', 'orderData'));
        } catch (\Exception $e) {
            Log::info('catch');
            Log::error('An error occurred: ' . $e->getMessage());
            dd('catch');
        }
    }

    /* paymentFailed handle function */
    public function paymentFailed()
    {
        $meta = selfApplyMeta();
        $data3 = array(
            'phoneNumber' => Cookie::get('user_mobile'),
            'countryCode' => '+91',
            'event' => 'Self Payment Failed',
        );
        $restrack2 = event_track($data3);

        /* send payment failed message starts */
        $msg = DB::table('sms_list')->where('type',1)->where('slug','payment_unsuccessful')->first()->message;
        if($msg != '#'){
            $senderId = DB::table('info_pages')->where('slug','sa-senderid')->first()->content;
            sendDynamicSMS($senderId, $msg, Cookie::get('user_mobile'), 'self');
        }
        /* send payment failed message ends */

        return view('selfApply.paymentFailed',compact('meta'));
    }


    public function checkUserProcess($inputs)
    {
        $mobile = $inputs['mobile'];

        // Step 1: Check in UserRegistration
        $regUser = UserRegistration::where('mobile', $mobile)
            ->where(['isActive' => 1, 'isDelete' => 0])
            ->first();

        if ($regUser) {
            if ($regUser->isUser == 2) {
                return [
                    'type' => 'SUCCESS',
                    'message' => 'You are already a customer. Kindly login to your customer portal.',
                    'url' => route('customer.login')
                ];
            } else {
                // User is registered but not a customer yet — continue checking.
            }
        }

        // Step 2: Check in Cardoffer
        $userDetails = Cardoffer::where('mobile', $mobile)->first();

        return false; // No user found in either table.
    }


    public function PrimeOffer()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.SA_OFFER_1'))->first();
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
        return view('selfApply.offers.prime_offer', compact('meta', 'productData'));
    }

    /* get offer one in this send on payment gateway */
    public function submitPrimeOffer(Request $request)
    {
        try{
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if($profile){
                return response()->json($profile);
            } else {
                $first_name = ucfirst(trim($inputs ['first_name']));
                $last_name = ucfirst(trim($inputs['last_name']));
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
         
            $products = Product::where('productslug', config('constant.SA_OFFER_1'))->first();
         
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
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 4, // SA-Offer-1 or prime offer
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
            $offerId = $record->id;
            $orderId = number_format(microtime(true) * 1000, 0, '.', '');
            $returnUrl = 'https://wisemudra.com/self-apply/prime-offer-response';

            if (env('LYRA_MODE') == "PROD") {
                $curlurl = "https://api.in.lyra.com/pg/rest/v1/charge";
            } else {
                $curlurl = "https://api.in.lyra.com/pg/rest/v1/charge";
            }

            /* lyra post data */
            $postData = array(
                "orderId" => $orderId,
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
            
            $payurl = getlyrapaymenturl($curlurl, $postData);
            $lyraData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 6,//sa offer 1 or prime offer
                'userid' => $offerId,
                'orderid' => $orderId,
                'orderamount' => floor($grandAmount),
                'ordernote' => $products->productname,
            );
            
            $response = LyraEntry::insert($lyraData);
            if ($payurl) {
                if ($payurl->paymentLink) {
                    return response()->json(array('type'=>'SUCCESS','message'=>'Please wait... We are redirecting to the payment page.','url'=>$payurl->paymentLink));
                } else {
                    return response()->json(array('type'=>'ERROR','url'=>route('self.apply.prime.offer')));
                }
            } else {
                return response()->json(array('type'=>'ERROR','url'=>route('self.apply.prime.offer')));
            }
        } catch(ValidationException $e){
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.'));
        }
    }

    public function PrimeOfferResponse(Request $request)
    {
        try {
            $inputs = $request->all();
            
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
                        'expiry_date' => date('Y-m-d', strtotime('+3 months')),
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
                            $converted = convertIntoCustomer($cardno, $regUser, $cardOffer, $orderAmountInRupees, $txnId, 1, 'self-apply', 'SA_',4);
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
                    
                    return view('cardoffer-response', compact('meta', 'response'));
                }

            } else {
                $response = FALSE;
                return View('cardoffer-response', compact('meta', 'response'));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            $response = FALSE;
            return View('cardoffer-response', compact('meta', 'response'));
        }
    }

    public function MegaOffer()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.SA_OFFER_2'))->first();
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
        return view('selfApply.offers.mega_offer', compact('meta', 'productData'));
    }

    public function submitMegaOffer(Request $request)
    {
        try{
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if($profile){
                return response()->json($profile);
            } else {
                $first_name = $inputs ['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.SA_OFFER_2'))->first();
 
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
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' =>  5,//SA offer  or mega offer
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
            $password = trim(random_code(6));
            Session::put('orderid', $orderid);
            Session::save();
            Cache::put('user_password', $password, $this->lifetime);
  
            $returnUrl = 'https://wisemudra.com/self-apply/mega-offer-response';

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
                    "name" => $first_name.' '.$last_name,
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

            $payurl = getlyrapaymenturl($curlurl, $postData);
            $lyraData = array(
                'rec_date' => now(),
                'entryfor' => 7,// sa offer 2 or mega offer
                'userid' => $offerId,
                'orderid' => $orderid,
                'orderamount' => floor($grandAmount),
                'ordernote' => $products->productname,
            );
            $response = LyraEntry::insert($lyraData);

            if ($payurl) {
                if ($payurl->paymentLink) {
                    return response()->json(array('type'=>'SUCCESS','message'=>'Please wait... We are redirecting to the payment page.','url'=>$payurl->paymentLink));
                } else {
                    return response()->json(array('type'=>'ERROR','url'=>route('self.apply.mega.offer')));
                }
            } else {
                return response()->json(array('type'=>'ERROR','url'=>route('self.apply.mega.offer')));
            }
        } catch(ValidationException $e){
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.'));
        }
    }

    public function MegaOfferResponse(Request $request)
    {
        try {
            $inputs = $request->all();
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $meta = selfApplyMeta();

            $response = FALSE;

            if (isset($inputs["vads_order_id"])) {
                $orderId = $inputs["vads_order_id"];
                $orderAmount = $inputs["vads_amount"];
                $responseCode = $inputs["vads_charge_status"];
                $txnId = $inputs["vads_trans_uuid"];

                $paymentData = LyraEntry::where('orderid',$orderId)->first();

                $lyraData = array(
    				'rec_date' => now(),
    				'orderamount' => $orderAmount / 100,
    				'statuscode' => $responseCode,
    				'transactionid' => $txnId
    			);

    			$response1 = LyraEntry::where('id',$paymentData->id)->update($lyraData);

    			$userData = Cardoffer::where('id',$paymentData->userid)->first();

    			if ($responseCode == "PAID") {
                    $isEntry = Cardoffer::where('paymentid', $txnId)
                    ->where('isDelete', 0)
                    ->count();

                    if ($isEntry == 0) {
                        $cardno = random_code_num(16);
                        $orderAmountInRupees = $orderAmount / 100;

                        $data = [
                            'rec_date' => now(),
                            'card_number' => $cardno,
                            'registration_date' => now(),
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

                            $data = array(
                                'rec_date' => date('Y-m-d H:i:s'),
                                'card_number' => $cardno,
                                'registration_date' => date('Y-m-d'),
                                'expiry_date' => date('Y-m-d', strtotime('+3 months')),
                                'amount' => $paymentData->orderamount,
                                'paymentid' => $txnId,
                                'isActive' => 1
                            );
                            $response = Cardoffer::where('id', $paymentData->userid)->update($data);

                            if ($regUser) {
                                $cardOffer = Cardoffer::where('id', $paymentData->userid)->first();
                                $converted = convertIntoCustomer($cardno, $regUser, $userData, $paymentData->orderamount, $txnId, 1, 'self-apply', 'SA_',7);
                                if (!$converted) {
                                    Log::error("Conversion to customer failed for user: " . $regUser->id);
                                    dd('check log');
                                }
                            } else {
                                sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);
                            }
                        }
                        session()->forget(['isMailSend', 'cardno']);
                        return View('cardoffer-response', compact('meta','response'));
                    }
                    
                    return View('cardoffer-response', compact('meta','response'));
    			} else {
    				$response = FALSE;
    				return View('cardoffer-response', compact('meta','response'));
    			}
            } else {
                $response = FALSE;
				return View('cardoffer-response', compact('meta','response'));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            dd('Ops! Something went wrong.');
        }
    }

    public function PremiumOffer()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.SA_OFFER_3'))->first();
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
        return view('selfApply.offers.premium_offer', compact('meta', 'productData'));
    }
    
    public function submitPremiumOffer(Request $request)
    {
        try{
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if($profile){
                return response()->json($profile);
            } else {
                $first_name = $inputs ['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            $products = Product::where('productslug', config('constant.SA_OFFER_3'))->first();
        
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
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 6, // sa offer 3 or premium offer
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'emailid' => $email,
                    'amount' => round($grandAmount),
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );

            $record = DB::table('cardoffer')->where('mobile', $mobile)->first();
            $offerId = $record->id;

            $orderId = 'KRBZVGP'.number_format(microtime(true) * 1000, 0, '.', '');
            $encData = null;
            $returnUrl = 'https://wisemudra.com/self-apply/premium-offer-response';
            
            $terminalId = env('VEEGAH_TERMINAL_ID');
            $password = env('VEEGAH_TERMINAL_PASSWORD');
            $mkey = env('VEEGAH_MERCHANT_KEY');
         
            $signdata = $orderId."|".$terminalId."|".$password."|".$mkey."|".round($grandAmount)."|INR";
            $signature = hash('sha256', $signdata);
    		
    		$postdata = array(
                "referenceId"=> $orderId,
                "terminalId"=> $terminalId,
                "password"=> $password,
                "signature"=>  $signature, //Generated signature
                "paymentType"=> "1",
                "amount"=> round($grandAmount),
                "currency"=> "INR",
                "order"=> array(
                    "orderId"=> $orderId,  // Related orderId
                    "description"=> "Premium Offer"
                ),
                "customer"=> array(
                    "customerEmail"=> $email,
                    "billingAddressStreet"=> '',
                    "billingAddressCity"=> "",
                    "billingAddressState"=> "",
                    "billingAddressPostalCode"=> "",
                    "billingAddressCountry"=> "IN"
                ),
                "additionalDetails"=> array(
                    "userData"=> "{\"entryone\":\"abc\",\"entrytwo\":\"def\",\"entrythree\":\"xyz\",\"receiptUrl\":\"$returnUrl\"}"
                ),
            );
    		
    		$veegahData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 8,//sa offer 3 or premium offer
                'userid' => $offerId,
                'orderid' => $orderId,
                'orderamount' => round($grandAmount),
                'ordernote' => $products->productname
            );
            
            $res = VeegahEntry::insert($veegahData);
            $prodUrl = "https://test-vegaah.concertosoft.com/vegaahpayments/v2/payments/pay-request";
            if(env('VEEGAH_PROD')){
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
                    $redirect_url = $post_decode_data->paymentLink->linkUrl.$post_decode_data->transactionId;
                    return response()->json(array('type'=>'SUCCESS','message'=>'Please wait... We are redirecting to the payment page.','url'=>$redirect_url));
                } else {
                    return response()->json(array('type'=>'ERROR','url'=>route('self.apply.premium.offer')));
                }
            } else {
                return response()->json(array('type'=>'ERROR','url'=>route('self.apply.premium.offer')));
            }
        } catch(ValidationException $e){
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.'));
        }
    }
    
    public function PremiumOfferResponse(Request $request){
        try{
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
			    return view('cardoffer-response', [ 'meta' => $meta, 'response' => FALSE]);
			}

			$resultdata = json_decode($decryptedData, true);
			if ($resultdata === null) {
				return view('cardoffer-response', [ 'meta' => $meta, 'response' => FALSE]);
			}
            
            $paymentData = VeegahEntry::where('orderid', $resultdata['orderDetails']['orderId'])->first();
            
            $veegahData = array(
                'rec_date' => now(),
                'referenceid' => $resultdata['transactionId'],
				'txstatus' => $resultdata['result'],
				'paymentmode' => $resultdata['paymentInstrument']['paymentMethod']
            );
            $response1 = VeegahEntry::where('id', $paymentData->id)->update($veegahData);
            $userData = Cardoffer::where('id',$paymentData->userid)->first();
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
                            $converted = convertIntoCustomer($cardno, $regUser, $userData, $paymentData->orderamount, $resultdata['transactionId'], 1, 'self-apply', 'SA_',8);
                            if (!$converted) {
                                Log::error("Conversion to customer failed for user: " . $regUser->id);
                                dd('check log');
                            }
                        } else {
                            $sent = sendPaymentGreetings($userData->first_name.' '.$userData->last_name, $userData->mobile, $userData->emailid);
                        }
                    }
                    session()->forget(['isMailSend', 'cardno']);
                    return view('cardoffer-response', [
                        'meta' => $meta,
                        'response' => TRUE,
                    ]);
                } else {
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
        } catch(\Exception $e){
            Log::info('An error occured in offer3 response - ' . $e->getMessage());
            dd('Ops! Something went wrong.');
        }
    }

    public function StarOffer()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.SA_OFFER_4'))->first();
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
        return view('selfApply.offers.star_offer',compact('meta', 'productData'));
    }

    public function submitStarOffer(Request $request)
    {
        try{
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if($profile){
                return response()->json($profile);
            } else {
                $first_name = $inputs ['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.SA_OFFER_4'))->first();
   
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
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 7,//sa offer 4 or star offer
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
            $password = trim(random_code(6));
            Session::put('orderid', $orderid);
            Session::save();
            Cache::put('user_password', $password, $this->lifetime);
   
            $returnUrl = 'https://wisemudra.com/self-apply/star-offer-response';

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
                    "name" => $first_name.' '.$last_name,
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
  
            $payurl = getlyrapaymenturl($curlurl, $postData);
            $lyraData = array(
                'rec_date' => now(),
                'entryfor' => 9,//la offer 1 or great deal offer
                'userid' => $offerId,
                'orderid' => $orderid,
                'orderamount' => floor($grandAmount),
                'ordernote' => $products->productname,
            );

            $response = LyraEntry::insert($lyraData);

            if ($payurl) {
                if ($payurl->paymentLink) {
                    return response()->json(array('type'=>'SUCCESS','message'=>'Please wait... We are redirecting to the payment page.','url'=>$payurl->paymentLink));
                } else {
                    return response()->json(array('type'=>'ERROR','url'=>route('self.apply.star.offer')));
                }
            } else {
                return response()->json(array('type'=>'ERROR','url'=>route('self.apply.star.offer')));
            }
        } catch(ValidationException $e){
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.'));
        }
    }

    public function StarOfferResponse(Request $request)
    {
        try {
            $inputs = $request->all();
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $meta = selfApplyMeta();

            $response = FALSE;

            if (isset($inputs["vads_order_id"])) {
                $orderId = $inputs["vads_order_id"];
                $orderAmount = $inputs["vads_amount"];
                $responseCode = $inputs["vads_charge_status"];
                $txnId = $inputs["vads_trans_uuid"];

                $paymentData = LyraEntry::where('orderid',$orderId)->first();

                $lyraData = array(
    				'rec_date' => now(),
    				'orderamount' => $orderAmount / 100,
    				'statuscode' => $responseCode,
    				'transactionid' => $txnId
    			);

    			$response1 = LyraEntry::where('id',$paymentData->id)->update($lyraData);

    			$userData = Cardoffer::where('id',$paymentData->userid)->first();

    			if ($responseCode == "PAID") {
                    $isEntry = Cardoffer::where('paymentid', $txnId)
                    ->where('isDelete', 0)
                    ->count();

                    if ($isEntry == 0) {
                        $cardno = random_code_num(16);
                        $orderAmountInRupees = $orderAmount / 100;

                        $data = [
                            'rec_date' => now(),
                            'card_number' => $cardno,
                            'registration_date' => now(),
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

                            $data = array(
                                'rec_date' => date('Y-m-d H:i:s'),
                                'card_number' => $cardno,
                                'registration_date' => date('Y-m-d'),
                                'expiry_date' => date('Y-m-d', strtotime('+3 months')),
                                'amount' => $paymentData->orderamount,
                                'paymentid' => $txnId,
                                'isActive' => 1
                            );
                            $response = Cardoffer::where('id', $paymentData->userid)->update($data);

                            if ($regUser) {
                                $cardOffer = Cardoffer::where('id', $paymentData->userid)->first();
                                $converted = convertIntoCustomer($cardno, $regUser, $userData, $paymentData->orderamount, $txnId, 1, 'self-apply', 'SA_',7);
                                if (!$converted) {
                                    Log::error("Conversion to customer failed for user: " . $regUser->id);
                                    dd('check log');
                                }
                            } else {
                                sendPaymentGreetings($userData->first_name . ' ' . $userData->last_name, $userData->mobile, $userData->emailid);
                            }
                        }
                        session()->forget(['isMailSend', 'cardno']);
                        return View('cardoffer-response', compact('meta','response'));
                    }
                    
                    return View('cardoffer-response', compact('meta','response'));
    			} else {
    				//$sent = $this->Site_Onlineprocess_Model->sendPaymentFailedGreetings($userdata->mobile, $userdata->emailid);
    				$response = FALSE;
    				return View('cardoffer-response', compact('meta','response'));
    			}
            } else {
                $response = FALSE;
				return View('cardoffer-response', compact('meta','response'));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            dd('Ops! Something went wrong.');
        }
    }

    public function GreatOffer()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', env('SA_OFFER_5'))->first();

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
        return view('selfApply.offers.great_offer', compact('meta', 'productData'));
    }

    public function submitGreatOffer(Request $request)
    {
        try{
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* first check in user registration */
            $profile = $this->checkUserProcess($inputs);
            if($profile){
                return response()->json($profile);
            } else {
                $first_name = $inputs ['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', env('SA_OFFER_5'))->first();

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
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 9,//great offer or SA offer 5
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

            $orderId = number_format(microtime(true) * 1000, 0, '.', '');
            $encData = null;
            $returnUrl = 'https://wisemudra.com/self-apply/great-offer-response';

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

            $subpaisaData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 21, //sa offer 5 or great offer
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
            
            return response()->json(array('type'=>'SUCCESS','message'=>'Please wait... We are redirecting to the payment page.','html'=>$html));
        } catch(ValidationException $e){
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.'));
        }
    }

    public function GreatOfferResponse(Request $request)
    {
        try {
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
  
            $paymentData = SubpaisaEntry::where('orderid', $clientTxnId)->first();
            $subpaisaData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'referenceid' => $sabpaisaTxnId,
                'txstatus' => $status,
                'paymentmode' => $paymentMode
            );
            $response1 = SubpaisaEntry::where('id', $paymentData->id)->update($subpaisaData);
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
                        $converted = convertIntoCustomer($cardno, $regUser, $userData, $paymentData->orderamount, $sabpaisaTxnId, 1, 'self-apply', 'SA_',5);
                        if (!$converted) {
                            Log::error("Conversion to customer failed for user: " . $regUser->id);
                            dd('check log');
                        }
                    } else {
                        $sent = sendPaymentGreetings($userData->first_name.' '.$userData->last_name, $userData->mobile, $userData->emailid);
                    }
                }
                session()->forget(['isMailSend', 'cardno']);
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => TRUE,
                ]);
            } else if($statusCode == '0300') {
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => FALSE,
                ]);
            } else {
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

    public function StandardOffer(){
        $meta = selfApplyMeta();
        $products = Product::where('productslug', config('constant.SA_OFFER_6'))->first();

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
        return view('selfApply.offers.standard_offer', compact('meta', 'productData'));
    }

    public function submitStandardOffer(Request $request){
        try{
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
                $first_name = $inputs ['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', config('constant.SA_OFFER_6'))->first();
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
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 31,//SA offer 6 or standard offer
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

            $orderid = 'ZPLive_'.number_format(microtime(true) * 1000, 0, '.', '');
            $password = trim(random_code(6));
            Session::put('orderid', $orderid);
            Session::save();
            Cache::put('user_password', $password, $this->lifetime);

            $returnUrl = 'https://wisemudra.com/self-apply/standard-offer-response';
            if (env('ZAAKPAY_ENV') == "PRODUCTION") {
                $curlurl = "https://api.zaakpay.com/api/paymentTransact/V8";
            } else {
                $curlurl = "https://zaakstaging.zaakpay.com/api/paymentTransact/V8";
            }

            $firstname = ($first_name != "") ? $first_name : $email;
            $zaakpayPostData = array(
                "merchantIdentifier" => env('ZAAKPAY_MERCHANT_IDENTIFIER'),
                "orderId" => $orderid,
                "returnUrl" => $returnUrl,
                "currency" => 'INR',
                "amount" => $grandAmount * 100,
                "buyerEmail" => $email,
                "buyerFirstName" => $firstname,
                "buyerPhoneNumber" => $mobile,
                "buyerCountry" => 'India',
                "productDescription" => $products->productname,
            );

            ksort($zaakpayPostData);
            $checksumData = "";

            foreach ($zaakpayPostData as $key => $value) {
                $checksumData .= $key . '=' . $value . '&';
            }

            $checksum = hash_hmac('sha256', $checksumData, env('ZAAKPAY_SECRET_KEY'));

            $zaakPayData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 31, // SA Offer 6 - Standard Offer
                'userid' => $offerId,
                'orderid' => $orderid,
                'orderamount' => $grandAmount,
                'ordernote' => $products->productname,
            );

            $response = ZaakpayEntry::create($zaakPayData);
            $html = view('pg.zaakpay-checkout-offer',compact('zaakpayPostData','checksum','curlurl'))->render();

            return response()->json(['type'=>'SUCCESS','message'=>'Please wait... We are redirecting to the payment page.','html'=>$html]);
        } catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['type'=>'ERROR','errors'=>$e->errors()], 422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.'));
        }
    }

    public function StandardOfferResponse(Request $request){
        try{
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
                    $checksumData .= $seqvalue == 'responseDescription' ? $request->input($seqvalue).' ' : $request->input($seqvalue);
                    $checksumData .= "&";
                }
            }
            $checksum = hash_hmac('sha256', $checksumData, env('ZAAKPAY_SECRET_KEY'));
            if ($checksum == $recd_checksum) {
                $paymentData = ZaakpayEntry::where('orderid', $orderId)->first();

                $zaakPayData = array(
                    'rec_date' => date('Y-m-d H:i:s'),
                    'orderamount' => $orderAmount,
                    'statuscode' => $responseCode,
                    'transactionid' => $txnId,
                    'paymentmode' => $paymentMode
                );
                $response1 = ZaakpayEntry::where('id', $paymentData->id)->update($zaakPayData);
                if ($responseCode == 100) {
                    $cardno = random_code_num(16);
                    $userData = Cardoffer::where('id', $paymentData->userid)->first();
                    $data = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'card_number' => $cardno,
                        'registration_date' => date('Y-m-d'),
                        'expiry_date' => date('Y-m-d', strtotime('+3 months')),
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
                            $converted = convertIntoCustomer($cardno, $regUser, $userData, $orderAmount, $txnId, 1, 'self-apply', 'SA_',31);
                            if (!$converted) {
                                Log::error("Conversion to customer failed for user: " . $regUser->id);
                                dd('check log');
                            }
                        } else {
                            $sent = sendPaymentGreetings($userData->first_name.' '.$userData->last_name, $userData->mobile, $userData->emailid);
                        }
                    }
                    session()->forget(['isMailSend', 'cardno']);
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
            } else {
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => FALSE,
                ]);
            }
        } catch(\Exception $e){
            dd('Oops! Something went wrong.');
        }
    }
}
