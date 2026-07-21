<?php

namespace App\Http\Controllers;

use App\Models\AirpayEntry;
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

class LoanAgentController extends Controller
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
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAocBe0dez4uad4TmP3oYG
qWPORb6y6akc63xSugUwMqZEL58z2spDiPsQa/PQOLOfYHQslJEzTdRef/ycFiAb
/cS7JsfWV7S2qTSSTcJLF4x8nWzWjmpOKbb7+P8y3/golv80Thh5qHqF3V7y5oWt
C0tDNAkLZ0DwnCOeQU1hMXUJpoGt0GOZFDpfG0C2kUaonNR9U0x4E88/sGiz+1bv
Cmr4hNEnNih+ACs0QV3xOZlRTdoi+DakhNMPcW+KaMw5i99maDierkX1znbx+lU8
oxFAKb6YGgqhd9DVITzo8ZThAScqpSph+rAcswNSonhonvF/gOXoWnGMugb+c3BT
qwIDAQAB
-----END PUBLIC KEY-----";     //body key
        $this->privateKey = "-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQChwF7R17Pi5p3h
OY/ehgapY85FvrLpqRzrfFK6BTAypkQvnzPaykOI+xBr89A4s59gdCyUkTNN1F5/
/JwWIBv9xLsmx9ZXtLapNJJNwksXjHydbNaOak4ptvv4/zLf+CiW/zROGHmoeoXd
XvLmha0LS0M0CQtnQPCcI55BTWExdQmmga3QY5kUOl8bQLaRRqic1H1TTHgTzz+w
aLP7Vu8KaviE0Sc2KH4AKzRBXfE5mVFN2iL4NqSE0w9xb4pozDmL32ZoOJ6uRfXO
dvH6VTyjEUApvpgaCqF30NUhPOjxlOEBJyqlKmH6sByzA1KieGie8X+A5ehacYy6
Bv5zcFOrAgMBAAECggEAPw+fSANnC/KqIclNyE0LYtXY8QMQgJ1ge4SMQh7MtCpz
UfepAUcy/kAXnma/SqPo4nNYgBF95X2C3DYRamTZVN3yswNdEvOO9TfcRDmYChXI
7Z0lpv9V+thusxxXas82j+vuKfZL0/30m6ItY+dA2DLe//X4vqgoRk9ynvX6iwt9
4DvXWaJIRpFpkMeJpA+wFquHac+cJLnZZj8G+ZpQpvFRUs7sfjw3zUl8y7LVn3mC
zEo6lmJYaqBf3sKOrCnZPrRdH9Y/4JkrnUfBUmhi28Aa87m89Wsv61JEIJHKwAYu
a94TwavVAI9ZLu6VqIaBl1IUPGfsthputfIWFcBnjQKBgQDQnLh91styoi+dM0yJ
J7Jm6gxT+770EapsEJBw28c/LXX8u97XO35Ty5Pw663pXMNten9QZpbPsYnjq//l
apg3SqfZGaer/95s+wz0GFnjCiPlDxm2Q6p3EBRWg70tY4K/olE/P3TyTlwKfTzF
77q2kH0a7kAcBD2aDEPLbsXZjwKBgQDGfpbt7Cl7apDQuQl9SS7mLs42XSDbI3QN
uISp0MfyvWa4hgy5uNcfFXkJ6/3wKenH2n+R32PwAhfqcEWsjOYb7cojGsMl3Ju5
J5+He2+8xIwAJ6hG4t+UISsCQFwTU5ajGc6iWoQmMTEZ4Ih3+h9UAZ+GlCix809t
jr1Sl0T+JQKBgQCynU01qaB+YTFlZpPkZ1HP3ht6GPVxYmLJrhEOII9jn5gDMhRl
srHCK29a+1/njB5j8VtqyrvbzsYiYpVyp6b2yHwYXWf709Ns+jMoGGV2CKudJyW7
sgoVcXYIcTmb0DUVwXPRNJL8GG2kKYDMdSsnv2TulwnbMyJPcKrnVsweLwKBgQCo
ND3SAH5mhzeQqDzSXmHPzXoRt3lQOgruVZ6WCMZnfPi/BVljSK+DN78KGWFnUx04
rn/MLXGSwTNjByEDx6J3qFnSxar5Oqj7jggx1vgpDqVUvEZtS3QLItA/aCqedgcA
z627BtlVQ/pH423BvcMufPGiKYsSwQxd2se0ZVuhwQKBgQDBe4psE5kz9CtO9ddb
9n/6hBRdG3YE0ZT+2PeeF+G8MnPRpKYm+r6/w6qtTrheFOBOetMhLy+kGMwWR1Xz
+CxFY2HTYD6uLgpNr3W0+f6NjHJiHFKKlF1hVOplf9Toven58H6WdhlTwEaD0JbT
F3b+8WxBHnqsfnXtlomwq+RTcw==
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
        return view('loanAgent.main', compact('meta'));
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
                    Cookie::queue('monthly_income', $request->input('monthly_income'), $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('current_emi', $request->input('current_emi'), $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('process_step', $user->process_step, $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('user_type', $loanApp->user_type, $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('acc_type', $user->acc_type, $this->lifetime, '/', null, false, true, false, 'lax');
                    Cookie::queue('userid', $user->id, $this->lifetime, '/', null, false, true, false, 'lax');
                    $otp = DB::table('otp_verifications')->where('mobile',$inputs['mobile'])->update(['acc_type'=>$inputs['acc_type']]);
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
                    $redirectUrl = route(loanagenturl($user->process_step));
                    if(isset($inputs['offerPageRedirect']) && $inputs['offerPageRedirect']){
                        return redirect()->route(loanagenturl($user->process_step));
                    } else {
                        return response()->json(['type' => 'SUCCESS', 'message' => 'User Verified', 'data' => '', 'redirectUrl' => $redirectUrl]);
                    }
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
            $redirectUrl = route('loan.agent.loan.details');
            return response()->json(['type' => 'SUCCESS', 'message' => 'OTP validate', 'data' => '', 'redirectUrl' => $redirectUrl]);
            /*if(!Cookie::has('user_mobile') || !Cookie::has('isVerified') || !Cookie::has('user_type') || !Cookie::has('process_step') || !Cookie::has('loan_amount')){
                //store is verified 1 in cookie when otp getting match
                Cookie::queue('isVerified', 1, $this->lifetime, '/', null, false, true, false, 'lax');
                //dd('here');
                $redirectUrl = route('loan.agent.loan.details');
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
                $redirectUrl = route(loanagenturl($user->process_step));
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
            'current_emi' => 'required'
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
                    'currentemi' => $request->input('current_emi'),
                    'application_number' => random_code(8)
                ]);
                DB::commit();
                Cookie::queue('userid', $userid, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('isUser', 1, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('applyid', $applyid, $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('loan_amount', $request->input('loan_amount'), $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('monthly_income', $request->input('monthly_income'), $this->lifetime, '/', null, false, true, false, 'lax');
                Cookie::queue('current_emi', $request->input('current_emi'), $this->lifetime, '/', null, false, true, false, 'lax');
                /*$currentCookie = json_decode(Cookie::get('process_step'), true);
                $currentCookie['process_step'] = 2;*/
                Cookie::queue('process_step', 2, $this->lifetime, '/', null, false, true, false, 'lax');
                return response()->json(['type' => 'SUCCESS', 'message' => 'Loan details added successfully', 'data' => $userid]);
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
        //Log::info(json_encode(request()->cookie));
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
                'pancard' => ['required', 'regex:/^[A-Z]{5}\d{4}[A-Z]$/', 'unique:user_registrations,pancard'],
                'pincode' => 'required|digits:6',
                'city' => 'required',
                'state' => 'required'
            ], [
                'pancard.regex' => 'Please insert valid PAN Card number.',
                'pancard.unique' => 'This PAN Card already in use.'
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
            return redirect()->route('loan.agent.main');
        } else {
            if(Cookie::get('process_step') == 3){
                $banks = ApplyLink::with('bank')->where('isDelete',0)->inRandomOrder()->limit(1)->first();
                return view('loanAgent.getOffers',compact('meta','banks'));
            } else {
                $returnUrl = loanagenturl(Cookie::get('process_step'));
                return redirect()->route($returnUrl);
            }
        }
    }

    /* buy now */
    public function buyNow(){
        $meta = selfApplyMeta();
        $eligibilityamt = calEligiblity(Cookie::get('monthly_income'), Cookie::get('current_emi'), ((Cookie::get('loan_type') == 2) ? 11.5 : 12.5), Cookie::get('loan_amount'));
        /* interakt code here starts */
        $data2 = array(
            'phoneNumber' => Cookie::get('user_mobile'),
            'countryCode' => '+91',
            'traits' => array(
                'name' => Cookie::get('fullname')
            ),
            'tags' => array('Get Offer Self')
        );

        $restrack1 = user_track($data2);

        $data3 = array(
            'phoneNumber' => Cookie::get('user_mobile'),
            'countryCode' => '+91',
            'event' => 'Get Offer Self',
            'traits' => array(
                'EligibleAmount' => $eligibilityamt
            ),
        );
        $restrack2 = event_track($data3);
        /* interakt code ends here */
        return view('loanAgent.buyNow', compact('meta'));
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
            $productslug = 'hire-loan-agent';
            $entryfor = 12;
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
            $returnUrl = route('api.loan.agent.buy.digital.agent.plan');
            $callbackUrl = route('loan.agent.callbackUrl');
            if (env('PHONEPE_ENV') == "PRODUCTION") {
                $curlurl = 'https://api.phonepe.com/apis/hermes/pg/v1/pay';
            } else {
                $curlurl = 'https://api-preprod.phonepe.com/apis/hermes/pg/v1/pay';
            }

            //Log::info($curlurl);
            $phonePeData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 12,
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
            if ($payUrl) {
                if ($payUrl->data->instrumentResponse->redirectInfo->url) {
                    //Log::info('if payment page');
                    header("location:" . $payUrl->data->instrumentResponse->redirectInfo->url);
                    die;
                } else {
                    //Log::info('else');
                    return redirect("loan.agent.main");
                }
            } else {
                //Log::info('super else');
                return redirect("loan.agent.main");
            }
            /*$curlurl = "https://api.zaakpay.com/api/paymentTransact/V8";

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
            Log::info('zaakpay data - '. json_encode($zaakpayPostData));
            ksort($zaakpayPostData);
            $checksumData = "";
            foreach ($zaakpayPostData as $key => $value) {
                $checksumData .= $key . '=' . $value . '&';
            }

            $checksum = hash_hmac('sha256', $checksumData, env('ZAAKPAY_SECRET_KEY'));
            Log::info('checksum - '.$checksum);

            $zaakPayData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => $entryfor, // 11 - selfapply, 12 - loanagent
                'userid' => Cookie::get('userid'),
                'orderid' => $orderid,
                'orderamount' => $grandAmount,
                'ordernote' => $productData->productname,
            );
            Log::info('zaakpay entry' . json_encode($zaakPayData));
            $response = ZaakpayEntry::create($zaakPayData);*/

            //return View('pg.zaakpay-checkout',compact('zaakpayPostData','checksum','curlurl'));
        } catch(\Exception $e){
            Log::error('checkout method error occured: ' . $e->getMessage());
            return redirect('/error')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /* callback url ofd loan agent */
    public function callbackUrl(){
        dd('Callback function call.Go Back and make further process');
    }

    public function buyDigitalPlan(Request $request){
        try {
            //Log::info('request data - '.json_encode($request->all()));
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $meta = selfApplyMeta();
            if (!$request->has(['code', 'transactionId', 'providerReferenceId'])) {
                //Log::info('in if self');
                return redirect("loan-agent");
            }
            $paymentData = PhonrPeEntry::where('orderid', $request->input('transactionId'))->first();

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
                        'expiry_date' => date('Y-m-d', strtotime('+3 months')),
                        'card_number' => $cardno,
                        'amount' => $grandtotal,
                        'paymentid' => $transactionId,
                        'isActive' => 1,
                        'isDelete' => 0
                    );
                    //Log::info('membership data - '. json_encode($membershipData));
                    $membershipId = MembershipOrder::create($membershipData)->id;

                    //Log::info('passsword user - ' . Cache::get('user_password'));
                    $password = Cache::get('user_password');
                    if($password =='' || $password == null){
                        dd('session null');
                    }
                    $passwordkey = Hash::make(Cache::get('user_password'));
                    $refcode = strtolower(substr(str_replace(" ", "", $userData->fullname), 0, 3));
                    $refcode .= substr($userData->mobile, -4);
                    $staffID = assignAgent();
                    $regData = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'update_date' => date('Y-m-d H:i:s'),
                        'staff_id' => $staffID,
                        'password' => $passwordkey,
                        'refcode' => $refcode,
                        'process_step' => 4,
                        'isUser' => 2,
                        'acc_type' => 2
                    );
                    Log::info('reg data'. json_encode($regData));
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
                    //Log::info('invData - '.json_encode($invData3));
                    $responseinvoice = Invoice::create($invData3)->id;
                    $invNoData = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'option_value' => $invoiceNo->option_value + 1
                    );
                    $updateInvoiceNo = SiteOption::where('option_key', 'newinvoiceno')->update($invNoData);
                    /*$data4 = array(
                        'payout' => 0,
                        'payout_amount' => $netamount * env('CU_PAYOUT_RATIO'),
                        'order_amount' => $netamount
                    );*/
                    //$response4 = 'loan-agent/paymentFailed';
                    /* wp campaign */
                    /*$user = UserTree::where('subuserid', $userData->userid)
                        ->orderBy('id', 'desc')
                        ->first();*/

                    /*if ($user) {
                        // Update the record where the 'id' matches
                        $updated = UserTree::where('id', $user->id)->update($data4);
                    }*/

                    //Log::info('response 4 - '. $response4);

                    $mailData = array(
                        'fullname' => $userData->first_name.' '.$userData->last_name,
                        'mobile' => $userData->mobile,
                        'email' => $userData->email,
                        'password' => $password,
                        'order_number' => $invoiceNo->option_value,
                        'order_date' => date('d-m-Y'),
                        'order_amount' => $grandtotal,
                        'transactionId' => $transactionId,
                    );
                    $sendGreetings = view('mail.welcomeGreetings',$mailData)->render();
                    $invAttach = array_merge($invData3,
                        [
                            'fullname' => $userData->first_name.' '.$userData->last_name,
                            'city' => $userData->city,
                            'mobile' => $userData->mobile,
                            'email' => $userData->email,
                            'acc_type' => $userData->acc_type,
                            'state' => $userData->state,
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
                    sendBrevoHtmlMail2($mailData, 'Congratulations! Payment Successful for Wisemudra’s Hire Agent Plan.', $sendGreetings, 3, $pdf);


                    if ($response2 > 0) {
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
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return redirect("loan-agent/paymentFailed");
        }
    }

    /* buyDigitalPlan function handle */
    public function buyDigitalPlan_Zaakpay(Request $request){
        try{
            //Log::info('request data - '.json_encode($request->all()));
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $meta = selfApplyMeta();

            $orderId = $request->input('orderId');
            $responseCode = $request->input('responseCode');
            $orderAmount = $request->input('amount') / 100;
            $txnId = $request->input('pgTransId');
            $paymentMode = $request->input('paymentMode');
            $recd_checksum = $request->input('checksum');
            Log::info($request->all());
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
                $paymentData = ZaakpayEntry::where('orderid',$orderId)->first();
                //Log::info('paymentData - '.json_encode($paymentData));

                $zaakPayData = array(
                    'rec_date' => date('Y-m-d H:i:s'),
                    'orderamount' => $orderAmount,
                    'statuscode' => $responseCode,
                    'transactionid' => $txnId,
                    'paymentmode' => $paymentMode
                );
                Log::info('zaakPayData - '. json_encode($zaakPayData));
                $response1 = ZaakpayEntry::where('id',$paymentData->id)->update($zaakPayData);
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

                if ($responseCode == 100 || $responseCode == 208 || /* $responseCode == 102 || */ $responseCode == 601) {
                    $cardno = random_code_num(16);
                    $membershipData = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'userid' => $userData->userid,
                        'registration_date' => date('Y-m-d'),
                        'expiry_date' => date('Y-m-d', strtotime('+3 months')),
                        'card_number' => $cardno,
                        'amount' => $orderAmount,
                        'paymentid' => $txnId,
                        'isActive' => 1,
                        'isDelete' => 0
                    );
                    //Log::info('membership data - '. json_encode($membershipData));
                    $membershipId = MembershipOrder::create($membershipData)->id;

                    //Log::info('passsword user - ' . Cache::get('user_password'));
                    $password = Cache::get('user_password');
                    if($password =='' || $password == null){
                        dd('session null');
                    }
                    $passwordkey = Hash::make(Cache::get('user_password'));
                    $refcode = strtolower(substr(str_replace(" ", "", $userData->fullname), 0, 3));
                    $refcode .= substr($userData->mobile, -4);
                    $staffID = assignAgent();
                    $regData = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'update_date' => date('Y-m-d H:i:s'),
                        'staff_id' => $staffID,
                        'password' => $passwordkey,
                        'refcode' => $refcode,
                        'process_step' => 4,
                        'isUser' => 2,
                        'acc_type' => 2
                    );
                    Log::info('reg data'. json_encode($regData));
                    $response2 =  UserRegistration::where('id',$userData->userid)->update($regData);

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
                    $productData = Product::where('productslug',$productslug)->first();
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
                    //Log::info('invData - '.json_encode($invData3));
                    $responseinvoice = Invoice::create($invData3)->id;
                    $invNoData = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'option_value' => $invoiceNo->option_value + 1
                    );
                    $updateInvoiceNo = SiteOption::where('option_key', 'newinvoiceno')->update($invNoData);
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

                    $mailData = array(
                        'fullname' => $userData->first_name.' '.$userData->last_name,
                        'mobile' => $userData->mobile,
                        'email' => $userData->email,
                        'password' => $password,
                        'order_number' => $invoiceNo->option_value,
                        'order_date' => date('d-m-Y'),
                        'order_amount' => $grandtotal,
                        'transactionId' => $txnId,
                    );
                    $sendGreetings = view('mail.welcomeGreetings',$mailData)->render();
                    $invAttach = array_merge($invData3,
                        [
                            'fullname' => $userData->first_name.' '.$userData->last_name,
                            'city' => $userData->city,
                            'mobile' => $userData->mobile,
                            'email' => $userData->email,
                            'acc_type' => $userData->acc_type,
                            'state' => $userData->state,
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
                    sendBrevoHtmlMail2($mailData, 'Congratulations! Payment Successful for Wisemudra’s Hire Agent Plan.', $sendGreetings, 3, $pdf);


                    if ($response2 > 0) {
                        $redRoute = 'loan-agent/paymentSuccess'; // Row was updated
                    } else {
                        $redRoute = 'loan-agent/paymentFailed'; // No rows were updated
                    }
                    return redirect($redRoute);
                } else {
                    Log::info('response code not sucess');
                    return redirect("loan-agent/paymentFailed");
                }
            } else {
                Log::info('else checksum not matched');
                //$sent = $this->Site_Digital_Model->sendPaymentFailedGreetings($userdata->mobile, $userdata->email);
                //$key = stringCrypt($userdata->id, 'encrypt');
                //return redirect("digital/subscriptionorder/" . $key);
                return redirect("loan-agent/paymentFailed");
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

            if (isset($loanType, $applyId, $orderId) && $loanType !== null && $applyId !== null && $orderId !== null) {
                $data = array(
                    'loantype' => $loanType,
                    'status' => true
                );
                $userData = checkuserdata($applyId);
                $firstname = strtolower(strtok($userData->fullname, " "));
                $city = strtolower(preg_replace("/[^a-zA-Z]+/", "", $userData->city));
                $state = strtolower(getStateAbbreviation($userData->state));
                $orderData = orderdata($orderId,'zaakpay_entry');

                /* interakt code starts here */
                $data2 = array(
                    'phoneNumber' => Cookie::get('user_mobile'),
                    'countryCode' => '+91',
                    'traits' => array(
                        'name' => Cookie::get('fullname')
                    ),
                    'tags' => array('Payment Successful Hire')
                );
                $restrack1 = user_track($data2);

                $data3 = array(
                    'phoneNumber' => Cookie::get('user_mobile'),
                    'countryCode' => '+91',
                    'event' => 'Payment Successful Hire'
                );
                $restrack2 = event_track($data3);
                /* interakt code ends here */

                //Log::info(json_encode($orderData));
                return view('loanAgent.paymentSuccess',compact('data','orderData','meta'));
            } else {
                return redirect('/loan-agent');
            }
        } catch(\Exception $e){
            Log::error('An error occurred: ' . $e->getMessage());
            return redirect('/error')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /* paymentFailed handle function */
    public function paymentFailed(){
        $meta = selfApplyMeta();
        return view('loanAgent.buyNow',compact('meta'));
    }


    public function checkUserProcess($inputs){
        $regUser = UserRegistration::where('mobile',$inputs['mobile'])->where(['isActive'=>1,'isDelete'=>0])->first();
        if($regUser){
            /* user found in user registration table */
            if($regUser->isUser == 2){
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
            }
        } else {
            /* user not found in user registration table */
            $userDetails = Cardoffer::where('mobile',$inputs['mobile'])->first();
            if($userDetails){
                /* Data found */
                if($userDetails->paymentid!=NULL && $userDetails->isActive==1 && $userDetails->isDelete==0){
                    /* User not in lead */
                    if($userDetails->isCustomer == 1){
                        /* check in user registration table */
                        return array('type'=>'SUCCESS','message'=>'You are already a customer.Kindly login your customer portal.','url'=>route('customer.login'));
                    } else {
                        /* payment done but not convert as a customer */
                        return array('type'=>'ERROR','message'=>'User not converted as a customer.Please contact to support team.');
                    }
                } else {
                    /* user in lead */
                    return FALSE;
                }
            } else {
                /* Data not found */
                return FALSE;
            }
        }
    }
    /* offer 1 - lyra */
    public function offer1()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug',env('LA_OFFER_1'))->first();
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
        return view('loanAgent.offers.offer-1',compact('meta','productData'));
    }

    /* get offer one in this send on payment gateway */
    public function getOffer1(Request $request)
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
            $products = Product::where('productslug',env('LA_OFFER_1'))->first();
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
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 1,//La offer 1 or great deal offer
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

            //Log::info('Offer data - '. $offerId);
            $orderId = number_format(microtime(true) * 1000, 0, '.', '');
            $returnUrl = 'https://wisemudra.com/api/loan-agent/great-deal-offer-response';

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
            //Log::info('lyra entry - '. json_encode($postData));
            /* generate lyra paymenturl */
            $payurl = getlyrapaymenturl($curlurl, $postData);
            $lyraData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 3,//la offer 1 or great deal offer
                'userid' => $offerId,
                'orderid' => $orderId,
                'orderamount' => floor($grandAmount),
                'ordernote' => $products->productname,
            );
            //Log::info('Lyra insert - '. json_encode($lyraData));
            $response = LyraEntry::insert($lyraData);
            if ($payurl) {
                if ($payurl->paymentLink) {
                    return response()->json(array('type'=>'SUCCESS','message'=>'Please wait...We are redirecting to Payment Page','url'=>$payurl->paymentLink));
                } else {
                    return response()->json(array('type'=>'ERROR','url'=>route('loan.agent.offer1')));
                }
            } else {
                return response()->json(array('type'=>'ERROR','url'=>route('loan.agent.offer1')));
            }
        } catch(ValidationException $e){
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Something went wrong.'));
        }
    }

    /* offer 1 response */
    public function offer1Response(Request $request){
        try{
            $inputs = $request->all();
            //Log::info(json_encode($inputs));
            $meta = selfApplyMeta();
            $response = FALSE;
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
                        'registration_date' => date('Y-m-d'),
    				    'expiry_date' => date('Y-m-d', strtotime('+3 months')),
    					'paymentid' => $txnId,
    					'isActive' => 1
    				);

    				$response = Cardoffer::where('id',$paymentData->userid)->update($data);
                    $sent = sendPaymentGreetings($userData->first_name.' '.$userData->last_name, $userData->mobile, $userData->emailid);
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

    /* load offer 2 - subpaisa*/
    public function offer2()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug',env('LA_OFFER_2'))->first();
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
        return view('loanAgent.offers.offer-2',compact('meta','productData'));
    }

    /* get offer two in this send on payment gateway */
    public function getOffer2(Request $request)
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
            $products = Product::where('productslug',env('LA_OFFER_2'))->first();
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
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 2,// la offer 2 or elite offer
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
            $returnUrl = 'https://wisemudra.com/api/loan-agent/elite-offer-response';

            if (env('SABPAISA_MODE') == "PROD") {
                $curlurl = "https://securepay.sabpaisa.in/SabPaisa/sabPaisaInit?v=1";
            } else {
                $curlurl = "https://stage-securepay.sabpaisa.in/SabPaisa/sabPaisaInit?v=1";
            }
            $fullname = trim($first_name)." ".trim($last_name);
            /* subpaisa encrypt data */
            $encData = "?clientCode=".env('SABPAISA_CLIENT_CODE')."&transUserName=".env('SABPAISA_USERNAME')."&transUserPassword=".env('SABPAISA_PASSWORD')."&amount=".round($grandAmount).
            "&amountType=INR&clientTxnId=".$orderId."&payerName=".$fullname."&payerMobile=".$mobile."&payerEmail=".trim(strtolower($email))."&mcc=5137&channelId=#&callbackUrl=".$returnUrl;
            //Log::info('subpaisa entry - '. $encData);

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
    			'entryfor' => 4,//la offer 2 or elite offer
    			'userid' => $offerId,
    			'orderid' => $orderId,
    			'orderamount' => round($grandAmount),
    			'ordernote' => $products->productname
    		);
    		//Log::info('Subpaisa insert - '. json_encode($subpaisaData));

            $response = SubpaisaEntry::insert($subpaisaData);
            $html = view('pg.pay', [
                'data' => $encryptData,
                'clientCode' => env('SABPAISA_CLIENT_CODE'),
                'action' => $curlurl
            ])->render();
            return response()->json(array('type'=>'SUCCESS','message'=>'Please wait...We are redirecting to Payment Page','html'=>$html));
        } catch(ValidationException $e){
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Something went wrong.'));
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

            //Log::info($token);

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
            //Log::info($clientTxnId);
            //Log::info($paymentMode);
            //Log::info($status);
            //Log::info($statusCode);

            $paymentData = SubpaisaEntry::where('orderid',$clientTxnId)->first();

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
                    'amount' => $paymentData->orderamount,
    				'isActive' => 1
    			);

    			$response = Cardoffer::where('id',$paymentData->userid)->update($data);

                $sent = sendPaymentGreetings($userData->first_name.' '.$userData->last_name, $userData->mobile, $userData->emailid);

    			 return view('cardoffer-response', [
    			    'meta' => $meta,
    	            'response' => TRUE,
                    /* 'clientTxnId' => TRUE,
                    'amount' => $amount,
                    'paymentMode' => $paymentMode,
    	            'payerName' => $payerName,
    	            'payerEmail' => $payerEmail,
    	            'payerMobile' => $payerMobile, */
                    // Add other variables as needed
                ]);
    		}
    		else if($statusCode == '0300') {
    			return view('cardoffer-response', [
    			    'meta' => $meta,
    	            'response' => $status,
                ]);
    		} else {
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

    /* load offer 3 - cipherpay*/
    public function offer3()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug',env('LA_OFFER_3'))->first();
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
        return view('loanAgent.offers.offer-3',compact('meta','productData'));
    }

    /* get offer two in this send on payment gateway */
    public function getOffer3(Request $request)
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
            $products = Product::where('productslug',env('LA_OFFER_3'))->first();
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

            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $mobile], // Search condition
                [ // Values to update or insert
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 3,//la offer 3 or ultra saver offer
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
            $returnUrl = 'https://Wisemudra.com/api/loan-agent/ultra-saver-offer-response';

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
    			'entryfor' => 5,// la offer 3 or ultra saver offer
    			'userid' => $offerId,
    			'orderid' => '',
    			'orderamount' => round($grandAmount),
    			'ordernote' => $products->productname
    		);
    		//Log::info('Cipher insert - '. json_encode($cipherPayData));

            $res = CipherPayEntry::insert($cipherPayData);
            $html = view('pg.cipherQR', compact('response'))->render();
            return response()->json(array('type'=>'SUCCESS','message'=>'Please wait...We are redirecting to Payment Page','html'=>$html));
        } catch(ValidationException $e){
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Something went wrong.'));
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
    			'ordernote' => $paymentData->ordernote.' (utr - '.$datas['data']['utr'].')'
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

			$response = Cardoffer::where('id',$paymentData->userid)->update($data);

            $sent = sendPaymentGreetings($userData->first_name.' '.$userData->last_name, $userData->mobile, $userData->emailid);

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

    /* offer4 - phonepe */
    public function offer4(){
        $meta = selfApplyMeta();
        $products = Product::where('productslug',env('LA_OFFER_4'))->first();
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
        return view('loanAgent.offers.offer-4',compact('meta','productData'));
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
                $first_name = $inputs ['first_name'];
                $last_name = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }
            /* product Data */
            $products = Product::where('productslug', env('LA_OFFER_4'))->first();
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
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 8,//LA offer 4 or big offer
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
            //Log::info('order ID - ' .$orderid);
            $returnUrl = 'https://wisemudra.com/api/loan-agent/big-offer-response';
            $curlurl = "https://api.zaakpay.com/api/paymentTransact/V8";

            $firstname = ($first_name != "") ? $first_name : $email;
            //Log::info($curlurl);
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
            Log::info('zaakpay data - '. json_encode($zaakpayPostData));
            ksort($zaakpayPostData);
            $checksumData = "";
            foreach ($zaakpayPostData as $key => $value) {
                $checksumData .= $key . '=' . $value . '&';
            }

            $checksum = hash_hmac('sha256', $checksumData, env('ZAAKPAY_SECRET_KEY'));
            Log::info('checksum - '.$checksum);

            $zaakPayData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 10, // 11 - selfapply, 12 - loanagent
                'userid' => $offerId,
                'orderid' => $orderid,
                'orderamount' => $grandAmount,
                'ordernote' => $products->productname,
            );
            Log::info('zaakpay entry' . json_encode($zaakPayData));
            $response = ZaakpayEntry::create($zaakPayData);
            $html = view('pg.zaakpay-checkout-offer',compact('zaakpayPostData','checksum','curlurl'))->render();
            return response()->json(['type'=>'SUCCESS','message'=>'Please wait...We are redirecting to Payment Page','html'=>$html]);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(['type'=>'ERROR','message'=>'Something went wrong']);
        }
    }

    public function offer4Response(Request $request)
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
            Log::info($request->all());
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
                    'rec_date' => date('Y-m-d H:i:s'),
                    'orderamount' => $orderAmount,
                    'statuscode' => $responseCode,
                    'transactionid' => $txnId,
                    'paymentmode' => $paymentMode
                );
                Log::info('zaakPayData - ' . json_encode($zaakPayData));
                $response1 = ZaakpayEntry::where('id', $paymentData->id)->update($zaakPayData);
                if ($responseCode == 100 || $responseCode == 208 || /* $responseCode == 102 || */ $responseCode == 601) {
                    $cardno = random_code_num(16);
                    $userData = Cardoffer::where('id',$paymentData->userid)->first();
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
                    $sent = sendPaymentGreetings($userData->first_name.' '.$userData->last_name, $userData->mobile, $userData->emailid);
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
        }catch(\Exception $e){
            Log::info($e->getMessage());
            dd('Something went wrong.Please check Log');
        }
    }

    /* offer 5 - airpay */
    public function offer5()
    {
        $meta = selfApplyMeta();
        $products = Product::where('productslug', env('LA_OFFER_5'))->first();

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
                $buyerFirstName = $inputs['first_name'];
                $buyerLastName = $inputs['last_name'];
                $buyerPhone = $inputs['mobile'];
                $buyerEmail = $inputs['email'];
                $buyerCountry = 'India';
            }
            $alldata = $buyerAddress = $buyerCity = $buyerState = $amount = $buyerPinCode = $orderid = '';
            /* product Data */
            $products = Product::where('productslug', env('LA_OFFER_5'))->first();

            /* set amount of offer */
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);


            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == $buyerPhone) {
                    $grandAmount = 50;
                    break; // Exit the loop once a match is found
                }
            }

            $orderid = "APLive" . number_format(microtime(true) * 1000, 0, '.', '');
            $url = "https://payments.airpay.co.in/pay/index.php";
            $returnUrl = 'https://wisemudra.com/api/loan-agent/big-benefit-offer-response';

            $hiddenmod = "";

            $postData = array(
                "username" => env('AIRPAY_USERNAME'),
                "password" => env('AIRPAY_PASSWORD'),
                "secret" => env('AIRPAY_API_KEY'),
                "mercid" => env('AIRPAY_MERCHENT_ID'),
                "orderid" => $orderid,
                "url" => $url,
                "currency" => 356,
                "isocurrency" => 'INR',
                "amount" => $grandAmount,
                "buyerFirstName" => $buyerFirstName,
                "buyerLastName" => $buyerLastName,
                "buyerEmail" => $buyerEmail,
                "buyerPhone" => $buyerPhone,
                "buyerAddress" => '',
                "buyerCity" => '',
                "buyerState" => '',
                "buyerPinCode" => '',
                "backurl" => $returnUrl,
                "hiddenmod" => $hiddenmod,
                "buyerCountry" => $buyerCountry,
                "customvar" => $products->productname,
            );
            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $buyerPhone], // Search condition
                [ // Values to update or insert
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 10,//big benefit offer or LA offer 5
                    'first_name' => $buyerFirstName,
                    'last_name' => $buyerLastName,
                    'emailid' => $buyerEmail,
                    'amount' => $grandAmount,
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );
            // Get the ID of the updated or inserted record
            $record = DB::table('cardoffer')->where('mobile', $buyerPhone)->first();
            $offerId = $record->id;

            $airpayData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 22, //la offer 5 or big benefit offer
                'userid' => $offerId,
                'orderid' => $orderid,
                'orderamount' => $grandAmount,
                'ordernote' => $products->productname,
            );
            $response = AirpayEntry::create($airpayData);
            $html = view('pg.sendtoairpay',compact('postData','url'))->render();
            return response()->json(['type'=>'SUCCESS','message'=>'Please wait...We are redirecting to Payment Page','html'=>$html]);
        } catch(ValidationException $e){
            return response()->json(array('type' => 'ERROR', 'errors' => $e->errors()), 422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(array('type' => 'ERROR', 'message' => 'Something went wrong.'));
        }
    }

    public function offer5Response(Request $request){
        try{
            $inputs = $request->all();
            $meta = selfApplyMeta();

            $TRANSACTIONID = trim($inputs['TRANSACTIONID']);
            $APTRANSACTIONID = trim($inputs['APTRANSACTIONID']);
            $AMOUNT = trim($inputs['AMOUNT']);
            $TRANSACTIONSTATUS = trim($inputs['TRANSACTIONSTATUS']);
            $MESSAGE = trim($inputs['MESSAGE']);
            $ap_SecureHash = trim($inputs['ap_SecureHash']);
            $CHMOD = "";

            if (isset($inputs['CHMOD'])) {
                $CHMOD = trim($inputs['CHMOD']);
            }
            if (isset($inputs['CUSTOMVAR'])) {
                $CUSTOMVAR = trim($inputs['CUSTOMVAR']);

            } else {
                $CUSTOMVAR = "";
            }
            if ($TRANSACTIONSTATUS == 200) {
                $paymentData = AirpayEntry::where('orderid',$TRANSACTIONID)->first();

                $airpayData = array(
                    'rec_date' => date('Y-m-d H:i:s'),
                    'orderamount' => $AMOUNT,
                    'statuscode' => $TRANSACTIONSTATUS,
                    'transactionid' => $APTRANSACTIONID,
                    'paymentmode' => $CHMOD
                );

                $response1 = AirpayEntry::where('id',$paymentData->id)->update($airpayData);
                $userData = Cardoffer::where('id',$paymentData->userid)->first();

                $cardno = random_code_num(16);
                $data = array(
                    'rec_date' => date('Y-m-d H:i:s'),
                    'card_number' => $cardno,
                    'registration_date' => date('Y-m-d'),
                    'expiry_date' => date('Y-m-d', strtotime('+3 months')),
                    'amount' => $AMOUNT,
                    'paymentid' => $TRANSACTIONID,
                    'isActive' => 1
                );

                $response = Cardoffer::where('id', $paymentData->userid)->update($data);
                $sent = sendPaymentGreetings($userData->first_name.' '.$userData->last_name, $userData->mobile, $userData->emailid);
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
        } catch(\Exception $e){
            Log::info($e->getMessage());
            dd('Something went wrong.Please check Log');
        }
    }

    /* Offer 6 - cashfree */
    public function offer6(){
        $meta = selfApplyMeta();
        $products = Product::where('productslug', env('LA_OFFER_6'))->first();

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

    public function getOffer6(Request $request){
        try{
            $inputs = $request->all();
            Log::info(json_encode($inputs));
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
                $firstName = $inputs['first_name'];
                $lastName = $inputs['last_name'];
                $mobile = $inputs['mobile'];
                $email = $inputs['email'];
            }

            /* product Data */
            $products = Product::where('productslug', env('LA_OFFER_6'))->first();
            Log::info('Products - '. json_encode($products));
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
            Log::info('Grand Amount - '. $grandAmount);
            $orderid = 'order_' . number_format(microtime(true) * 1000, 0, '.', '');

            //$returnUrl = base_url('pay/cardofferreturn?orderid=' . $orderid);
            $returnUrl = route('api.loan.agent.offer6Response',['orderid'=>$orderid]);
            Log::info('return url - '. $returnUrl);
            if (env('CASHFREE_MODE') == "PROD") {
                $curlurl = 'https://api.cashfree.com/pg/orders';
                $paymode = 'production';
            } else {
                $curlurl = 'https://sandbox.cashfree.com/pg/orders';
                $paymode = 'sandbox';
            }

            $offerId = DB::table('cardoffer')->updateOrInsert(
                ['mobile' => $mobile], // Search condition
                [ // Values to update or insert
                    'rec_date' => date('Y-m-d H:i:s'),
                    'offerpage' => 32,//LA offer 6
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'emailid' => $email,
                    'amount' => $grandAmount,
                    'isCustomer' => 0,
                    'isActive' => 0,
                    'isDelete' => 0,
                ]
            );
            $record = DB::table('cardoffer')->where('mobile', $mobile)->first();
            $offerId = $record->id;
            Log::info('offerid - '.$offerId);
            $data_res = array(
                "order_id" => $orderid,
                "order_amount" => $grandAmount,
                "order_note" => $products->productname,
                "customer_id" => strval($offerId),
                "customer_name" => $firstName.' '.$lastName,
                "customer_phone" => $mobile,
                "customer_email" => $email,
                "returnUrl" => $returnUrl
            );
            Log::info('cashfree data - '. json_encode($data_res));
            $payUrl = getCashfreePaymentUrl($curlurl, $data_res);
            Log::info('payurl -  - '.json_encode($payUrl));
            if ($payUrl) {
                $pay_sess_url = $payUrl->payment_session_id;
            }

            $cashfreeData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => 32, // LA offer 6
                'userid' => $offerId,
                'orderid' => $orderid,
                'orderamount' => $grandAmount,
                'ordernote' => $products->productname,
            );
            Log::info(json_encode($cashfreeData));
            $res = CashfreeEntry::create($cashfreeData);
            $html = view('pg.cashfree-checkout',compact('pay_sess_url','paymode'))->render();
            Log::info($html);
            return response()->json(['type'=>'SUCCESS','message'=>'Please wait...We are redirecting to Payment Page', 'html'=>$html]);
        } catch(\Illuminate\Validation\ValidationException $e){
            Log::info('Validation Error');
            return response()->json(['type'=>'ERROR','errors'=>$e->errors()],422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(['type'=>'ERROR','message'=>'Something went wrong']);
        }
    }

    public function offer6Response(Request $request, $orderid){
        try{
            $inputs = $request->all();
            $meta = selfApplyMeta();
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            if (isset ($orderid)) {
                if (env('CASHFREE_MODE') == "PROD") {
                    $curlurl = 'https://api.cashfree.com/pg/orders/' . $orderid . '/payments';
                } else {
                    $curlurl = 'https://sandbox.cashfree.com/pg/orders/' . $orderid . '/payments';
                }
                $orderdata = getorderdata($curlurl);
                if ($orderdata) {
                    Log::info(json_encode($orderdata));
                    $orderid = $orderdata[0]->order_id;
                    $txstatus = $orderdata[0]->payment_status;
                    $referenceid = $orderdata[0]->cf_payment_id;
                    $paymentmode = $orderdata[0]->payment_group;
                    $orderamount = $orderdata[0]->order_amount;

                    $paymentData = CashfreeEntry::where('orderid', $orderid)->first();
                    $cashFreeData = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'referenceid' => $referenceid,
                        'txstatus' => $txstatus,
                        'paymentmode' => $paymentmode
                    );
                    CashfreeEntry::where('id',$paymentData->id)->update($cashFreeData);
                    if ($txstatus == 'SUCCESS') {
                        $userData = Cardoffer::where('id',$paymentData->userid)->first();
                        $cardno = random_code_num(16);
                        $data = array(
                            'rec_date' => date('Y-m-d H:i:s'),
                            'card_number' => $cardno,
                            'registration_date' => date('Y-m-d'),
                            'expiry_date' => date('Y-m-d', strtotime('+1 months')),
                            'amount' => $orderamount,
                            'paymentid' => $referenceid,
                            'isActive' => 1
                        );
                        $response = Cardoffer::where('id',$paymentData->userid)->update($data);
                        $sent = sendPaymentGreetings($userData->first_name.' '.$userData->last_name, $userData->mobile, $userData->emailid);
                        return view('cardoffer-response', [
                            'meta' => $meta,
                            'response' => TRUE,
                        ]);
                    } else if($txstatus == 'FAILED'){
                        Log::info('txstatus is FAILED');
                        return view('cardoffer-response', [
                            'meta' => $meta,
                            'response' => FALSE,
                        ]);
                    } else {
                        Log::info('nothing in txstatus');
                        return view('cardoffer-response', [
                            'meta' => $meta,
                            'response' => FALSE,
                        ]);
                    }
                } else {
                    Log::info('Order not found');
                    return view('cardoffer-response', [
                        'meta' => $meta,
                        'response' => FALSE,
                    ]);
                }
            } else {
                Log::info('orderid not found');
                return view('cardoffer-response', [
                    'meta' => $meta,
                    'response' => FALSE,
                ]);
            }
        } catch(\Exception $e){
            Log::info($e->getMessage());
            dd('Something went wrong.Please check Log');
        }
    }

}
