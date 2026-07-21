<?php

use Illuminate\Support\Facades\Log;

    if(!function_exists('createMerchantToken')) {
        function createMerchantToken()
        {
            $auth = array(
                'mid' => env('PAYGIC_MERCHANT_ID'),
                'password' => env('PAYGIC_PASSWORD')
            );

            $url = curl_init();
            curl_setopt_array($url, [
                CURLOPT_URL => "https://server.paygic.in/api/v2/createMerchantToken",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($auth),
                CURLOPT_HTTPHEADER => [
                    "accept: application/json",
                    "content-type: application/json",
                ]
            ]);

            $response = curl_exec($url);
            $err = curl_error($url);
            curl_close($url);
            if ($err) {
                return "cURL Error #:" . $err;
            } else {

                return json_decode($response, true);

            }

        }
    }

    if(!function_exists('createPaymentPage')) {
        function createPaymentPage($data, $token)
        {

            $url = curl_init();
            curl_setopt_array($url, [
                CURLOPT_URL => "https://server.paygic.in/api/v2/createPaymentPage",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => [
                    "content-type: application/json",
                    "token: $token",
                ]
            ]);

            $response = curl_exec($url);
            $err = curl_error($url);
            curl_close($url);
            return $response;

        }
    }

    if(!function_exists('checkPaymentStatus')) {
        function checkPaymentStatus($orderid, $token){

            $data = array(
                'mid' => env('PAYGIC_MERCHANT_ID'),
                'merchantReferenceId' =>$orderid
            );
            $url = curl_init();
            curl_setopt_array($url, [
                CURLOPT_URL => "https://server.paygic.in/api/v2/checkPaymentStatus",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => [
                    "content-type: application/json",
                    "token: $token",
                ]
            ]);

            $response = curl_exec($url);
            $err = curl_error($url);
            curl_close($url);
            return $response;
        }
    }
