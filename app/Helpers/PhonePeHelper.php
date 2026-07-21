<?php
header('Content-Type: text/html; charset=utf-8');
if(!function_exists('getPhonePePaymentUrl')){
    function getPhonePePaymentUrl($peurl, $key, $keyindex, $data) {
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
