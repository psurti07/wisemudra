<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

if(!function_exists('sendSingleSMS')){
    function sendSingleSMS($mobile, $otp, $panel = 'self'){
        $message = "Hello, the Wisemudra OTP for your mobile number registration is ".$otp.". Kindly do not share it with anyone. Thanks, wisemudra";
        // URL encode the message
       // URL encode the message
        $sms_text = urlencode($message);

        // Retrieve the SMS credentials from environment variables
        if ($panel == 'hire') {
    		$username = config('constant.SMS_OBB_LA_USERNAME');
            $password = config('constant.SMS_OBB_LA_PASSWORD');
            $sender_id = DB::table('info_pages')->where('slug','la-senderid-otp')->first()->content;
    	} else if ($panel == 'self') {
    		$username = config('constant.SMS_OBB_USERNAME');
            $password = config('constant.SMS_OBB_PASSWORD');
            $sender_id = DB::table('info_pages')->where('slug','sa-senderid-otp')->first()->content;
    	} else {
    		$username = config('constant.SMS_OBB_USERNAME');
            $password = config('constant.SMS_OBB_PASSWORD');
            $sender_id = config('constant.SMS_OBB_SENDER_ID');
    	}
	    // Construct the API URL
        $api_url = "http://m.onlinebusinessbazaar.in/sendsms.jsp?user={$username}&password={$password}&senderid={$sender_id}&mobiles={$mobile}&sms={$sms_text}";
        // $api_url = "43.204.206.165/sendsms.jsp?user={$username}&password={$password}&senderid={$sender_id}&mobiles={$mobile}&sms={$sms_text}";

        // Submit the request to the server
        $response = Http::get($api_url);

        // Return the response
        return [
            'status_code' => $response->status(),
            'body' => $response->body(),
        ];
    }
}

if(!function_exists('sendDynamicSMS')){
    function sendDynamicSMS($senderId, $message, $mobile, $panel = 'assistant', $type = '', $tempId = ''){
        // URL encode the message
        $sms_text = urlencode($message);

        // Retrieve the SMS credentials from environment variables
        if ($panel == 'hire') {
    		$username = config('constant.SMS_OBB_LA_USERNAME');
            $password = config('constant.SMS_OBB_LA_PASSWORD');
    	} else if ($panel == 'self') {
    		$username = config('constant.SMS_OBB_USERNAME');
            $password = config('constant.SMS_OBB_PASSWORD');
    	} else {
    		$username = env('SMS_OBB_LAT_USERNAME');
            $password = env('SMS_OBB_LAT_PASSWORD');
    	}
    	/*$sender_id = config('constant.SMS_OBB_SENDER_ID');*/

        // Construct the API URL
        if($type == 'forget-password'){
            $api_url = "http://m.onlinebusinessbazaar.in/sendsms.jsp?user={$username}&password={$password}&senderid={$senderId}&mobiles={$mobile}&sms={$sms_text}&tempid=1707174617771060776";
            //  $api_url = "43.204.206.165/sendsms.jsp?user={$username}&password={$password}&senderid={$senderId}&mobiles={$mobile}&sms={$sms_text}&tempid=1707174617771060776";
        } elseif($tempId!=''){
            $api_url = "http://m.onlinebusinessbazaar.in/sendsms.jsp?user={$username}&password={$password}&senderid={$senderId}&mobiles={$mobile}&sms={$sms_text}&tempid={$tempId}";
            // $api_url = "43.204.206.165/sendsms.jsp?user={$username}&password={$password}&senderid={$senderId}&mobiles={$mobile}&sms={$sms_text}&tempid={$tempId}";
        } else {
            $api_url = "http://m.onlinebusinessbazaar.in/sendsms.jsp?user={$username}&password={$password}&senderid={$senderId}&mobiles={$mobile}&sms={$sms_text}";
            // $api_url = "43.204.206.165/sendsms.jsp?user={$username}&password={$password}&senderid={$senderId}&mobiles={$mobile}&sms={$sms_text}";
        }
        
        // Submit the request to the server
        $response = Http::get($api_url);

        // Log::info($response);
        // Return the response
        return [
            'status_code' => $response->status(),
            'body' => $response->body(),
        ];
    }
}

if(!function_exists('sendDynamicXMLSMS')){
    function sendDynamicXMLSMS($dataset){
        $xmldataset = "<?xml version='1.0'?><smslist>".$dataset.'</smslist>';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'http://m.onlinebusinessbazaar.in/sendsms.jsp?',
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_ENCODING => 'UTF-8',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $xmldataset,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/xml',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        /*if ($err) {
            dd('cURL Error #:'.$err);
        } else {
            return $response;
        }*/
        return $response;
    }
}
