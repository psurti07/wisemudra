<?php

    if(!function_exists('user_track')){
        function user_track($postData){
            $curl = curl_init();
            $map = [
                // Self product tags → Self key
                'Self Get Offer'          => config('constant.SELF_INTERAKT_KEY'),
                'Self Payment Successful' => config('constant.SELF_INTERAKT_KEY'),
            
                // Hire product tags → Hire key
                'Hire Get Offer'          => config('constant.HIRE_INTERAKT_KEY'),
                'Hire Payment Successful' => config('constant.HIRE_INTERAKT_KEY'),

                 // Webinar product tags → Webinar  key
                'Lead Gen'     => config('constant.WEBINAR_INTERAKT_KEY'),
                'Payment Successful' => config('constant.WEBINAR_INTERAKT_KEY'),
            ];

            $tag = $postData['tags'][0] ?? null; // safely get first tag
            $key = $map[$tag] ?? null;
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.interakt.ai/v1/public/track/users/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => [
                    "Authorization: Basic " . $key,
                    "Content-Type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            $result = json_decode($response, true);
            return $result;
        }
    }

    if(!function_exists('event_track')){
        function event_track($postData){
            $curl = curl_init();
            $arr = $map = [
                'Self Get Offer'            => config('constant.SELF_INTERAKT_KEY'),
                'Self Payment Successful'    => config('constant.SELF_INTERAKT_KEY'),
                'Self Payment Failed'        => config('constant.SELF_INTERAKT_KEY'),
                
                'Hire Get Offer'            => config('constant.HIRE_INTERAKT_KEY'),
                'Hire Payment Successful'    => config('constant.HIRE_INTERAKT_KEY'),
                'Hire Payment Failed'        => config('constant.HIRE_INTERAKT_KEY'),

                'Lead Gen'                  => config('constant.WEBINAR_INTERAKT_KEY'),
                'Payment Successful'        => config('constant.WEBINAR_INTERAKT_KEY'),
                'Payment Failed'            => config('constant.WEBINAR_INTERAKT_KEY'),
            ];
            
            $event = $postData['event'] ?? null; // safely get first tag
            $key = $map[$event] ?? null;
                        
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.interakt.ai/v1/public/track/events/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => [
                    "Authorization: Basic " . $key,
                    "Content-Type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            $result = json_decode($response, true);
            return $result;
        }
    }
    
    if(!function_exists('interakt_message')){
        function interakt_message($type, $postData, $key){
            /*Log::info($type);
            Log::info(json_encode($postData));
            Log::info($key);*/
            
            $curl = curl_init();
            //$key = ($type == 'self') ? config('constant.SELF_INTERAKT_KEY') : config('constant.HIRE_INTERAKT_KEY');
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.interakt.ai/v1/public/message/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => [
                    "Authorization: Basic " . $key,
                    "Content-Type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            return $response;
        }

    }
