<?php

namespace Modules\Dashboard\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Administrations;
use App\Models\ImportantUpdate;
use App\Models\Invoice;
use App\Models\LoanApplications;
use App\Models\MembershipOrder;
use App\Models\UserRegistration;
use App\Models\Product;
use App\Models\SiteOption;
use App\Models\ZaakpayEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /* dashboard index function  */
    public function dashboard(){
        $userId = Auth::user()->id;
        $plan = DB::table('membership_orders')->where('userid',$userId)->orderByDesc('id')->first();
        $personalLoan = DB::table('loan_applications')->where(['userid'=>$userId,'isDelete'=>0,'loan_type'=>1])->count();
        $businessLoan = DB::table('loan_applications')->where(['userid'=>$userId,'isDelete'=>0,'loan_type'=>2])->count();
        $referalusers = DB::table('user_tree as t')
            ->join('user_registrations as r','r.id','=','t.subuserid')
            ->where(['t.refferaltype'=>1,'t.refferaluserid'=>$userId,'r.isUser'=>2,'r.isActive'=>1,'r.isDelete'=>0])->count();
        $profile = DB::table('user_registrations')->where(['id'=>$userId,'isUser'=>2,'isActive'=>1,'isDelete'=>0])->first();
        $slug = $profile->acc_type == 1 ? 'self-apply' : 'loan-agent';
        $accmsg = DB::table('info_pages')->where('slug',$slug)->pluck('content')->first();
        $kycstatus = DB::table('user_documents')->select('isVerified')->where('userid',$userId)->first();

        $wheredate = "CAST(rec_date as DATE) <= DATE_ADD(CURDATE(),INTERVAL -185 DAY)";
        $wherestatus = "(status=2 or status=3)";
        $reapplystatus = 0;
        $agent = Administrations::select('id','fullname','mobile','emailid')->where('id',$profile->staff_id)->first();
        $query = DB::table('loan_applications')
            ->where('userid', $userId)
            ->whereRaw($wheredate)
            ->whereRaw($wherestatus)
            ->where('isDelete', 0)
            ->orderByDesc('rec_date')
            ->first();
        $camapignName = '';
        if(!empty($reapplystatus)){
            $reapplystatus = dateDiffInDays($reapplystatus->rec_date,'Y-n-d');
        }
        $camapignName = '';
        $impUpdates = ImportantUpdate::where('isActive',1)->where('isDelete',0)->get();
        //dd($impUpdates);
        /* manage message of plan expiration */
        $expiryDate = Carbon::parse($plan->expiry_date);
        $currentDateTime = Carbon::now();

        if ($currentDateTime->greaterThanOrEqualTo($expiryDate)) {
            if($profile->acc_type == 1){
                $message = 'Dear Customer, your Self-Apply Plan has been expired. We recommend you renew your plan to again access your portal and services.';
            } else {
                $message = 'Dear Customer, your Hire Agent Plan has been expired. We recommend you renew your plan to again access your portal, services and expert consultation without interruption.';
            }
        } elseif ($currentDateTime->diffInHours($expiryDate) <= 48) {
            if($profile->acc_type == 1){
                $message = 'Dear Customer, your Self-Apply Plan will expire in 48 hours. We recommend you renew your plan before expiry to access your portal and services without interruption.';
            } else {
                $message = 'Dear Customer, your Hire Agent Plan will expire in 48 hours. We recommend you renew your plan before expiry to access your portal, services and expert consultation without interruption.';
            }
        } else {
            $message = NULL;
        }
        $appId = DB::table('loan_applications')->where('userid',$userId)->first()->id;
        $showMessage = false;
        if(Auth::user()->acc_type == 2){
            $latestRemark = DB::table('application_remarks')
                ->select(DB::raw('DATE(entry_at) as entryAt'))
                ->where('application_id', $appId)
                ->orderByDesc('id')
                ->first();
            
            $latestRemarkDate = $latestRemark ? $latestRemark->entryAt : null;
            
            if ($latestRemarkDate) {
                $entryDate = Carbon::parse($latestRemarkDate)->startOfDay();
                $today = Carbon::today();
                $diffInDays = $entryDate->diffInDays($today, false); // false to include future
            
                // Show message only if the remark was added today or within the next 2 days
                if ($diffInDays >= 0 && $diffInDays <= 2) {
                    $showMessage = true;
                }
            }
        }
        return view('dashboard::index',compact('impUpdates','personalLoan','businessLoan','referalusers','profile','accmsg','kycstatus','reapplystatus','agent','plan','camapignName','message','showMessage','appId'));
    }

    /* open license agreement page when customer visit first time */
    public function licenseAgreement(){
        if(Auth::user()->iAgree == 0){
            return redirect()->route('customer.dashboard');
        }
        return view('dashbaord::licenseAgreement');
    }

    /* accept the license agreement through checkbox */
    public function acceptAgreement(Request $request){
        $inputs = $request->all();
        $request->validate([
            'agree' => 'required'
        ],[
            'agree.required' => 'Please check the checkbox of I Agree'
        ]);
        $result = UserRegistration::where('id',$inputs['customerid'])->update(['iAgree'=>$inputs['agree']]);
        if($result){
            return response()->json(array('type'=>'SUCCESS','message'=>''));
        } else {
            return response()->json(array('type'=>'ERROR','message'=>'Ops! Something went wrong.'));
        }
    }

    /* logout the session from dashboard */
    public function logout(){
        Session::flush();
        Auth::logout();
        return redirect('customer/login');
    }

    /* Plan renewal function starts */
    public function renewPlan(){
        $loanApplicationDetails = LoanApplications::where('userid',Auth::user()->id)->orderByDesc('id')->first();
        $membershipDetails = MembershipOrder::where('userid',Auth::user()->id)->orderByDesc('id')->first();
        $planOne = Product::where('productslug','self-apply')->first();
        $planTwo = Product::where('productslug','hire-loan-agent')->first();
        $planThree = Product::where('productslug','loan-assistant')->first();
        return View('dashboard::renewPlan', compact('loanApplicationDetails','membershipDetails','planOne','planTwo','planThree'));
    }

    /* renew plan post */
    public function renewalPlanStore(Request $request){
        try{
            $inputs = $request->all();
            $request->validate([
                'loan_amount' => 'required',
                'monthly_income' => 'required',
                'currentemi' => 'required',
                'loan_purpose' => 'required',
            ]);
            $loanApp = [
                'rec_date' => date('Y-m-d h:i:s'),
                'userid' => Auth::user()->id,
                'loan_amount' => $inputs['loan_amount'],
                'user_type' => $inputs['user_type'],
                'loan_type' => $inputs['loan_amount'] > 500000 ? 2 : 1,
                'monthly_income' => $inputs['monthly_income'],
                'loan_purpose' => $inputs['loan_purpose'],
                'application_number' => random_code(8),
            ];

            //$res1 = LoanApplications::create($loanApp);
            $productslug = (($inputs['payment_method'] == 3) ? 'loan-assistant' : (($inputs['payment_method'] == 2) ? 'hire-loan-agent' : 'self-apply'));
            $entryFor = (($inputs['payment_method'] == 2) ? 12 : 11);
            $productData = Product::where('productslug', $productslug)->first();
            $amount = ($productData->inOffer == 1) ? $productData->offeramount : $productData->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == Auth::user()->mobile) {
                    $grandAmount = 1;
                    break; // Exit the loop once a match is found
                }
            }
            $orderId = "ZPLAT" . number_format(microtime(true) * 1000, 0, '.', '');
            $returnUrl = route('api.customer.plan-upgrade');
            
            /*zaakpay code starts*/
            if (config('constant.ZAAKPAY_ENV') == "PRODUCTION") {
                $curlurl = "https://api.zaakpay.com/api/paymentTransact/V8";
            } else {
                $curlurl = "https://zaakstaging.zaakpay.com/api/paymentTransact/V8";
            }
            
            $firstname = Auth::user()->first_name;
            $lastname = Auth::user()->last_name;
            $zaakpayPostData = array(
                "merchantIdentifier" => config('constant.ZAAKPAY_MERCHANT_IDENTIFIER'),
                "orderId" => $orderId,
                "returnUrl" => $returnUrl,
                "currency" => 'INR',
                "amount" => $grandAmount * 100,
                "buyerEmail" => Auth::user()->email,
                "buyerFirstName" => $firstname,
                "buyerLastName" => $lastname,
                "buyerPhoneNumber" => Auth::user()->mobile,
                "buyerCountry" => 'India',
                "productDescription" => $productData->productname,
            );
            
            ksort($zaakpayPostData);
            $checksumData = "";
            foreach ($zaakpayPostData as $key => $value) {
                $checksumData .= $key . '=' . $value . '&';
            }

            $checksum = hash_hmac('sha256', $checksumData, config('constant.ZAAKPAY_SECRET_KEY'));

            $zaakPayData = array(
                'rec_date' => date('Y-m-d H:i:s'),
                'entryfor' => $entryFor,
                'userid' => Auth::user()->id,
                'orderid' => $orderId,
                'orderamount' => $grandAmount,
                'ordernote' => $productData->productname,
            );
            $response = ZaakpayEntry::create($zaakPayData);
            $html = view('dashboard::zaakpay-checkout',compact('zaakpayPostData', 'checksum', 'curlurl'))->render();
            return response()->json(array('type'=>'SUCCESS','message'=>'Please wait...We are redirecting to Payment Page','html'=>$html));
        } catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['type'=>'ERROR', 'errors'=>$e->errors()],422);
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(['type'=>'ERROR','message'=>'Ops! Something went wrong.']);
        }
    }

    /* upgrade plan */
    public function upgradePlan(Request $request){
        try{
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
            $orderId = $request->input('orderId');
            $responseCode = $request->input('responseCode');
            $orderAmount = $request->input('amount') / 100;
            $txnId = $request->input('pgTransId');
            $paymentMode = $request->input('paymentMode');
            $recd_checksum = $request->input('checksum');
            $plan = $request->input('product1Description');

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

            $checksum = hash_hmac('sha256', $checksumData, config('constant.ZAAKPAY_SECRET_KEY'));
            // if ($checksum == $recd_checksum) {
                $paymentData = ZaakpayEntry::where('orderid', $orderId)->first();

                //Log::info('paymentData - '.json_encode($paymentData));

                $zaakPayData = array(
                    'rec_date' => date('Y-m-d H:i:s'),
                    'orderamount' => $orderAmount,
                    'statuscode' => $responseCode,
                    'transactionid' => $txnId,
                    'paymentmode' => $paymentMode
                );
                //Log::info('zaakPayData - '. json_encode($zaakPayData));
                $response1 = ZaakpayEntry::where('id', $paymentData->id)->update($zaakPayData);
                if ($responseCode == 100) {
                    $cardno = random_code_num(16);
                    $membershipData = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'userid' => $paymentData->userid,
                        'registration_date' => date('Y-m-d'),
                        'expiry_date' => $plan == 2 ? date('Y-m-d', strtotime('+3 months')) : date('Y-m-d', strtotime('+1 months')),
                        'card_number' => $cardno,
                        'amount' => $orderAmount,
                        'paymentid' => $txnId,
                        'isActive' => 1,
                        'isDelete' => 0
                    );

                    $membershipId = MembershipOrder::create($membershipData)->id;
                    $userData = UserRegistration::where('id',$paymentData->userid)->first();
                    $regData = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'update_date' => date('Y-m-d H:i:s'),
                        'acc_type' => $plan
                    );
                    $response2 = UserRegistration::where('id', $paymentData->userid)->update($regData);
                    $productslug = $plan == 2 ? 'hire-loan-agent' : 'self-apply';
                    $invprefix = $plan == 2 ? "LA_" : "SA_";

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
                    $invData3 = array(
                        'rec_date' => date('Y-m-d H:i:s'),
                        'userid' => $paymentData->userid,
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
                    $mailData = array(
                        'fullname' => $userData->first_name . ' ' . $userData->last_name,
                        'mobile' => $userData->mobile,
                        'email' => $userData->email,
                        'order_number' => $invoiceNo->option_value,
                        'order_date' => date('d-m-Y'),
                        'order_amount' => $grandtotal,
                        'transactionId' => $txnId,
                    );
                    $sendGreetings = view('mail.upgradePlan', $mailData)->render();
                    Log::info($sendGreetings);
                    Log::info("==================================================");
                    Log::info("==================================================");
                    Log::info("==================================================");
                    $invAttach = array_merge($invData3,
                        [
                            'fullname' => $userData->first_name . ' ' . $userData->last_name,
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
                    Log::info($invoiceData);
                    $pdf = Pdf::loadHTML($invoiceData)->setPaper('A4', 'portrait')->output();
                    sendBrevoHtmlMail2($mailData, 'Congratulations! Successful Plan Renewal for Wisemudra.', $sendGreetings, 3, $pdf);
                    return redirect("customer/dashboard")->with('success', 'Your plan has been successfully renewed!');
                } else {
                    Log::info('response code not success');
                    return redirect("customer/renew-plan");
                }
            // } else {
            //     Log::info('else checksum not matched');
            //     return redirect("customer/renew-plan");
            // }
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return redirect("customer/dashboard")->with('error', 'Payment Failed!');
        }
    }
    
    
    
    /* download report */
    public function downloadReport(){
        try{
            $userId = Auth::user()->id;
            $userData = UserRegistration::where('id',$userId)->select('first_name','last_name','staff_id','mobile','email','acc_type')->first();
            $cardId = LoanApplications::where('userid', $userId)->select('id')->whereRaw('rec_date >= DATE_ADD(CURDATE(),INTERVAL -180 DAY)')->where('isDelete',0)->orderByDesc('rec_date')->limit(1)->first()->id;
            $agent = Administrations::where('id',$userData->staff_id)->select('fullname','mobile','emailid')->first();
            $membershipData = MembershipOrder::where('userid',$userId)->select('registration_date','expiry_date','card_number')->first();
            $offers = optional(DB::table('user_offers')->where('userid', $userId)->first())->offerdata;
            $offers = json_decode($offers);
            $remarks = DB::table('application_remarks AS ar')
                ->select('ar.*','lr.title','lr.remarks','lr.statusid','admin.fullname')
                ->join('loanstatus_remarks AS lr','lr.id','=','ar.subject')
                ->join('administrations AS admin','admin.id','=','ar.staff_id')
                ->where('application_id',$cardId)
                ->orderBy('id','DESC')->get();
            $plan = $userData->acc_type == 1 ? 'Self Apply Plan' : 'Hire Agent Plan';
            $planCode = $userData->acc_type == 1 ? 11 : 12;
            $invoice = DB::table('invoices')->where('userid', $userId)->select('inv_prefix','inv_number')->first();
            return view('mail.dwReport',compact('userData','agent','membershipData','offers','remarks','plan','planCode','invoice','userId'));
        } catch(\Exception $e){
            Log::info('download report error - ' . $e->getMessage());
            dd('Oops!Something went wrong. Please contact your support.');
        }
    }
    
    public function knowledgeSection(){
        return view('dashboard::knowledgeSection');
    }
}
