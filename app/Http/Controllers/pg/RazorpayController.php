<?php

namespace App\Http\Controllers\pg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RazorpayController extends Controller
{
    public function index(){
        $receiptid = number_format(microtime(true) * 1000, 0, '.', '');
        $orderdata = array(
            'amount' => 1 * 100,
            'currency' => 'INR',
            'receipt' => $receiptid,
            'notes' => array(
                'key1' => 'Vicky Jaiswal',
                'key2' => '8530537178'
            )
        );
        $orderres = generateRazorpayOrder($orderdata);
        dd($orderres);
        $successURL = route('razorpay-success');
		$failURL = url('razorpay-fail/1200');
		$postData = array(
		 'applyid' => 1200,
		 'fullname' => 'Vicky Jaiswal',
		 'mobile' => '8530537178',
		 'email' => 'vickyverloop@gmail.com',
		 'orderamount' => 1,
		 'orderid' => $orderres->id,
		 'description' => 'Hello World',
		 'successURL' => $successURL,
		 'failURL' => $failURL
		);
        return view('pg.razorpay',compact('postData'));
    }

    public function failUrl($key){
        dd('Payment Failed or maybe camcel the payment');
    }
    
    public function successUrl(Request $request){
        dd($request,'Payment Success');
    }
}
