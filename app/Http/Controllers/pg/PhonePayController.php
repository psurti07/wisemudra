<?php

namespace App\Http\Controllers\pg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class PhonePayController extends Controller
{
    public function index(){
        return view('pg.phonepe');
    }
    public function phonePe(Request $request)
    {
    
        $orderid = number_format(microtime(true) * 1000, 0, '.', '');
        $returnUrl = route('response');
        $callbackUrl = route('response1');
        
        if(env('PHONEPE_ENV') == "PRODUCTION") {
            $curlurl = 'https://api.phonepe.com/apis/hermes/pg/v1/pay';
        } else {
            $curlurl = 'https://api-preprod.phonepe.com/apis/hermes/pg/v1/pay';
        }
        /* post Data */
        $data_res = array(
            "merchantId" => env('PHONEPE_MERCHANT_ID'),
            "merchantTransactionId" => strval($orderid),
            "merchantUserId" => "Test1",
            "amount" => strval($request->amount) * 100,
            "redirectUrl" => $returnUrl,
            "redirectMode" => "POST",
            "callbackUrl" => $callbackUrl,
            "mobileNumber" => strval($request->mobile),
            "paymentInstrument" => array(
                "type" => "PAY_PAGE",
            ),
        );
        
        $payurl = getpaymenturl($curlurl, env('PHONEPE_SALT_KEY'), env('PHONEPE_SALT_INDEX'), $data_res);
        if($payurl) {
			if($payurl->data->instrumentResponse->redirectInfo->url) {
				header("location:".$payurl->data->instrumentResponse->redirectInfo->url);
				die;
            }
        } else {
            dd($payurl);
        }
    }

    public function response1(Request $request)
    {
        dd($request);
    }

    public function response(Request $request){
        dd($request);
    }

}
