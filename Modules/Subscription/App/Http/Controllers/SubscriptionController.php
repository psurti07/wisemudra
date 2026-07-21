<?php

namespace Modules\Subscription\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MembershipOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index()
    {
        $lists = MembershipOrder::where('userid',Auth::user()->id)->orderByDesc('id')->get();
        return view('subscription::index',compact('lists'));
    }
    
    public function invoice(){
        $invoice = DB::table('invoices')->where('userid',Auth::user()->id)->first();
        $user = DB::table('user_registrations')->select(DB::raw('CONCAT(first_name, " ", last_name) as fullname'),'city','email','mobile','acc_type','state')->where('id', Auth::user()->id)->first();
        $membership = DB::table('membership_orders')->select('card_number','registration_date','expiry_date','paymentid')->where('userid',Auth::user()->id)->first();
        $invAttach = array_merge((array)$invoice, (array)$user, (array)$membership, ['isCustomer'=>1]);
        return view('mail.invoice', $invAttach);
    }

}
