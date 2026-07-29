<?php

namespace App\Http\Controllers;

use App\Models\Cardoffer;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\LoanApplications;
use App\Models\LyraEntry;
use App\Models\OtpVerification;
use App\Models\PhonrPeEntry;
use App\Models\Product;
use App\Models\Razorpayentry;
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
use App\Models\CipherPay as CipherPayEntry;
use App\Utilities\Authuntication;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Http\Controllers\CipherPayController as CipherPay;

class SelfApplyController extends Controller
{
    public $mainurl;
    public $key;
    public $partnerid;
    public $headerJson;
    public $publicKey;
    public $privateKey;
    public $aesKey;
    public $aesIv;
    public $publicKeyHeader;
    public $partnerToken;
    public $lifetime;
            
    public function __construct()
    {
        $this->mainurl = "https://api.cipherpay.in/api/v3/";
        $this->key = "";         // token
        $this->partnerid = "20221427";         // 2022XXXX
        $this->headerJson = '{"partnerId":"CP00321","headerToken":"to1Wy9MTMq-lfZxIIRvN9-FlEBW-rFhMd-LbMs7e2U2Q"}';     //header json
        $this->publicKey = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1/RwNQQ05yd2d0hisFHO
771Lc+iOIsPJysGsTA9MC+sV+I1mNwA69WIYmPRz11zhDUPVojVjopkbMF1pe57p
kpzr149INVgmevbXwpynQ/kUbwMj+M70Jm/FASmvtDRpOWaypocv9O8y35/bNdKT
1rSGqe15rcCg+ZCpQcQcpaNWGRTEfLyCM4LWH0NnGDaOfZjHsIXN4Pb6QKZiLmuP
f7FQoTOQ8irb6GSmoGQHhsspO2VXXa4ivsYXL7ZihMvnj+k/iRBA9Nmy+eD+fbBj
dl/QVDpKFh96U++OzUH+qf6anpjnkDKugVwjoqoitfjVC5+S/xEE2HUMAjfYxAxp
ewIDAQAB
-----END PUBLIC KEY-----";     //body key
        $this->privateKey = "-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDtSj4gDw0y0zK1
yUQPTS6e9AF+m0dN/mEsY7I+vLx5EsocC7b0ZDESBto7QZQTFLgkRG/umI03Jvjv
7VjTMBPdnZZs1VEyzY/iz9dEu95RoO/TmgErhk/RirBUifv7fqzEblgNHdLeKO9i
zlqODCH07tlgrL8+dln4/aaINNsV8ci2PpyTQvmzeGVLN87VCL93Cf1fKUw4Jxfm
grXwjm7rjsaQXwgLwCyBZMw3pKOxcAMbe3FXX73EM95kye5THr6aoabYtEtThEkd
hIdiOWfcaKIqwEWdqQdrKvcuQgQ4x8sYQHEdAlZTav0hIzCNOoYDHOXPPA1uGYWI
U3sQ8cRFAgMBAAECggEBAMBbagdFDFcCPF5/PKwGzl9OwJNovyHrr8xzOUCAcWzY
nXykxnhRbDIH2gslOytIpYnI7NAHXJqz/iNJTbNNix0hLZsmRf+gAh9Ei2aNwUh8
5U2sz3wAEl50RkMR5HJYmydVqA1h+tnbZ0u6qk/yZ+iNYyxqfVHeUUt2G/TYnC2p
VkRmlIJzz6jKkEftIpMIs54gCITsrMEAFhbEEti1bxa8xcchFUFXOC2qseCsa3wh
H/vWKcwvxlJ/iIdIucGMiG6YvqBAl8cGwtUP8NBVcc//H6T94nphY2LWFjnXP/vx
oDH4HO0GjypJuiAo3U0OWtfceKDVuI5WMxrnSafSrlUCgYEA/691/WDNRQo9rwPA
zurSA+WqnnbmTddDQCebJSaKodt0Fm32TGZlemmXw2TwX/2Y0BO9hQ9ErL7Iq3kJ
Zw/oic+1Xbe2X/Xy+Y8ejx7+NCr7wnsusQs1u0q8iPbPkwm/DNHeeYaFsC2CsHYA
LaXxqAAmQePO9swql73BPbvCYlsCgYEA7ZT8w8tm5cFa7xatzLdMd2i0c0zKxmC2
ZdCAXxTAKag4RaIdCjd2fe6FLR7AIweK1TxNjJdky0jSlmrHpm7aUJc+AQIeJrHH
yn/7ZrqKIqlvsIjrrVQ34AAGEqmpOKaGHmxaIymCz6ba1FLOaIFBoU9iteCSpxSp
Wu2f/n4H9d8CgYB4v34zSNHn9uwmiNk3XsILwRyQdYpR3IUP+SRVqRvzUFZEeW3F
qt3lr5RSXIsAah0OtyPbgNhn1DmkcSa1m1ewLX1zYt0n+Fjk7mf6IyLdtCbtM4tb
ZPXdG3BqJlTlFcc54Kr2LUdZakS73R48cI/tTRTELTeCaez7eEuYQeGM3QKBgQDs
+CYFxOOlVu60exJvloSWtcEHIBlBNUW41/ay36er5TX7fm8ouZlekoITNxC939AC
UFupV3gZq1Eg5vCsePUYXUJjDxGz1q9Is8618GnHmOjmVCt+fvocWumzw7Yd4zrz
LW/akpT/OZYbIVzvr70egGgcsRqVvG4PW+SxmlOmUQKBgQC4X2Fh8JQMyzQmyjuH
/kR/MHFlyQjGRfh9dp62juMf8+AWqOYpwflBSBfQsEWtfb5ZzufXbv/Kbe34VJGj
WusJ8o48S/JKkpXHRzpgGpPWvToSHClarEfLIU9tKSAOTxU2EjbvUvmnRMqcAVkB
yLLiOCucoXMl+zpvVxXuMuybog==
-----END PRIVATE KEY-----";
        $this->aesKey = '';
        $this->aesIv = '';
        $this->publicKeyHeader = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArmPnNPRUIMjUXqVT0i6V
ebaesn17MIhMoMyFXu0gIwSd5/LM7p0Gt1faWlXvl/nUnvdajCWScrgxyIGUVIwM
gYQnxBFhCA+i3WcI3CjVAZNQv0VVbNsGqjFRqLhkaxTRKWZeZbQM6GOWeJ0o3S9Q
oP+8R2xQX5iCeDk/VIq1L9gw/DIJV+V4RSspEbujOEAnUXtAvLZXPJQzTonECzuJ
OQJOqmtThgaH9cablNiIzlFCe6ir5T0tgOSt1VPjQaiBAfaIdYnrF5KccPE5S0SW
C74RXau1WOWg4gs68fAXquL+79mMX+LUSI/YwT/wh068lh851sgz51Ci1KLtk+E+
dwIDAQAB
-----END PUBLIC KEY-----';       //header key

        $this->partnerToken = 'Q1AwMDMyMTokMnkkMTIkWDd2UXZUNFJhcUZMZE1qM1V5d2lHTzVSa1ZWSm1Rc2NOS0hGalNvZDYwT0dzS3Y2ZG5IVUs='; //partner Token
        $this->lifetime = config('session.lifetime');
    }

    /* landing page function */
    public function main(Request $request)
    {
        $meta = selfApplyMeta();
        cookieHelper($request, $this->lifetime);
        return view('selfApply.main', compact('meta'));
    }

    /* send and resend otp */
    public function sendOtp(Request $request)
    {
        try {
            /* store all request in $inputs variable */
            $inputs = $request->all();

            if(Cookie::has('user_mobile') && Cookie::get('user_mobile') != $inputs['mobile']){
                $keysToKeep = ['XSRF-TOKEN', 'wisemudra_session', 'utm_campaign', 'utm_medium', 'utm_source'];
                foreach (Cookie::get() as $key => $value) {
                    if (!in_array($key, $keysToKeep)) {
                        Cookie::queue(Cookie::forget($key));
                    }
                }
            }
            /* validate the request fields */
            $request->validate([
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/'],
                'accept_tnc' => 'required',
                'allow_sms' => 'required'
            ], [
                'mobile.regex' => 'Enter valid mobile number',
                'accept_tnc.required' => 'Click checkbox to accept our terms conditions and privacy policy',
                'allow_sms.required' => 'Click checkbox to allow the sms and emails our best offers.'
            ]);
            /* create cookie/session for entered mobile number */
            Cookie::queue('user_mobile', $inputs['mobile'], $this->lifetime, '/', null, false, true, false, 'lax');
            /* count the otp sent in current day */
            $countSMS = countOTPs($inputs['mobile']);
            /* check the entered mobile number is present or not */
            $user = singleUserDetails(['mobile' => $inputs['mobile']]);
            //Log::info('userDetails - ' . json_encode($user));
            /* here, if condition check the user present and else condition check the user are not present */
            if ($user) {
                /* check what's the user status is customer or not */
                if ($user && $user->isUser === 2) {
                    return response()->json([
                        'type' => 'ERROR',
                        'message' => 'Entered mobile number is already register.Kindly log in to your customer portal.',
                        'data' => []
                    ]);
                } else {
                    $loanApp = LoanApplications::where('userid', $user->id)->orderBy('id', 'DESC')->first();
                    Cookie::queue('applyid', $loanApp->id, $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('isUser', 1, $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('isVerified', 1, $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('loan_amount', $loanApp->loan_amount, $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('loan_type',$loanApp->loan_type,$this->lifetime,'/',null,false,true,false,'lax');
                    Cookie::queue('process_step', $user->process_step, $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('user_type', $loanApp->user_type, $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('acc_type', $user->acc_type, $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('userid', $user->id, $this->lifetime, '/', null, false, true, false, 'lax');
                    $sourceID = DB::table('source_entry')->insertGetId([
                        'user_id' => $user->id,
                        'utm_source' => Cookie::get('utm_source'),
                        'utm_campaign' => Cookie::get('utm_campaign'),
                        'utm_medium' => Cookie::get('medium'),
                        'utm_referral' => Cookie::get('utm_referral'),
                        'source_id' => Cookie::get('sourceId'),
                        'client_ip' => $request->ip()
                    ]);
                    if($user->process_step >= 3){
                        Cookie::queue('fullname', $user->first_name.' '.$user->last_name , $this->lifetime, '/', null, false, true, false, 'lax');
                        Cookie::queue('email', $user->email, $this->lifetime, '/', null, false, true, false, 'lax');
                    }
                    $redirectUrl = route(selfapplyurl($user->process_step));
                    return response()->json(['type' => 'SUCCESS', 'message' => 'User Verified', 'data' => '', 'redirectUrl' => $redirectUrl]);
                }
            } else {
                /* store user type weather its salaried or self-employed */
                Cookie::queue('acc_type', $inputs['acc_type'], $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('user_type', $inputs['user_type'], $this->lifetime, '/', null, false, true, false, 'lax');
                /* if the otp's already reach the limits */
                if (!$countSMS) {
                    return response()->json([
                        'type' => 'ERROR',
                        'message' => 'You have reached your OTP limit. Please contact customer support.',
                        'data' => []
                    ]);
                } else {
                    /* otp doesn't reach the limit */
                    $generatedOtp = generateOtp($inputs['mobile'],$inputs['acc_type']);
                    if ($generatedOtp) {
                        return response()->json(array('type' => 'SUCCESS', 'message' => 'OTP sent to mobile.', 'data' => $inputs['mobile']));
                    } else {
                        return response()->json(array('type' => 'ERROR', 'message' => 'Something went wrong while sending OTP.Try after sometime', 'data' => []));
                    }
                }
            }
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Currently server is busy.Please try after some time.', 'data' => []));
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
        $getOtp = OtpVerification::whereDate('rec_date', date('Y-m-d'))
            ->where('mobile', Cookie::get('user_mobile'))
            ->orderBy('id', 'desc')
            ->first();
        $otp = $inputs['otp'];
        /* match the entered otp and inserted otp is same or not */
        if ($otp == $getOtp->otp) {
            /* store is verified 1 in cookie when otp getting match */
            Cookie::queue('isVerified', 1, $this->lifetime, '/', null, false, true, false, 'lax');
            $redirectUrl = route('self.apply.loan.details');
            return response()->json(['type' => 'SUCCESS', 'message' => 'OTP validate', 'data' => '', 'redirectUrl' => $redirectUrl]);
            /*if(!Cookie::has('user_mobile') || !Cookie::has('isVerified') || !Cookie::has('user_type') || !Cookie::has('process_step') || !Cookie::has('loan_amount')){
                //store is verified 1 in cookie when otp getting match
                Cookie::queue('isVerified', 1, $this->lifetime, '/', null, false, true, false, 'lax');
                //dd('here');
                $redirectUrl = route('self.apply.loan.details');
                return response()->json(['type' => 'SUCCESS', 'message' => 'OTP validate', 'data' => '', 'redirectUrl' => $redirectUrl]);
            } else {
                // check the entered mobile number is present or not
                $user = singleUserDetails(['mobile' => Cookie::get('user_mobile')]);
                $loanApp = LoanApplications::where('userid', $user->id)->orderBy('id', 'DESC')->first();
                //Log::info('loanApplicationData - '. json_encode($loanApp));
                //here we got the user is in the process his/her isUser status is 1. here, we first update the record date
                UserRegistration::where('id',$user->id)->update(['update_date' => date('Y-m-d H:i:s')]);
                if($user->process_step >= 3){
                    Cookie::queue('fullname', $user->first_name.' '.$user->last_name , $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('email', $user->email, $this->lifetime, '/', null, false, true, false, 'lax');
                }
                Cookie::queue('applyid', $loanApp->id, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('isUser', 1, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('isVerified', 1, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('loan_amount', $loanApp->loan_amount, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('loan_type',$loanApp->loan_type,$this->lifetime,'/',null,false,true,false,'lax');
                Cookie::queue('process_step', $user->process_step, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('user_type', $loanApp->user_type, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('userid', $user->id, $this->lifetime, '/', null, false, true, false, 'lax');
                $redirectUrl = route(selfapplyurl($user->process_step));
                dd($redirectUrl);
                return response()->json(['type' => 'SUCCESS', 'message' => 'User Verified', 'data' => '', 'redirectUrl' => $redirectUrl]);
            }*/
        } else {
            return response()->json(['type' => 'ERROR', 'message' => 'Invalid OTP', 'data' => '']);
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
            'monthly_income' => 'required'
        ]);
        //Log::info('process step - ' . Cookie::get('process_step'));
        if (Cookie::get('process_step') === null) {
            //Log::info('if');
            /* loan_type, mobile_number, loan_amount, monthly_income, process_step = 2  */
            DB::beginTransaction();
            try {
                $userid = DB::table('user_registrations')->insertGetId([
                    'rec_date' => date('Y-m-d H:i:s'),
                    'update_date' => date('Y-m-d H:i:s'),
                    'mobile' => Cookie::get('user_mobile'),
                    'process_step' => 2,
                    'acc_type' => Cookie::get('acc_type')
                ]);

                $sourceID = DB::table('source_entry')->insertGetId([
                    'user_id' => $userid,
                    'utm_source' => Cookie::get('utm_source'),
                    'utm_campaign' => Cookie::get('utm_campaign'),
                    'utm_medium' => Cookie::get('utm_medium'),
                    'utm_referral' => Cookie::get('utm_referral'),
                    'source_id' => Cookie::get('sourceId'),
                    'client_ip' => $request->ip()
                ]);

                Cookie::queue('loan_type',$request->input('loan_amount') > 500000 ? 2 : 1,$this->lifetime,'/',null,false,true,false,'lax');
                // Insert record into the loan_applications table using the userID from the user_registrations table
                $applyid = DB::table('loan_applications')->insertGetId([
                    'rec_date' => date('Y-m-d H:i:s'),
                    'userid' => $userid,
                    'loan_amount' => $request->input('loan_amount'),
                    'user_type' => Cookie::get('user_type'),
                    'loan_type' => $request->input('loan_amount') > 500000 ? 2 : 1,
                    'monthly_income' => $request->input('monthly_income'),
                    'application_number' => random_code(8)
                ]);
                DB::commit();
                Cookie::queue('userid', $userid, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('isUser', 1, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('applyid', $applyid, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('loan_amount', $request->input('loan_amount'), $this->lifetime, '/', null, false, true, false, 'lax');
                /*$currentCookie = json_decode(Cookie::get('process_step'), true);
                $currentCookie['process_step'] = 2;*/
                Cookie::queue('process_step', 2, $this->lifetime, '/', null, false, true, false, 'lax');
                return response()->json(['type' => 'SUCCESS', 'message' => 'Loan details added successfully', 'data' => $userid]);
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
        //Log::info(json_encode(request()->cookie));
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
        // Call the helper function
        $promise = getPostalDetailsByPincode($request->input('pincode'));
        // Wait for the async response
        $result = $promise->wait();
        if (isset($result[0]['PostOffice'][0])) {
            // Get the first PostOffice record
            $postOffice = $result[0]['PostOffice'][0];

            // Extract the district and state
            $district = $postOffice['District'];
            $state = $postOffice['State'];

            // Return or use these values as needed
            return response()->json(['status' => 'success', 'district' => $district, 'state' => $state,]);
        }
        return response()->json(['status' => 'false', 'district' => '', 'state' => '',]);
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
                'email' => 'required|email|unique:user_registrations,email',
                'pancard' => ['required', 'regex:/^[A-Z]{5}\d{4}[A-Z]$/'],
                'pincode' => 'required|digits:6',
                'state' => 'required'
            ], [
                'pancard.regex' => 'Please insert valid PAN Card number'
            ]);
            /* create new array which is pass in create function for create the record */
            $newInputs = [
                'first_name' => ucfirst(trim($request->input('firstname'))),
                'last_name' => ucfirst(trim($request->input('lastname'))),
                'email' => strtolower(trim($request->input('email'))),
                'dob' => $request->input('dob'),
                'pancard' => $request->input('pancard'),
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
                Cookie::queue('fullname', ucfirst(trim($request->input('firstname'))) . ' ' . ucfirst(trim($request->input('lastname'))),$this->lifetime,'/', null, false, true, false, 'lax');
                return response()->json(['type' => 'SUCCESS', 'message' => 'Personal details added successfully', 'data' => '']);
            } else {
                return response()->json(['type' => 'ERROR', 'message' => 'Something went wrong', 'data' => '']);
            }
        } catch (ValidationException $e) {
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch (\Exception $e) {
            //Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Currently server is busy.Please try after some time.', 'data' => []));
        }
    }

    /* get offers step 3 */
    public function getOffers(){
        $meta = selfApplyMeta();
        if(Cookie::get('isVerified') === null && Cookie::get('isUser') === null){
            return redirect()->route('self.apply.main');
        } else {
            if(Cookie::get('process_step') == 3){
                return view('selfApply.getOffers',compact('meta'));
            } else {
                $returnUrl = selfapplyurl(Cookie::get('process_step'));
                return redirect()->route($returnUrl);
            }
        }
    }

    /* buy now */
    public function buyNow(){
        $meta = selfApplyMeta();
        return view('selfApply.buyNow', compact('meta'));
    }

    /* checkout the data */
    public function checkout(Request $request){
        try{
            $inputs = $request->all();
            $loanAppUpdates = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'status' => 1,
                'isDelete' => 0
            );
            $res1 = LoanApplications::where('id', Cookie::get('applyid'))->update($loanAppUpdates);
            //Log::info('response one - '. $res1);
            $productslug = $inputs['plan'] == 2 ? 'hire-loan-agent' : 'self-apply';
            $productData = Product::where('productslug',$productslug)->first();
            $amount = ($productData->inOffer == 1) ? $productData->offeramount : $productData->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == Cookie::get('user_mobile')) {
                    $grandAmount = 1;
                    break; // Exit the loop once a match is found
                }
            }
            //Log::info('grandamount - '. $grandAmount);
            $orderid = number_format(microtime(true) * 1000, 0, '.', '');
            $password = trim(random_code(6));
            Session::put('orderid', $orderid);
            Session::save();
            Cache::put('user_password', $password, $this->lifetime);
            //Log::info('order ID - ' .$orderid);
            $returnUrl = $inputs['plan'] == 2 ? route('api.loan.agent.buy.digital.agent.plan') : route('api.self.apply.buy.digital.plan');
            $callbackUrl = $inputs['plan'] == 2 ? route('loan.agent.callbackUrl') : route('self.apply.callbackUrl');

            if (env('PHONEPE_ENV') == "PRODUCTION") {
                $curlurl = 'https://api.phonepe.com/apis/hermes/pg/v1/pay';
            } else {
                $curlurl = 'https://api-preprod.phonepe.com/apis/hermes/pg/v1/pay';
            }
            //Log::info($curlurl);
            $phonePeData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 11,
                'userid' => Cookie::get('userid'),
                'orderid' => $orderid,
                'orderamount' => $grandAmount,
                'ordernote' => $productData->productname
            );
            //Log::info('PhonePe Insert data - '. json_encode($phonePeData));
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
            //Log::info('Data Response - '. json_encode($dataRes));
            $payUrl = getPhonePePaymentUrl($curlurl, env('PHONEPE_SALT_KEY'), env('PHONEPE_SALT_INDEX'), $dataRes);
            //Log::info(json_encode($payUrl));
            if ($payUrl) {
                if ($payUrl->data->instrumentResponse->redirectInfo->url) {
                    //Log::info('if payment page');
                    header("location:" . $payUrl->data->instrumentResponse->redirectInfo->url);
                    die;
                } else {
                    //Log::info('else');
                    return redirect("self.apply.main");
                }
            } else {
                //Log::info('super else');
                return redirect("self.apply.main");
            }
        } catch(\Exception $e){
            Log::error('checkout method error occured: ' . $e->getMessage());
            return redirect('/error')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /* callback url ofd selfapply */
    public function callbackUrl(){
        dd('Callback function call.Go Back and make furthur process');
    }

    /* buyDigitalPlan function handle */
    public function buyDigitalPlan(Request $request){
        try{
            //Log::info('request data - '.json_encode($request->all()));
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $meta = selfApplyMeta();
            if (!$request->has(['code', 'transactionId','providerReferenceId'])) {
                //Log::info('in if self');
                return redirect("self-apply");
            }
            $paymentData = PhonrPeEntry::where('orderid',$request->input('transactionId'))->first();

            $txStatus = $request->input('code');
            $transactionId = $request->input('transactionId');
            $referenceId = $request->input('providerReferenceId');

            $phonepedata = array(
                'rec_date' => date('Y-m-d H:i:s'),
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
            //Log::info('txnStatus - '. $txStatus);
            if ($txStatus == "PAYMENT_SUCCESS") {
                $isEntry = MembershipOrder::where('paymentid', $referenceId)
                    ->where('isDelete', 0)
                    ->count();
                //Log::info('isEntry - '. $isEntry);
                if ($isEntry == 0) {
                    Cookie::queue('applyid', $userData->id, $this->lifetime, '/', null, false, true, false, 'lax');
                    $cardno = random_code_num(16);
                    //Log::info('card no - '. $cardno);
                    if ($userData->cardtype == 12) {
                        $productslug = "hire-loan-agent";
                        $invfor = 2;
                        $invprefix = "LA_";
                    } else {
                        $productslug = "self-apply";
                        $invfor = 1;
                        $invprefix = "SA_";
                    }
                    /*Log::info('product Slug - '. $productslug);
                    Log::info('invfor - '. $invfor);
                    Log::info('invprefix - '. $invprefix);*/
                    $productData = Product::where('productslug',$productslug)->first();
                    $netamount = ($productData->inOffer == 1) ? $productData->offeramount : $productData->amount;

                    if ($userData->state == 'Gujarat') {
                        $cgstamount = $netamount * 0.09;
                        $sgstamount = $netamount * 0.09;
                    } else {
                        $igstamount = $netamount * 0.18;
                    }

                    $grandtotal = $netamount + $cgstamount + $sgstamount + $igstamount;

                    $membershipData = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'userid' => $userData->userid,
                        'registration_date' => date('Y-m-d'),
                        'expiry_date' => date('Y-m-d', strtotime(env('SUBSCRIPTION_EXPIRY'))),
                        'card_number' => $cardno,
                        'amount' => $grandtotal,
                        'paymentid' => $referenceId,
                        'isActive' => 1,
                        'isDelete' => 0
                    );
                    //Log::info('membership data - '. json_encode($membershipData));
                    $membershipId = MembershipOrder::create($membershipData)->id;
                    //Log::info(json_encode(Session::all()));
                    //Log::info('passsword user - ' . Cache::get('user_password'));
                    $password = Cache::get('user_password');
                    if($password =='' || $password == null){
                        dd('session null');
                    }
                    $passwordkey = Hash::make(Cache::get('user_password'));
                    $refcode = strtolower(substr(str_replace(" ", "", $userData->fullname), 0, 3));
                    $refcode .= substr($userData->mobile, -4);

                    $regData = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'update_date' => date('Y-m-d H:i:s'),
                        'password' => $passwordkey,
                        'refcode' => $refcode,
                        'process_step' => 4,
                        'isUser' => 2,
                        'acc_type' => Cookie::get('acc_type')
                    );
                    $response2 =  UserRegistration::where('id',$userData->userid)->update($regData);

                    $invoiceNo = SiteOption::where('option_key', 'newinvoiceno')
                        ->select('option_value')
                        ->first();

                    $invData3 = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'userid' => $userData->userid,
                        'cardid' => $membershipId,
                        // 'inv_for' => $invfor,
                        'inv_prefix' => $invprefix,
                        'inv_number' => $invoiceNo->option_value,
                        'inv_date' => date('Y-m-d'),
                        'inv_price' => $netamount,
                        'inv_cgst' => $cgstamount,
                        'inv_sgst' => $sgstamount,
                        'inv_igst' => $igstamount,
                        'inv_grandtotal' => $grandtotal,
                        'isdelete' => 0
                    );

                    $responseinvoice = Invoice::create($invData3)->id;
                    $invNoData = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'option_value' => $invoiceNo->option_value + 1
                    );
                    $updateInvoiceNo = SiteOption::where('option_key', 'newinvoiceno')->update($invNoData);
                    $data4 = array(
                        'payout' => 0,
                        'payout_amount' => $netamount * env('CU_PAYOUT_RATIO'),
                        'order_amount' => $netamount
                    );
                    $response4 = 'self-apply/paymentFailed';
                    $user = UserTree::where('subuserid', $userData->userid)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($user) {
                        // Update the record where the 'id' matches
                        $updated = UserTree::where('id', $user->id)->update($data4);

                        // If update was successful, set response4 to true
                        if ($updated) {
                            $response4 = 'self-apply/paymentSuccess';
                        }
                    } else {
                        $response4 = 'self-apply/paymentFailed';
                    }
                    //Log::info('response 4 - '. $response4);
                    /*$maildata = array(
                        'fullname' => $userdata->fullname,
                        'mobile' => $userdata->mobile,
                        'email' => $userdata->email,
                        'password' => $password,
                        'order_number' => $invoiceno,
                        'order_date' => date('d-m-Y'),
                        'order_amount' => $grandtotal
                    );
                    $sent = $this->Site_Digital_Model->sendSuccessGreetings($maildata);*/
                    if ($response2 > 0) {
                        $redRoute = 'self-apply/paymentSuccess'; // Row was updated
                    } else {
                        $redRoute = 'self-apply/paymentFailed'; // No rows were updated
                    }
                    return redirect($redRoute);
                } else {
                    return redirect("self-apply/paymentSuccess");
                }
            } else if ($txStatus == "PAYMENT_FAILURE") {
                //$sent = $this->Site_Digital_Model->sendPaymentFailedGreetings($userdata->mobile, $userdata->email);
                return redirect("self-apply/paymentFailed");
            } else {
                //$sent = $this->Site_Digital_Model->sendPaymentFailedGreetings($userdata->mobile, $userdata->email);
                //$key = stringCrypt($userdata->id, 'encrypt');
                //return redirect("digital/subscriptionorder/" . $key);
                return redirect("self-apply/paymentFailed");
            }
        }catch(\Exception $e){
            Log::error('checkout method error occured: ' . $e->getMessage());
            return redirect('/error')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /* paymentSuccess handle function */
    public function paymentSuccess(){
        /*Log::info('paymentSuccess');
        Log::info(json_encode(Session::all()));*/
        try{
            $meta = selfApplyMeta();
            $loanType = Cookie::get('loan_type');
            $applyId = Cookie::get('applyid');
            $orderId = Session::get('orderid');
            //Log::info('orderId = '. $orderId);
            if(!empty($loanType) && !empty($applyId) && !empty($orderId)){
                $data = array(
                    'loantype' => $loanType,
                    'status' => true
                );
                $userData = checkuserdata($applyId);
                $firstname = strtolower(strtok($userData->fullname, " "));
                $city = strtolower(preg_replace("/[^a-zA-Z]+/", "", $userData->city));
                $state = strtolower(getStateAbbreviation($userData->state));
                $orderData = orderdata($orderId,'phonepe_entry');
                //Log::info(json_encode($orderData));
                return view('selfApply.paymentSuccess',compact('data','orderData','meta'));
            } else {
                return redirect('/self-apply');
            }
        } catch(\Exception $e){
            Log::error('An error occurred: ' . $e->getMessage());
            return redirect('/error')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /* paymentFailed handle function */
    public function paymentFailed(){
        $meta = selfApplyMeta();
        return view('selfApply.buyNow',compact('meta'));
    }

    public function offer1()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug',config('constant.SA_OFFER_1'))->first();
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
        return view('selfApply.offers.offer-1',compact('meta','productData'));
    }

    /* get offer one in this send on payment gateway */
   public function getOffer1(Request $request){
        /* store fields in inputs variable */
        $inputs = $request->all();
        /* validate fields */
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
        ]);
        Log::info(json_encode($inputs));
        try{
            /* product Data */
            $products = Product::where('productslug',config('constant.SA_OFFER_1'))->first();
            Log::info('products - '.json_encode($products));
            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $first_name = $inputs['first_name'];
            $last_name = $inputs['last_name'];
            $mobile = $inputs['mobile'];
            $email = $inputs['email'];

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
                    'offerpage' => 4, // SA-Offer-1
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'emailid' => $email,
                    'amount' => $grandAmount,
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );

            // Get the ID of the updated or inserted record
            $record = DB::table('cardoffer')->where('mobile', $mobile)->first();
            $offerId = $record->id;

            Log::info('Offer data - '. $offerId);
            $orderId = number_format(microtime(true) * 1000, 0, '.', '');
            $returnUrl = 'https://wisemudra.com/api/self-apply/offer-1-response';

            if (env('LYRA_MODE') == "PROD") {
                $curlurl = "https://api.in.lyra.com/pg/rest/v1/charge";
            } else {
                $curlurl = "https://api.in.lyra.com/pg/rest/v1/charge";
            }
            /* lyra post data */
            $postData = array(
                "orderId" => $orderId,
                "currency" => 'INR',
                "amount" => $grandAmount * 100,
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
            Log::info('lyra entry - '. json_encode($postData));
            /* generate lyra paymenturl */
            $payurl = getlyrapaymenturl($curlurl, $postData);
            $lyraData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 6,
                'userid' => $offerId,
                'orderid' => $orderId,
                'orderamount' => $grandAmount,
                'ordernote' => $products->productname,
            );
            Log::info('Lyra insert - '. json_encode($lyraData));
            $response = LyraEntry::insert($lyraData);
            if ($payurl) {
                if ($payurl->paymentLink) {
                    return redirect($payurl->paymentLink);
                } else {
                    return redirect()->back();
                }
            } else {
                return redirect()->back();
            }
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return redirect()->back();
        }
    }

     public function offer1Response(Request $request){
        try{
            $inputs = $request->all();
            Log::info(json_encode($inputs));
            $meta = selfApplyMeta();
            if (isset($inputs["vads_order_id"])) {
                $orderId = $inputs["vads_order_id"];
                $orderAmount = $inputs["vads_amount"];
                $responseCode = $inputs["vads_charge_status"];
                $txnId = $inputs["vads_trans_uuid"];

                $paymentData = LyraEntry::where('orderid',$orderId)->first();

                $lyraData = array(
    				'rec_date' => date('Y-m-d H:i:s'),
    				'orderamount' => $orderAmount / 100,
    				'statuscode' => $responseCode,
    				'transactionid' => $txnId
    			);

    			$response1 = LyraEntry::where('id',$paymentData->id)->update($lyraData);

    			$userData = Cardoffer::where('id',$paymentData->userid)->first();

    			if ($responseCode == "PAID") {
    				$cardno = random_code_num(16);

    				$data = array(
    					'rec_date' => date('Y-m-d H:i:s'),
    					'card_number' => $cardno,
    					'amount' => $orderAmount / 100,
    					'paymentid' => $txnId,
    					'isActive' => 1
    				);

    				$response = Cardoffer::where('id',$paymentData->userid)->update($data);
    				//$sent = $this->Site_Onlineprocess_Model->sendPaymentGreetings($userdata->fullname, $userdata->mobile, $userdata->emailid);
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
        } catch(\Exception $e){
            Log::info($e->getMessage());
            $response = FALSE;
            return View('cardoffer-response', compact('meta','response'));
        }
    }

    public function offer2()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug',config('constant.SA_OFFER_2'))->first();
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
        return view('selfApply.offers.offer-2',compact('meta','productData'));
    }

    /* get offer two in this send on payment gateway */
    public function getOffer2(Request $request){
        /* store fields in inputs variable */
        $inputs = $request->all();
        /* validate fields */
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
        ]);
        Log::info(json_encode($inputs));
        try{
            /* product Data */
            $products = Product::where('productslug',config('constant.SA_OFFER_2'))->first();
            Log::info('products - '.json_encode($products));
            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $first_name = $inputs['first_name'];
            $last_name = $inputs['last_name'];
            $mobile = $inputs['mobile'];
            $email = $inputs['email'];

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
                    'offerpage' => 5,//SA offer 2
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

            Log::info('Offer data - '. $offerId);
            $orderId = number_format(microtime(true) * 1000, 0, '.', '');
            $encData = null;
            $returnUrl = 'https://wisemudra.com/api/self-apply/offer-2-response';

            if (env('SABPAISA_MODE') == "PROD") {
                $curlurl = "https://securepay.sabpaisa.in/SabPaisa/sabPaisaInit?v=1";
            } else {
                $curlurl = "https://stage-securepay.sabpaisa.in/SabPaisa/sabPaisaInit?v=1";
            }
            $fullname = trim($first_name)." ".trim($last_name);
            /* subpaisa encrypt data */
            $encData = "?clientCode=".env('SABPAISA_CLIENT_CODE')."&transUserName=".env('SABPAISA_USERNAME')."&transUserPassword=".env('SABPAISA_PASSWORD')."&amount=".round($grandAmount).
            "&amountType=INR&clientTxnId=".$orderId."&payerName=".$fullname."&payerMobile=".$mobile."&payerEmail=".trim(strtolower($email))."&mcc=5137&channelId=#&callbackUrl=".$returnUrl;
            Log::info('subpaisa entry - '. $encData);

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
    			'entryfor' => 7,
    			'userid' => $offerId,
    			'orderid' => $orderId,
    			'orderamount' => round($grandAmount),
    			'ordernote' => 'Card Offer'
    		);
    		Log::info('Subpaisa insert - '. json_encode($subpaisaData));

            $response = SubpaisaEntry::insert($subpaisaData);
            return view('pg.pay', [
                'data' => $encryptData,
                'clientCode' => env('SABPAISA_CLIENT_CODE'),
                'action' => $curlurl
            ]);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return redirect()->back();
        }
    }

    public function offer2Response(Request $request){
        try{
            $meta = selfApplyMeta();
            $query = $request->input('encResponse');
            $authKey = env('SABPAISA_AUTH_KEY');
            $authIV = env('SABPAISA_AUTH_IV');

            $AesCipher = new Authuntication();
            $decText = $AesCipher->decrypt($authKey, $authIV, $query);

            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;

            $token = strtok($decText,"&");

            $i=0;

            /* response value After Decryption

            payerName=Test&payerEmail=Test@gmail.com&payerMobile=1234567890&clientTxnId=1907&payerAddress=NA&amount=10.0
            &clientCode=XXXXX&paidAmount=10.1&paymentMode=Debit Card&bankName=BOB&amountType=INR&status=FAILED&statusCode=0300&challanNumber=null
            &sabpaisaTxnId=883602112220421050&sabpaisaMessage=Sorry, Your Transaction has Failed.&bankMessage=DebitCard&bankErrorCode=null
            &sabpaisaErrorCode=null&bankTxnId=101202235510088892&transDate=Wed Dec 21 16:26:28 IST 2022&udf1=NA&udf2=NA&udf3=NA&udf4=NA&udf5=NA
            &udf6=NA&udf7=NA&udf8=NA&udf9=null&udf10=null&udf11=null&udf12=null&udf13=null&udf14=null&udf15=null&udf16=null&udf17=null&udf18=null
            &udf19=null&udf20=nulli- */

            Log::info($token);

            while ($token !== false)
            {
                $i=$i+1;
                $token1=strchr($token, "=");
                $token=strtok("&");
                $fstr=ltrim($token1,"=");

                if($i==1) {
    			  $payerName = $fstr;
    			}
                if($i==2)
                    $payerEmail=$fstr;
                if($i==3)
                    $payerMobile=$fstr;
                if($i==4)
                    $clientTxnId=$fstr;
                if($i==5)
                    $payerAddress=$fstr;
                if($i==6)
                    $amount=$fstr;
                if($i==7)
                    $clientCode=$fstr;
                if($i==8)
                    $paidAmount=$fstr;
                if($i==9)
                    $paymentMode=$fstr;
                if($i==10)
                    $bankName=$fstr;
                if($i==11)
                    $amountType=$fstr;
                if($i==12)
                    $status=$fstr;
                if($i==13)
                        $statusCode=$fstr;
                if($i==14)
                        $challanNumber=$fstr;
                if($i==15)
                        $sabpaisaTxnId=$fstr;
                if($i==16)
                        $sabpaisaMessage=$fstr;
                if($i==17)
                        $bankMessage=$fstr;
                if($i==18)
                        $bankErrorCode=$fstr;
                if($i==19)
                        $sabpaisaErrorCode=$fstr;
                if($i==20)
                        $bankTxnId=$fstr;
                if($i==21)
                    $transDate=$fstr;

                    if($token == true)
                    {

                    }
            }
            /* update client tax id in subpaisa_entry table */
            Log::info($clientTxnId);
            Log::info($paymentMode);
            Log::info($status);
            Log::info($statusCode);

            $paymentData = SubpaisaEntry::where('orderid','$clientTxnId')->first();

            $subpaisaData = array(
    			'rec_date' => date('Y-m-d H:i:s'),
    			'referenceid' => $sabpaisaTxnId,
    			'txstatus' => $status,
    			'paymentmode' => $paymentMode
    		);

    		$response1 = SubpaisaEntry::where('id',$paymentData->id)->update($subpaisaData);
            if($statusCode == '0000') {
    			$userData = Cardoffer::where('id',$paymentData->userid)->first();
    			$cardno = random_code_num(16);
    			$data = array(
    				'rec_date' => date('Y-m-d H:i:s'),
    				'card_number' => $cardno,
    				'registration_date' => date('Y-m-d'),
    				'expiry_date' => date('Y-m-d', strtotime('+3 months')),
    				'paymentid' => $sabpaisaTxnId,
    				'isActive' => 1
    			);

    			$response = CardOffer::where('id',$paymentData->userid)->update($data);

    			//$sent = $this->Site_Onlineprocess_Model->sendPaymentGreetings($userdata->fullname, $userdata->mobile, $userdata->emailid);

    			 return view('cardoffer-response', [
    			    'meta' => $meta,
    	            'response' => $response,
                    'clientTxnId' => $clientTxnId,
                    'amount' => $amount,
                    'paymentMode' => $paymentMode,
    	            'payerName' => $payerName,
    	            'payerEmail' => $payerEmail,
    	            'payerMobile' => $payerMobile,
                    // Add other variables as needed
                ]);
    		}
    		else if($statusCode == '0300') {
    			return view('cardoffer-response', [
    			    'meta' => $meta,
    	            'response' => FALSE,
                ]);
    		}
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return view('cardoffer-response', [
			    'meta' => $meta,
	            'response' => FALSE,
            ]);
        }
    }

    public function offer3()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug',config('constant.SA_OFFER_3'))->first();
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
        return view('selfApply.offers.offer-3',compact('meta','productData'));
    }

    /* get offer two in this send on payment gateway */
    public function getOffer3(Request $request){
        /* store fields in inputs variable */
        $inputs = $request->all();
        /* validate fields */
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
        ]);
        //Log::info(json_encode($inputs));
        try{
            /* product Data */
            $products = Product::where('productslug',config('constant.SA_OFFER_3'))->first();
            //Log::info('products - '.json_encode($products));
            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $first_name = $inputs['first_name'];
            $last_name = $inputs['last_name'];
            $mobile = $inputs['mobile'];
            $email = $inputs['email'];

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
                    'offerpage' => 6,
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
            $returnUrl = 'https://wisemudra.com/api/loan-agent/offer-3-response';

            /* cipherPay PG starts */
            $refId = rand(1000,9999);
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
    			'rec_date' => date('Y-m-d H:i:s'),
    			'entryfor' => 8,
    			'userid' => $offerId,
    			'orderid' => $response['data']['txnid'],
    			'orderamount' => round($grandAmount),
    			'ordernote' => 'Card Offer'
    		);
    		//Log::info('Cipher insert - '. json_encode($cipherPayData));

            $res = CipherPayEntry::insert($cipherPayData);
            return view('pg.cipherQR', compact('response'));
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return redirect()->back();
        }
    }

    public function offer3Response(Request $request){
        try{
            $meta = selfApplyMeta();
            $datas = Session::get('cipherResponse');
            $paymentData = CipherPayEntry::where('orderid',$datas['data']['txnid'])->first();
            //Log::info('cipher table data - '. json_encode($paymentData));

            $cipherData = array(
    			'rec_date' => date('Y-m-d H:i:s'),
    			'referenceid' => $datas['data']['upiRefId'],
    			'txstatus' => $datas['data']['status'],
    			'paymentmode' => $datas['data']['remarks'],
    			'ordernote' => 'Card Offer (utr - '.$datas['data']['utr'].')'
    		);

    		$response1 = CipherPayEntry::where('id',$paymentData->id)->update($cipherData);

			$userData = Cardoffer::where('id',$paymentData->userid)->first();
			$cardno = random_code_num(16);
			$data = array(
				'rec_date' => date('Y-m-d H:i:s'),
				'card_number' => $cardno,
				'registration_date' => date('Y-m-d'),
				'expiry_date' => date('Y-m-d', strtotime('+3 months')),
				'paymentid' => $datas['data']['txnid'],
				'isActive' => 1
			);

			$response = CardOffer::where('id',$paymentData->userid)->update($data);

			//$sent = $this->Site_Onlineprocess_Model->sendPaymentGreetings($userdata->fullname, $userdata->mobile, $userdata->emailid);

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

        } catch(\Exception $e){
            Log::info($e->getMessage());
            return redirect()->back();
        }
    }
}
