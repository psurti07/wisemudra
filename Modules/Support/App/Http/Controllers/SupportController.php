<?php

namespace Modules\Support\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    public function support(){
        return view('support::support');
    }

    public function supportPost(Request $request){
        $inputs = $request->all();
        $request->validate([
            'issuetype' => 'required',
            'message' => 'required'
        ]);
        
        $ticketno = date('mdh').random_code_num(4);
        $cardno = DB::table('membership_orders')->select('card_number')->where('userid',Auth::user()->id)->first();
        
        $data = array(
            'rec_date' => date('Y-m-d H:i:s'),
            'ticketno' => $ticketno,
            'usertype' => 1,
            'firstname' => Auth::user()->first_name,
            'lastname' => Auth::user()->last_name,
            'mobile' => Auth::user()->mobile,
            'email' => Auth::user()->email,
            'cardnumber' => $cardno->card_number,
            'issuetype' => $request->input('issuetype'),
            'message' => $request->input('message'),
            'status' => 1,
            'isDelete' => 0
        );
        $result = SupportRequests::create($data);
        
        if($result){
            /* send ticket message starts */
            $msg = DB::table('sms_list')->where('type',3)->where('slug','ticket_raised')->first()->message;
            if($msg != '#'){
                $msg = str_ireplace('{#varticket#}',$ticketno,$msg);
                $senderId = DB::table('info_pages')->where('slug','common-senderid')->first()->content;
                sendDynamicSMS($senderId, $msg, Auth::user()->mobile, 'self');
            }
            /* send ticket message ends */

            $message = "Your request ticket has been raised in our system with the Ticket Id: ".$ticketno.". We will contact you within 24-48 hours for a follow-up.";
            return response()->json(array('type'=>'SUCCESS','message'=>$message));
        } else {
            return response()->json(array('type'=>'ERROR','message'=>'Ops! Something went wrong.'));
        }
    }
}
