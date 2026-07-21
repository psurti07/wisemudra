<?php

namespace App\Http\Controllers\pg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VeegahController extends Controller
{
    public function index(){
        return view('pg.veegah');
    }
    public function process(Request $request){
        try{
            $amount = '1.00';
            $grandamount = $amount + ($amount * 0.18);
            $roundamount = floor($grandamount);

            $orderid = 'KRBZVGH'.number_format(microtime(true) * 1000, 0, '.', '');
            $encData = null;

            $returnUrl = route('api.veegah.response');

            $terminalId = env('VEEGAH_TERMINAL_ID');
            $password = env('VEEGAH_TERMINAL_PASSWORD');
            $mkey = env('VEEGAH_MERCHANT_KEY');

            // data sequence is - orderId|terminalId|password|merchantKey|amount|currency
            //$signdata = $orderid."|TER7990817|TER25041201011970543064|f5949cf7946afa557191b8a18504c2a847a6d9ff08c28ec2fd456322889d1451|".$roundamount."|INR";
            $signdata = $orderid."|".$terminalId."|".$password."|".$mkey."|".$roundamount."|INR";
            $signature = hash('sha256', $signdata);

            $fullname = $request->firstname.' '.$request->lastname;
            $mobileno = $request->mobile;
            $emailid = $request->email;

            $postdata = array(
                "referenceId"=> $orderid,
                "terminalId"=> $terminalId,
                "password"=> $password,
                "signature"=>  $signature, //Generated signature
                "paymentType"=> "1",
                "amount"=> $roundamount,
                "currency"=> "INR",
                "order"=> array(
                    "orderId"=> $orderid,  // Related orderId
                    "description"=> "Test Payment"
                ),
                "customer"=> array(
                    "customerEmail"=> $emailid,
                    "billingAddressStreet"=> '',
                    "billingAddressCity"=> "",
                    "billingAddressState"=> "",
                    "billingAddressPostalCode"=> "",
                    "billingAddressCountry"=> "IN"
                ),
                "additionalDetails"=> array(
                    "userData"=> "{\"entryone\":\"abc\",\"entrytwo\":\"def\",\"entrythree\":\"xyz\",\"receiptUrl\":\"$returnUrl\"}"
                ),
            );
            Log::info('vegaah post data - ' . json_encode($postdata));
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://test-vegaah.concertosoft.com/vegaahpayments/v2/payments/pay-request",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS =>  json_encode($postdata),
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "accept: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            Log::info($response);
            $post_decode_data =  json_decode($response);
            $payment_url = $post_decode_data->paymentLink->linkUrl;
            $transaction_id = $post_decode_data->transactionId;

            $redirect_url = $payment_url.$transaction_id;

            return redirect($redirect_url);
        } catch(\Exception $e){
            Log::info('An error occured in veegah file - ' . $e->getMessage());
            dd('check log');
        }
    }

    public function response(Request $request){
        try{
            $jsonData = file_get_contents("php://input");
            parse_str($jsonData, $parsedData);
            unset($parsedData['termId']);
            $decodedData = urldecode($parsedData['data']);
            $decodedData = str_replace(' ', '+', $decodedData);

            $encryptedResponse = base64_decode($decodedData, true);

            $merKey = env('VEEGAH_MERCHANT_KEY');
            $binaryKey = hex2bin($merKey);

            $decryptedData = openssl_decrypt($encryptedResponse, 'AES-256-ECB', $binaryKey, OPENSSL_RAW_DATA);
            Log::info($decryptedData);
            if ($decryptedData === false) {
                dd('decrypted data false');
            }

            $resultdata = json_decode($decryptedData, true);
            if ($resultdata === null) {
                dd('nothing in data');
            }

            dd('payment success');
        } catch(\Exception $e){
            Log::info('An error occured in veegah response - ' . $e->getMessage());
            dd('error occured check log');
        }
    }
}
