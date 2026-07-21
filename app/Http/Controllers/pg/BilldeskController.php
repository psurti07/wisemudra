<?php

namespace App\Http\Controllers\pg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\BilldeskServices;

class BilldeskController extends Controller
{
    public function index(BilldeskServices $billDesk){
        try{
            $date = Carbon::now()->setTimezone('+05:30');
            $data = [
                "orderid" => "BDUAT" . number_format(microtime(true) * 1000, 0, '.', ''),
                "mercid" => env('MERCHANT_ID'),
                "order_date" => $date->format('Y-m-d\TH:i:sP'),
                "amount" => "1.00",
                "currency" => "356",
                "ru" => "https://wisemudra.com/",
                "itemcode" => "DIRECT",
                "customer" => [
                    "first_name" => "Vicky",
                    "last_name" => "Jaiswal",
                    "mobile" => "9408881214",
                    "mobile_alt" => "",
                    "email" => "vickyverloop@gmail.com",
                    "email_alt" => ""
                ],
                "device" => [
                    "init_channel" => "internet",
                    "ip" => request()->ip(),
                    "user_agent" => request()->userAgent(),
                    "accept_header" => "text/html",
                ],
                "timestamp" => $date->timestamp
            ];
            Log::info(json_encode($data));
            /*$payload = $billDesk->createPayload($data);
            $encrypted = $billDesk->encryptPayload($payload);
            $signed = $billDesk->signPayload($encrypted);
            $orderId = $data['orderid'];
            Log::info('payload - '. json_encode($payload));
            Log::info('signed - ' . $signed);
            Log::info('encrypted - ' . $encrypted);
            Log::info('orderId - ' . $orderId);*/
            /*return view('pg.billdesk'*//*,compact('payload','encrypted','signed','orderId')*//*);*/
        } catch(\Exception $e){
            Log::info('error occured in billdesk pg controller - ' . $e->getMessage());
            dd('index method catch');
        }
    }
}
