<?php

namespace App\Http\Controllers\pg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Utilities\Authuntication;

class SabpaisaController extends Controller
{
    public function index(){
        return view('pg.sbpDetails');    
    }
    
    public function initiatePayment(Request $request)
    {
        session_start();

        $encData = null;
        $inputs = $request->all();
        // $clientCode = 'DCRBP'; 
        // $username = 'userph.jha_3036'; 
        // $password = 'DBOI1_SP3036'; 
        // $authKey = '0jeOYcu3UnfmWyLC'; 
        // $authIV = 'C28LAmGxXTqmK0QJ'; 
        
        $clientCode = 'ARVI84'; 
        $username = 'payment_17241'; 
        $password = 'ARVI84_SP17241'; 
        $authKey = 'gItHWu1yIxhDpD93'; 
        $authIV = 'N5KQy4JdEgxWYz7Y'; 

        $payerName = $inputs['payer_name'];
        $payerEmail = $inputs['payer_email'];
        $payerMobile = $inputs['payer_mobile'];
        $payerAddress = $inputs['payer_address'];

        $clientTxnId = rand(1000, 9999);
        $amount = floor($inputs['amount']);
        $amountType = 'INR';
        $mcc = 5137;
        $channelId = 'W';
        $callbackUrl = route('payment-response');
        
        $encData = "?clientCode=".$clientCode."&transUserName=".$username."&transUserPassword=".$password."&payerName=".$payerName.
            "&payerMobile=".$payerMobile."&payerEmail=".$payerEmail."&payerAddress=".$payerAddress."&clientTxnId=".$clientTxnId.
            "&amount=".$amount."&amountType=".$amountType."&mcc=".$mcc."&channelId=".$channelId."&callbackUrl=".$callbackUrl;
        
        $AesCipher = new Authuntication();
        $data = $AesCipher->encrypt($authKey, $authIV, $encData);
        
        return view('pg.pay', [
            'data' => $data,
            'clientCode' => $clientCode,
        ]);
    }
}   
