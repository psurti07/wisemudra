<?php

namespace App\Http\Controllers\pg;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\{Log, Session};

class ZwitchController extends Controller
{
    /*public function index(){
        return view('pg.zwitch');    
    }*/
    
    /*public function createToken(Request $request)
    {
        $endpoint = config('services.zwitch.sandbox')
            ? 'https://api.zwitch.io/v1/pg/sandbox/payment_token'
            : 'https://api.zwitch.io/v1/pg/payment_token';
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.zwitch.access_key') . ':' . config('services.zwitch.secret_key'),
            'Accept' => 'application/json',
        ])->post($endpoint, [
            ['udf' => [
                'key_1' => 'Vicky Jaiswal',
                'key_2' => 'Big Offer'
            ]],
            'amount' => $request->amount,
            'currency' => $request->currency,
            'contact_number' => '8530537178',
            'email_id' => 'vickyverloop@gmail.com',
            'mtx' => substr(uniqid(mt_rand(), true) , 0, 8),
            // … add other required fields
        ]);
        
        if ($response->successful()) {
            Session::put('paymentToken', $response->json('id'));
            return response()->json([
                'payment_token' => $response->json('id')  // <-- use 'id'
            ]);
        }


        return response()->json([
            'error' => $response->json()
        ], $response->status());
    }
    
    
    public function paymentResponse(Request $request){
        try{
            $paymentToken = Session::get('paymentToken');
        } catch(\Exception $e){
            Log::info('An error occured in zwitch payemnt response - ' . $e->getMessage());
            dd('check log zwitchpgcontroller');
        }
    }*/
    
    public function index(){
        $meta = selfApplyMeta();
        $products = Product::where('productslug', env('SA_OFFER_6'))->first();

        if ($products->inOffer == 1) {
            $productData = array(
                'inOffer' => $products->inOffer,
                'amount' => $products->amount,
                'offeramount' => $products->offeramount,
                'offerdate' => date('Y/m/d', strtotime('+1 days')) . ' 24:00:00',
                'payamount' => $products->offeramount + ($products->offeramount * 0.18)
            );
        } else {
            $productData = array(
                'inOffer' => 0,
                'amount' => $products->amount,
                'offeramount' => 0,
                'offerdate' => '',
                'payamount' => $products->amount + ($products->amount * 0.18)
            );
        }
        return view('pg.zwitch', compact('meta', 'productData'));
    }
    
    public function createToken(Request $request){
        try{
            $inputs = $request->all();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'mobile' => ['required', 'numeric', 'regex:/^[6-9]\d{9}$/']
            ]);
            /* product Data */
            $products = Product::where('productslug', env('SA_OFFER_6'))->first();
            $amount = ($products->inOffer == 1) ? $products->offeramount : $products->amount;
            $grandAmount = $amount + ($amount * 0.18);

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', '')); // Convert the string into an array

            foreach ($uatNumbers as $uatNum) {
                if ($uatNum == $request->mobile) {
                    $grandAmount = 1;
                    break; // Exit the loop once a match is found
                }
            }
            
            $orderid = 'ZTH_' . number_format(microtime(true) * 1000, 0, '.', '');
            $return_url = route('zwitch.payment.response');
    
            $paymentData = [
                "order_id"        => $orderid,
                "customer_id"     => uniqid() . mt_rand(1000, 9999),
                "amount"          => $grandAmount,
                "currency"        => "INR",
                "name"            => $request->first_name . ' ' . $request->last_name,
                "email_id"        => $request->email,
                "contact_number"  => $request->mobile,
                "mtx"             => $orderid,
                "return_url"      => $return_url,
                "udf"             => ['key_1' => 'jaiswal']
            ];
    
            $accesskey  = config('services.zwitch.access_key');
            $secretkey  = config('services.zwitch.secret_key');
            $environment = config('services.zwitch.sandbox'); // e.g. https://sandbox-payments.open.money
    
            // 🔹 Step 1: Create payment token
            $payment_token = create_payment_token($environment, $accesskey, $secretkey, $paymentData);
    
            if (isset($payment_token['error'])) {
                return back()->withErrors('Payment error: ' . $payment_token['error']);
            }
    
            if (empty($payment_token["id"])) {
                return back()->withErrors('Payment error: Token ID missing.');
            }
    
            // 🔹 Step 2: Fetch token details
            $payment_token_data = get_payment_token($environment, $accesskey, $secretkey, $payment_token["id"]);
    
            if (isset($payment_token_data['error'])) {
                return back()->withErrors('Payment error: ' . $payment_token_data['error']);
            }
    
            if ($payment_token_data['status'] === "paid") {
                return back()->withErrors('This order has already been paid.');
            }
    
            if ($payment_token_data['amount'] != $paymentData['amount']) {
                return back()->withErrors('Amount mismatch occurred.');
            }
    
            // 🔹 Step 3: Create hash for validation
            $hash = create_hash([
                'layer_pay_token_id'   => $payment_token_data['id'],
                'layer_order_amount'   => $payment_token_data['amount'],
                'tranid'               => $orderid,
            ], $accesskey, $secretkey);
    
            // 🔹 Step 4: Pass data to Blade view
            $html = view('pg.zwitch_checkout', [
                'token_id'  => $payment_token_data['id'],
                'tranid'    => $orderid,
                'amount'    => $payment_token_data['amount'],
                'accesskey' => $accesskey,
                'hash'      => $hash,
            ])->render();
            return response()->json(array('type'=>'SUCCESS','message'=>'Please wait... We are redirecting to the payment page.','html'=>$html));
        } catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['errors'=>$e->errors(),'type'=>'ERROR']);
        } catch(\Exception $e){
            Log::info('An error occured in zwitch controller - ' . $e->getMessage());
            return response()->json(['type'=>'ERROR','message'=>'Something went wrong']);
        }
    }
    
    public function paymentResponse(Request $request){
        $accesskey  = config('services.zwitch.access_key');
        $secretkey  = config('services.zwitch.secret_key');
        $environment = config('services.zwitch.sandbox');
        $payment_data = get_payment_details($environment, $accesskey, $secretkey, $request->layer_payment_id);
        dd($payment_data);
    }
}
