<?php

use Illuminate\Support\Facades\Log;

if(!function_exists('getlyrapaymenturl')){
    function getlyrapaymenturl($peurl, $data){
        $data_json = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => $peurl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>  $data_json,
          CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            'Authorization: Basic ' . base64_encode(env('LYRA_SHOP_ID').":".env('LYRA_API_KEY')),
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
