<?php

namespace App\Http\Controllers\pg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Exception\APIException;
use App\Services\PaymentService;

class HdfcPgController extends Controller
{
    public function index(){
        return view('pg.hdfcIndex');
    }
    
    public function initiatePayment(Request $request){
        $paymentHandler = new PaymentService(json_encode(config('payment'), TRUE));

        $orderId = 'laravel_sdk_' . uniqid();
        $customerId = 'laravel_sdk_customer_' . uniqid();
        
        $params = [
            "amount" => "10.00",
            "order_id" => $orderId,
            "customer_id" => $customerId,
            "action" => "paymentPage",
            "return_url" => route('hdfc.handlePaymentResponse'),
        ];

        try {
            $session = $paymentHandler->orderSession($params);

            return redirect()->away($session['payment_links']['web']);

        } catch (APIException $e) {
            return response()->json([
                'message' => $e->getErrorMessage(),
                'error_code' => $e->getErrorCode(),
                'http_response_code' => $e->getHttpResponseCode()
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
