<?php

use Illuminate\Support\Facades\Log;

//$salt = bin2hex(openssl_random_pseudo_bytes(8));
/*if (!function_exists('getGlobalVariable')) {
    function getGlobalVariable($aesKey, $aesIv)
    {
        $global = [
            'salt' => bin2hex(openssl_random_pseudo_bytes(8)),
            'aesKey' => $aesKey,
            'aesIv' => $aesIv
        ];
        return $global;
    }
}*/
/* Generate JWT Token */
if (!function_exists('generateJwt')) {
    function generateJwt($header, $payload, $secret)
    {
        $headerEncoded = base64UrlEncode(json_encode($header));
        $payloadEncoded = base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, $secret, true);
        $signatureEncoded = base64UrlEncode($signature);

        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }
}

/* Base64 URL Encode */
if (!function_exists('base64UrlEncode')) {
    function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}


/* get the generated jwt token */
if(!function_exists('getjwttoken')){
    function getjwttoken(){
        $reqId = uniqid();
        $tokendata = array(
            "timestamp" => date('Y-m-d H:i:s'),
            "partnerId" => 20221427,
            "reqId" => $reqId,
        );

        Log::info('Token data - ' . json_encode($tokendata));

        $header = array(
            'alg' => 'HS256', // Algorithm used
            'typ' => 'JWT'    // Type of token
        );

        $secret = 'Q1AwMDMyMTokMnkkMTIkWDd2UXZUNFJhcUZMZE1qM1V5d2lHTzVSa1ZWSm1Rc2NOS0hGalNvZDYwT0dzS3Y2ZG5IVUs='; //partnerToken

        Log::info('Secret key - ' . $secret);

        // Generate JWT token
        $generatedToken = generateJwt($header, $tokendata, $secret);

        // Log and return the generated token
        Log::info('Generated JWT Token: ' . $generatedToken);

        return $generatedToken;
    }
}

if(!function_exists('cipherPaymentStatus')){
    function cipherPaymentStatus($status){
        switch($status){
            case 1:
                $msg = 'Transaction Successfull';
                break;
            case 2:
                $msg = 'Transaction Under Process';
                break;
            case 3:
                $msg = 'Transaction Under Process';
                break;
            case 4:
                $msg = 'Transaction Under Process';
                break;
            default:
                $msg = 'Transaction Failed';
                break;
        }
        return $msg;
    }
}
