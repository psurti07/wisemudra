<?php

use App\Models\OtpVerification;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use \App\Events\SendOtpOBB;
use Illuminate\Support\Facades\Http;

if(!function_exists('random_code_num')) {
    function random_code_num( $length = 6 ) {
        $chars = "01234567890123456789";
        $code = substr( str_shuffle( $chars ), 0, $length );
        return $code;
    }
}

if(!function_exists('random_code')){
    function random_code($length = 6){
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789abcdefghijklmnopqrstuvwxyz";
        $code = substr( str_shuffle( $chars ), 0, $length );
        return $code;
    }
}

if(!function_exists('generateOtp')) {
    function generateOtp( $data, $acctype) {
        try{
            $mobile = $data;
            $otpCode = rand(1000,9999);
            $otpDetails = [
                'rec_date' => date('Y-m-d H:i:s'),
                'mobile' => $mobile,
                'email' => '',
                'otp' => ($mobile == env('STATIC_NO')) ? 1111 : $otpCode,
                'acc_type' => (int)$acctype
            ];
            $result = OtpVerification::create($otpDetails);
            if($result){
                if($mobile == env('STATIC_NO')){
                    return TRUE;
                } else {
                    event(new SendOtpOBB([
                    'phone' => $mobile,
                    'otp' => $otpCode,
                    'panel' => (($acctype == 3) ? 'assistant' : (($acctype == 2) ? 'hire' : 'self'))
                ]));
                return TRUE;
                }

            }
        } catch(\Exception $e){
            Log::info($e->getMessage());
            dd($e->getMessage());
        }
    }
}

if(!function_exists('getStateOption')){
    function getStateOption($selected_state = ''){
        $states = array("Andaman and Nicobar Islands"=>"Andaman and Nicobar Islands",
            "Andhra Pradesh"=>"Andhra Pradesh",
            "Arunachal Pradesh"=>"Arunachal Pradesh",
            "Assam"=>"Assam",
            "Bihar"=>"Bihar",
            "Chandigarh"=>"Chandigarh",
            "Chhattisgarh"=>"Chhattisgarh",
            "Dadra and Nagar Haveli"=>"Dadra and Nagar Haveli",
            "Daman and Diu"=>"Daman and Diu",
            "Delhi"=>"Delhi",
            "Goa"=>"Goa",
            "Gujarat"=>"Gujarat",
            "Haryana"=>"Haryana",
            "Himachal Pradesh"=>"Himachal Pradesh",
            "Jammu and Kashmir"=>"Jammu and Kashmir",
            "Jharkhand"=>"Jharkhand",
            "Karnataka"=>"Karnataka",
            "Kerala"=>"Kerala",
            "Ladakh"=>"Ladakh",
            "Lakshadweep"=>"Lakshadweep",
            "Madhya Pradesh"=>"Madhya Pradesh",
            "Maharashtra"=>"Maharashtra",
            "Manipur"=>"Manipur",
            "Meghalaya"=>"Meghalaya",
            "Mizoram"=>"Mizoram",
            "Nagaland"=>"Nagaland",
            "Odisha"=>"Odisha",
            "Puducherry"=>"Puducherry",
            "Punjab"=>"Punjab",
            "Rajasthan"=>"Rajasthan",
            "Sikkim"=>"Sikkim",
            "Tamil Nadu"=>"Tamil Nadu",
            "Telangana"=>"Telangana",
            "Tripura"=>"Tripura",
            "Uttar Pradesh"=>"Uttar Pradesh",
            "Uttarakhand"=>"Uttarakhand",
            "West Bengal"=>"West Bengal");
        $option = '';
        foreach($states as $key=>$value){
            $option .= "<option ";
            $option .= " value=\"".$value."\"";
            if ( $selected_state == $value ) {
                $option .= " selected";
            }
            $option .= " >";
            $option .= $key;
            $option .= "</option>";
        }
        return $option;
    }
}

if(!function_exists('formatePrice')) {
    function formatePrice($price)
    {
        if (is_numeric($price)) {
            return number_format($price, 0);
        } else {
            if (empty($price)) {
                return "0.00";
            } else {
                return $price;
            }
        }
    }
}

if(!function_exists('formatePriceIndia')) {
    function formatePriceIndia($num, $decimal = 1) {
        $explrestunits = "";
        $num = preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $des = "00";

        if (count($words) <= 2) {
            $num = $words[0];
            if (count($words) >= 2) {
                $des = $words[1];
            }
            if (strlen($des) < 2) {
                $des = "$des";
            } else {
                $des = substr($des, 0, 2);
            }
        }
        if (strlen($num) > 3) {
            $lastthree = substr($num, strlen($num) - 3, strlen($num));
            $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
            $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for ($i = 0; $i < sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if ($i == 0) {
                    $explrestunits .= (int)$expunit[$i] . ","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i] . ",";
                }
            }
            $thecash = $explrestunits . $lastthree;
        } else {
            $thecash = $num;
        }

        if ($decimal == 0) {
            return $thecash;
        } else {
            return $thecash . "." . $des;
        }
    }
}

if (!function_exists('getPostalDetailsByPincode')) {
    function getPostalDetailsByPincode($pincode) {
        $curl = curl_init(); 
        curl_setopt_array($curl, [ 
            CURLOPT_URL => 'https://geoloc.in/api/pincode',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(['pincode' => $pincode]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer '.env('GEOLOC_KEY')
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
       
        $data = json_decode($response);
        
        if ($data->status == "success") {
            return [
                'city' => $data->data[0]->cityname,
                'state' => $data->data[0]->statename
            ];
        } else {
            return ['error' => 'Invalid Pincode'];
        } 
    }
}

/* phone pay payment gateway testing */
if(!function_exists('getpaymenturl')){
    function getpaymenturl($peurl, $key, $keyindex, $data){
        $data_json = json_encode($data);
        $data_base64 = base64_encode($data_json);
        $data_sha256 = hash('sha256', ($data_base64."/pg/v1/pay".$key));
        $data_xvalue = $data_sha256."###".$keyindex;

        $post_data = array();

        $data_req1 = array(
            "request" => $data_base64
        );
        $data_req2 = json_encode($data_req1);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $peurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>  $data_req2,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-VERIFY: ".$data_xvalue,
                "accept: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }
}

// airpay pg
if(!function_exists('calculateChecksum')){
    function calculateChecksum($data, $secret_key) {
        $checksum = md5($data.$secret_key);
        return $checksum;
    }
}

if(!function_exists('air_encrypt')){
    function air_encrypt($data, $salt) {
        // Build a 256-bit $key which is a SHA256 hash of $salt and $password.
        $key = hash('SHA256', $salt.'@'.$data);
        return $key;
    }
}

if(!function_exists('air_encryptSha256')){
    function air_encryptSha256($data) {
        $key = hash('SHA256', $data);
        return $key;
    }
}

if(!function_exists('air_calculateChecksumSha256')){
    function air_calculateChecksumSha256($data, $salt) {
        $checksum = hash('SHA256', $salt.'@'.$data);
        return $checksum;
    }
}
if(!function_exists('air_outputForm')){
    function air_outputForm($checksum) {
        //ksort($_POST);
        //$input = '';
        foreach($_POST as $key => $value) {
                echo '<input type="hidden" name="'.$key.'" value="'.$value.'" />'."\n";
        }
        echo '<input type="hidden" name="checksum" value="'.$checksum.'" />'."\n";

    }
}

if(!function_exists('getStateAbbreviation')){
    function getStateAbbreviation($state_name = '') {
        $states = array("Andaman and Nicobar Islands"=>"AN",
            "Andhra Pradesh"=>"AP",
            "Arunachal Pradesh"=>"AR",
            "Assam"=>"AS",
            "Bihar"=>"BR",
            "Chandigarh"=>"CH",
            "Chhattisgarh"=>"CT",
            "Dadra and Nagar Haveli"=>"DN",
            "Daman and Diu"=>"DD",
            "Delhi"=>"DL",
            "Goa"=>"GA",
            "Gujarat"=>"GJ",
            "Haryana"=>"HR",
            "Himachal Pradesh"=>"HP",
            "Jammu and Kashmir"=>"JK",
            "Jharkhand"=>"JH",
            "Karnataka"=>"KA",
            "Kerala"=>"KL",
            "Ladakh"=>"LA",
            "Lakshadweep"=>"LD",
            "Madhya Pradesh"=>"MP",
            "Maharashtra"=>"MH",
            "Manipur"=>"MN",
            "Meghalaya"=>"ML",
            "Mizoram"=>"MZ",
            "Nagaland"=>"NL",
            "Odisha"=>"OR",
            "Puducherry"=>"PY",
            "Punjab"=>"PB",
            "Rajasthan"=>"RJ",
            "Sikkim"=>"SK",
            "Tamil Nadu"=>"TN",
            "Telangana"=>"TG",
            "Tripura"=>"TR",
            "Uttar Pradesh"=>"UP",
            "Uttarakhand"=>"UT",
            "West Bengal"=>"WB");
        $statecode = 'GJ';
        foreach($states as $key=>$value){
            if ( $state_name == $key ) {
                $statecode = $value;
            }
        }
        return $statecode;
    }
}

if(!function_exists('calEligiblity')){
    function calEligiblity($income, $emi, $apr, $loanamount) {
        $remainamount = floor(((float)$income * 0.40) - (float)$emi);
        $monthlyemi = floor(($loanamount + ($loanamount * ($apr / 100)) * 6) / 72);
        $amount = floor(($loanamount * $remainamount) / $monthlyemi);

        if ($amount < 200000) {
            $amount = 195000;
        } else if ($amount > 850000) {
            $amount = 875000;
        }
        return round($amount);
    }
}

if (!function_exists('customEncrypt')) {
    function customEncrypt($value)
    {
        $key = substr(hash('sha256', env('SECURE_SALT')), 0, 32); // AES-256 key
        $iv = random_bytes(16); // 16 bytes for AES-256-CBC

        $encrypted = openssl_encrypt($value, 'AES-256-CBC', $key, 0, $iv);
        $encoded = base64_encode($encrypted . '::' . $iv);

        // Make URL-safe
        return strtr($encoded, ['+' => '-', '/' => '_', '=' => '~']);
    }
}

if (!function_exists('customDecrypt')) {
    function customDecrypt($encryptedValue)
    {
        $key = substr(hash('sha256', env('SECURE_SALT')), 0, 32); // AES-256 key

        // Revert URL-safe replacements
        $decoded = strtr($encryptedValue, ['-' => '+', '_' => '/', '~' => '=']);
        list($encrypted, $iv) = explode('::', base64_decode($decoded), 2);

        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    }
}

if(!function_exists('calPercentage')){
    function calPercentage($oldvalue, $newvalue)
    {
        $amtratio = 100 - (($newvalue * 100) / $oldvalue);
        $percentagevalue = round($amtratio) . "%";
        return $percentagevalue;
    }
}

function encryptData($data)
{
    $key = "jvJ7RGlyfjm0jwaa";
    $iv = "@@@@&&&&####$$$$";

    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptData($data)
{
    $key = "jvJ7RGlyfjm0jwaa";
    $data = base64_decode($data);

    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);

    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
}