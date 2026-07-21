<?php

namespace App\Http\Controllers\pg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Log;

class LyraPgController extends Controller
{
    public function index(){
        return view('pg.lyra');
    }
    
    public function lyraProcess(/*Request $request*/){
        $orderid = number_format(microtime(true) * 1000, 0, '.', '');
		$returnUrl = route('lyra.response');
		
		$curlurl = "https://api.in.lyra.com/pg/rest/v1/charge";
		
		$postData = array(
			"orderId" => $orderid,
			"currency" => 'INR',
			"amount" => 1 * 100,
			"orderInfo" => 'Self Apply',
			"maxAgeInHours" => '240',
			"customer" => array(
				// "uid" => $inputs['uid'],
				// "name" => $inputs['fullname'],
				// "emailId" => $inputs['email'],
				// "phone" => $inputs['mobile']
				"uid" => rand(1000,9999),
				"name" => 'User'.rand(100,999),
				"emailId" => 'user'.rand(100,999).'@yopmail.com',
				"phone" => '96'.rand(10000000,99999999)
			),
			"webhook" => array(
				"url" => $returnUrl
			),
			"return" => array(
				"method" => 'POST',
				"url" => $returnUrl,
				"timeout" => '600'
			)
		);
		Log::info("------------------------ Lyra Log -------------------------------");
		Log::info('postData - '. json_encode($postData));
		$payurl = getlyrapaymenturl($curlurl, $postData);
		Log::info('return Response - '. json_encode($payurl));
		if ($payurl) {
			if ($payurl->paymentLink) {
				header("location:" . $payurl->paymentLink);
				die;
			} else {
				dd('getting error');
				die;
			}
		} else {
			dd('getting error');
			die;
		}
    }
    
    public function lyraResponse(Request $request){
        Log::info('lyra Response - '. json_encode($request));
        dd('--- check log ---');
    }
}
