<?php

namespace App\Http\Controllers\pg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayuController extends Controller
{
    public function index(){
        return view('pg.payu');
    }

    public function payuCheckout(Request $request){
        $data = $request->all();
        Log::info(json_encode($data));
        $amount = $data['amount'];
        $grandamount = $amount + ($amount * 0.18);
        /*$uat_numbers = unserialize(env('UAT_MOBILE_NUMBERS'));
        foreach ($uat_numbers as $uat_num) {
            if($uat_num == $userdata->mobile) {
                $grandamount = 1;
            }
        }*/
        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        $udf1 = $udf2 = $udf3 = $udf4 = $udf5 = '';
        $postData = array();

        $hashstring = env('PAYU_MERCHANT_KEY') . '|' . $txnid . '|' . $grandamount . '|' . $data['product'] . '|' . $data['fullname'] . '|' . $data['email'] . '|' . $udf1 . '|' . $udf2 . '|' . $udf3 . '|' . $udf4 . '|' . $udf5 . '||||||' . env('PAYU_SALT');

        $hash = hash('sha512', $hashstring);

        $returnUrl = route('payu.callbackUrl');

        if(env('PAYU_MODE') == "PROD") {
            $url = 'https://secure.payu.in/_payment';
        } else {
            $url = 'https://test.payu.in/_payment';
        }

        $postData = array(
            'mkey' => env('PAYU_MERCHANT_KEY'),
            'tid' => $txnid,
            'hash' => $hash,
            'amount' => $grandamount,
            'name' => $data['fullname'],
            'productinfo' => $data['product'],
            'mailid' => $data['email'],
            'phoneno' => $data['mobile'],
            'address' => '',
            'action' => $url,
            'returnUrl' => $returnUrl
        );
        Log::info(json_encode($postData));
        return view('pg.payu-checkout', compact('postData'));
    }

    public function callbackURL(Request $request){
        Log::info('callback - '. json_encode($request->all()));
    }
}
