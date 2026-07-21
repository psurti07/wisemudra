<?php

namespace Modules\Loans\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Administrations;
use App\Models\UserDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoansController extends Controller
{
   /* pre Approved loan */
    public function preApprovedLoans(){
        $appDetails = DB::table('loan_applications')->where('userid', Auth::user()->id)->where('isDelete',0)->orderByDesc('id')->first();
        $offers = DB::table('user_offers')->where('userid',Auth::user()->id)->first();
        $eligibilityAmt = calEligiblity($appDetails->monthly_income, $appDetails->currentemi, (($appDetails->loan_type == 2) ? 11.5 : 12.5), $appDetails->loan_amount);
        if(!$offers){
            $jsonData = offersBankList($appDetails->monthly_income, $appDetails->user_type, $eligibilityAmt);
            $resId = DB::table('user_offers')->insertGetId([
                'rec_date' => Carbon::now(),
                'userid' => Auth::user()->id,
                'offerdata' => $jsonData
            ]);
            $offers = DB::table('user_offers')->where('id', $resId)->first();
        }
        $offers = json_decode($offers->offerdata);
        $membership = DB::table('membership_orders')->where('userid', Auth::user()->id)->orderByDesc('id')->limit(1)->first();
        $expiryDate = Carbon::parse($membership->expiry_date);
        $currentDateTime = Carbon::now();
        if ($currentDateTime->greaterThanOrEqualTo($expiryDate)) {
            if(Auth::user()->acc_type == 1){
                $message = 'Dear Customer, your Self-Apply Plan has been expired. We recommend you renew your plan to again access your portal and services.';
            } else {
                $message = 'Dear Customer, your Hire Agent Plan has been expired. We recommend you renew your plan to again access your portal, services and expert consultation without interruption.';
            }
        } elseif ($currentDateTime->diffInHours($expiryDate) <= 48) {
            if(Auth::user()->acc_type == 1){
                $message = 'Dear Customer, your Self-Apply Plan will expire in 48 hours. We recommend you renew your plan before expiry to access your portal and services without interruption.';
            } else {
                $message = 'Dear Customer, your Hire Agent Plan will expire in 48 hours. We recommend you renew your plan before expiry to access your portal, services and expert consultation without interruption.';
            }
        } else {
            $message = NULL;
        }
        
        return view('loans::preApprovedLoan',compact('appDetails','offers','membership','message'));
    }

    /* loan history */
    public function loanHistory(){
        $custId = Auth::user()->id;
        $loanHistory = DB::table('loan_applications')->whereRaw('rec_date >= DATE_ADD(CURDATE(),INTERVAL -180 DAY)')
            ->where('userid',$custId)->where('isDelete',0)->orderByDesc('rec_date')->get();
        return view('loans::loan-history',compact('loanHistory'));
    }

    /* loan details */
    public function loanDetails($appId){
        $custId = Auth::user()->id;
        $agentDetails = Administrations::where('id',Auth::user()->staff_id)->first();
        $user_docs = UserDocument::where('userid',$custId)->first();
        $appDetails = DB::table('loan_applications')->where('id', decrypt($appId))->where('isDelete',0)->orderBy('id')->first();
        $statusList = DB::table('loan_application_status as s')->selectRaw('s.*, b.bank_name, l.statusname, l.colorClass')
            ->join('banks as b','b.id','=','s.bankid')->join('loanstatus as l','l.id','=','s.statusid')
            ->where('s.isDelete',0)->where('s.applicationid',decrypt($appId))->orderByDesc('s.statusdate')->get();
        $remarks = '';
        if(Auth::user()->acc_type == 2){
            $remarks = DB::table('application_remarks AS ar')
                            ->select('ar.*','lr.title','lr.remarks','lr.statusid','admin.fullname')
                            ->join('loanstatus_remarks AS lr','lr.id','=','ar.subject')
                            ->join('administrations AS admin','admin.id','=','ar.staff_id')
                            ->where('application_id',decrypt($appId))
                            ->orderBy('id','DESC')->get();
        }
        
        return view('loans::myLoanAppDetails',compact('user_docs','appDetails','statusList','agentDetails','remarks'));
    }

    public function faircentApplyNow(Request $request){
        $userData = [
            'first_name' => Auth::user()->first_name,
            'last_name' => Auth::user()->last_name,
            'dob' => Auth::user()->dob,
            'city' => Auth::user()->city,
            'state' => Auth::user()->state,
            'pincode' => Auth::user()->pincode,
            'mobile' => Auth::user()->mobile,
            'pancard' => Auth::user()->pancard,
            'email' => Auth::user()->email,
            'user_type' => 1,
            'monthly_income' => '70000-85000',
            'loan_amount' => '500000',
            'sign_ip' => '192.168.1.30',
        ];
        Log::info($userData);
        $res = createNewApplication($userData);
        dd($res);
    }
    
    public function applyNow(Request $request){
        try{
            if($request->has('apply_id') && $request->has('apply_url')){
                $userId = Auth::user()->id;
                $applyId = $request->apply_id;
                
                $res = DB::table('click_counts')
                    ->where('user_id',Auth::user()->id)
                    ->where('applylink_id',$request->apply_id)
                    ->first();
                    
                /* create the record */
                $result = DB::table('click_counts')->insert([
                    'rec_date' => Carbon::now(),
                    'user_id' => $userId,
                    'applylink_id' => $applyId,
                    'counts' => 1,
                ]);
                if($result){
                    return response()->json(['type'=>'SUCCESS','message'=>'success']);
                } else {
                    return response()->json(['type'=>'ERROR','message'=>'Ops! Something went wrong.']);
                }
            } else {
                return response()->json(['type'=>'ERROR','message'=>'Ops! Perform invalid action.']);
            }
        } catch(\Exception $e){
            Log::info($e->getMessage());
            return response()->json(['type'=>'ERROR','message'=>'Ops! Something went wrong.']);
        }
    }
}
