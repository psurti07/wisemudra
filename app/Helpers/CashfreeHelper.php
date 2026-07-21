<?php
    use Illuminate\Support\Facades\Log;

    if(!function_exists('getCashfreePaymentUrl')){
        function getCashfreePaymentUrl($csurl, $data){
            $cust_data = array(
                "customer_id" => $data['customer_id'],
                "customer_phone" => $data['customer_phone'],
                "customer_name" => $data['customer_name'],
                "customer_email" => $data['customer_email']
            );

            $return_data = array(
                "return_url" => $data['returnUrl']
            );

            $data_req1 = array(
                "order_id" => $data['order_id'],
                "order_amount" => $data['order_amount'],
                "order_currency" => "INR",
                "customer_details" => $cust_data,
                "order_meta" => $return_data,
                "order_note" => $data['order_note']
            );
            $data_req2 = json_encode($data_req1);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $csurl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_req2,
                CURLOPT_HTTPHEADER => [
                    "accept: application/json",
                    "content-type: application/json",
                    "x-api-version: 2022-09-01",
                    "x-client-id: " . env('CASHFREE_APP_ID'),
                    "x-client-secret: " . env('CASHFREE_SECRET_KEY')
                ]
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

    if(!function_exists('getOrderData')){
        function getOrderData($csurl){
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $csurl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => [
                    "accept: application/json",
                    "x-api-version: 2022-09-01",
                    "x-client-id: " . env('CASHFREE_APP_ID'),
                    "x-client-secret: " . env('CASHFREE_SECRET_KEY')
                ]
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
