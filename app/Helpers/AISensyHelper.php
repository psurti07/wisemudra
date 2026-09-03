<?php


    if(!function_exists('aisensy_track')){
        function aisensy_track($postData){
            $curl = curl_init();

            curl_setopt_array($curl, [
              CURLOPT_URL => "https://backend.aisensy.com/campaign/t1/api/v2",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => json_encode($postData),
              CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
              ],
            ]);
        
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
        
            return $response; 
        }
    }