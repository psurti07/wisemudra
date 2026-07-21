<?php

namespace App\Http\Controllers\pg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Http, Log};
use App\Utility\PaytmChecksum;


class PaytmController extends Controller
{
    public function checkout(){
        return view('pg.paytm_checkout');    
    }
    
    public function initiate(Request $request)
    {
        $orderId = "ORDER_" . time();
        $amount = $request->input('amount', 100); // default 100 for test
        $customerId = $request->input('customer_id', 'CUST_001');

        $paytmParams = [
            "requestType"   => "Payment",
            "mid"           => config('paytm.merchant_id'),
            "websiteName"   => config('paytm.website'),
            "orderId"       => $orderId,
            "callbackUrl"   => config('paytm.callback_url'),
            "txnAmount"     => [
                "value"    => number_format($amount, 2, '.', ''),
                "currency" => "INR",
            ],
            "userInfo"      => [
                "custId" => $customerId,
            ],
        ];

        $checksum = PaytmChecksum::generateSignature(
            json_encode($paytmParams, JSON_UNESCAPED_SLASHES),
            config('paytm.merchant_key')
        );

        $body = [
            "body" => $paytmParams,
            "head" => [
                "signature" => $checksum,
            ],
        ];
        Log::info($body);
        $url = sprintf(config('paytm.initiate_url'), config('paytm.merchant_id'), $orderId);

        $response = Http::withHeaders([
            "Content-Type" => "application/json"
        ])->post($url, $body);

        $respData = $response->json();

        if (isset($respData['body']['txnToken'])) {
            $txnToken = $respData['body']['txnToken'];
            return view('pg.paytm.checkout', [
                'orderId'  => $orderId,
                'txnToken' => $txnToken,
                'amount'   => $amount,
            ]);
        }

        return back()->with('error', 'Failed to initiate Paytm payment');
    }
    
    public function callback(Request $request)
    {
        $data = $request->all();

        $body = [
            "mid"     => config('paytm.merchant_id'),
            "orderId" => $data['ORDERID'] ?? '',
        ];

        $isValidChecksum = PaytmChecksum::verifySignature(
            $request->all(),
            config('paytm.merchant_key'),
            $request->input('CHECKSUMHASH')
        );

        $statusResponse = Http::withHeaders([
            "Content-Type" => "application/json"
        ])->post(config('paytm.status_api_url'), [
            "body" => $body,
            "head" => ["signature" => $checksum]
        ]);

        $statusData = $statusResponse->json();
        Log:info($statusData);
        return view('pg.paytm.result', ['status' => $statusData]);
    }
    
}