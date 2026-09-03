<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\InfoPages;
use App\Models\SupportRequests;

class LegalController extends Controller
{
    public function privacyPolicy(){
        $meta = privacyPolicyMeta();
        $mainTitle = 'Privacy Policy';
        $description = InfoPages::select('content')->where('slug','privacy-policy')->first()->content;
        return view('front.legalPages',compact('meta','description', 'mainTitle'));
    }

    public function termsConditions(){
        $meta = termsConditionsMeta();
        $mainTitle = 'Terms & Conditions';
        $description = InfoPages::select('content')->where('slug','terms-conditions')->first()->content;
        return view('front.legalPages',compact('meta','description', 'mainTitle'));
    }

    public function refundPolicy(){
        $meta = refundPolicyMeta();
        $mainTitle = 'Cancellation & Refund Policy';
        $description = InfoPages::select('content')->where('slug','refund-policy')->first()->content;
        return view('front.legalPages',compact('meta','description', 'mainTitle'));
    }

    public function disclaimer(){
        $meta = disclaimerMeta();
        $mainTitle = 'Disclaimer';
        $description = InfoPages::select('content')->where('slug','disclaimer')->first()->content;
        return view('front.legalPages',compact('meta','description', 'mainTitle'));
    }

    public function raiseRequest(){
        $meta = raiseRequestMeta();
        return view('front.raiseRequest',compact('meta'));
    }

    public function requestRaisedPost(Request $request){
        $inputs = $request->all();
        $request->validate([
            'usertype' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'mobile' => ['required','numeric', 'regex:/^[6-9]\d{9}$/'],
            'issuetype' => 'required',
            'message' => 'required'
        ]);

        $newInputs = [
            'ticketno' => $request->input('ticketno'),
            'rec_date' => date('Y-m-d H:i:s'),
            'serverip' => $request->ip(),
            'usertype' => $request->input('usertype'),
            'firstname' => ucfirst(trim($request->input('firstname'))),
            'lastname' => ucfirst(trim($request->input('lastname'))),
            'email' => strtolower(trim($request->input('email'))),
            'mobile' => $request->input('mobile'),
            'issuetype' => $request->input('issuetype'),
            'message' => $request->input('message'),
        ];

        $result = SupportRequests::create($newInputs);
        
        if($result){
            /* send ticket message starts */
            $msg = DB::table('sms_list')->where('type',3)->where('slug','ticket_raised')->first()->message;
            if($msg!='#'){
                $msg = str_ireplace('{#varticket#}',$request->input('ticketno'),$msg);
                $senderId = DB::table('info_pages')->where('slug','common-senderid')->first()->content;
                sendDynamicSMS($senderId, $msg, $request->input('mobile'), 'self');
            }
            /* send ticket message ends */
            
            $message = 'Your request ticket has been raised in our system with the Ticket Id: '.$request->input("ticketno").'. We will contact you within 24-48 hours for a follow-up.';
            return response()->json(array('type' => 'SUCCESS', 'message' => $message, 'data' => $result));
        } else {
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.', 'data' => []));
        }
    }
}
