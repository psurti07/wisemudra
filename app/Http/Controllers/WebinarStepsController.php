<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\FbAdsEntry;
use App\Models\FreeLogModel;
use App\Models\InfoPages;
use App\Models\Invoice;
use App\Models\OtpVerification;
use App\Models\SiteOption;
use App\Models\UserRegistration;
use App\Models\WebinarCustomers;
use App\Models\WebinarEvent;
use App\Models\WebinarOrder;
use App\Models\WebinarRegistration;
use App\Models\ZaakpayEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class WebinarStepsController extends Controller
{
    public function webinar()
    {
        $meta = fintechMeta();
        return view('front.Webinar.index', compact('meta'));
    }

    public function webinarStep1(Request $request)
    {
        $meta = fintechRegisterMeta();
        //session()->forget('email', 'mobile_no', 'city', 'state', 'pincode', 'current_occupation', 'earning_goal', 'fbclid', 'step3_completed', 'payment_attempted', 'firstname', 'lastname', 'mobile_no', 'otp_verified');
        $fbclid = $request->query('fbclid', null);
        session(['fbclid' => $fbclid]);
        return view('front.Webinar.step1', compact('meta'));
    }

    public function webinarStep2()
    {
        $meta = fintechRegisterMeta();
        if (!session()->has('firstname') || !session()->has('lastname') || !session()->has('mobile_no')) {
            return redirect()->route('webinar.step1');
        }
        return view('front.Webinar.step2', compact('meta'));
    }

    public function webinarStep3()
    {
        $meta = fintechRegisterMeta();
        if (!session()->has('firstname') || !session()->has('lastname') ||  !session()->has('mobile_no')) {
            return redirect()->route('webinar.step1');
        }
        if (!session('otp_verified')) {
            return redirect()->route('webinar.step2');
        }
        return view('front.Webinar.step3', compact('meta'));
    }

    public function storewebinarStep1(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'mobile_no' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
        ]);
        try {
            session([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'mobile_no' => $request->mobile_no,
            ]);

            $webinar = WebinarEvent::where('program_type', 0)
                ->where('isActive', 1)
                ->where('isDelete', 0)
                ->whereDate('event_datetime', '>', now())
                ->orderBy('event_datetime', 'asc')
                ->first();

            if (!$webinar) {
                return response()->json([
                    'status' => false,
                    'message' => 'No upcoming webinar found.',
                    'redirect_url' => route('webinar.step1')
                ]);
            }

            $user = WebinarRegistration::where('mobile', $request->mobile_no)->first();

            if ($user) {

                $usercustomer = WebinarOrder::where('userid', $user->id)->where('webinar_id', $webinar->id)->first();

                if ($usercustomer) {
                    if ($usercustomer->isUser == 2) {
                        return response()->json([
                            'type' => 'ALREADY_REGISTERED',
                            'message' => 'You’re already registered with us. Please join the community to get further important updates.',
                            'redirect_url' => route('webinar.step1')
                        ]);
                    }
                } else {
                    $userWebinarCustomers = WebinarOrder::create([
                        'userid'     => $user->id,
                        'webinar_id' => $webinar->id,
                        'rec_date'   => now(),
                        'isUser'     => 1,
                    ]);
                }

                if ($user->process_step == 1) {
                    return response()->json([
                        'message' => 'User account already verified.',
                        'redirect_url' => route('webinar.step3')
                    ]);
                }

                if ($user->process_step == 2 || $user->process_step == 3) {
                    session(['otp_verified' => true,'step3_completed' => true,]);
                    
                    return response()->json([
                        'message' => 'User account already verified.',
                        'redirect_url' => route('webinar.step4')
                    ]);
                }
            }
            $generatedOtp = generateOtp($request->mobile_no, 9);

            return response()->json(['message' => 'OTP has been sent successfully.', 'redirect_url' => route('webinar.step2')]);
        } catch (\Exception $e) {
            Log::error('storeWebinarStep1 failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'type' => 'ERROR',
                'message' => 'Something went wrong while processing your request. Please try again.',
            ], 500);
        }
    }
    public function verifywebinarOtpStep(Request $request)
    {
        $request->validate([
            'otp' => 'required|array|size:4',
            'otp.*' => 'required|digits:1',
        ], [
            'otp.*.required' => 'OTP is required.',
        ]);

        try {
            $otp = implode('', $request->otp);
            $mobile_no = session('mobile_no');
            $firstname = session('firstname');
            $lastname  = session('lastname');
            $otpRecord = OtpVerification::where('mobile', $mobile_no)
                ->where('acc_type', 9)
                ->orderByDesc('rec_date')
                ->orderBy('id', 'desc')
                ->first();

            if ($otpRecord && trim($otpRecord->otp) === trim($otp)) {

                session(['otp_verified' => true]);

                $webinar = WebinarEvent::where('program_type', 0)
                    ->where('isActive', 1)
                    ->where('isDelete', 0)
                    ->whereDate('event_datetime', '>', now())
                    ->orderBy('event_datetime', 'asc')
                    ->first();

                if (!$webinar) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No upcoming webinar found.',
                        'redirect' => route('webinar.step1')
                    ]);
                }
                // Create or update user WITHOUT password
                $user = WebinarRegistration::updateOrCreate(
                    [
                        'mobile'   => $mobile_no,
                    ],
                    [
                        'rec_date'   => now(),
                        'first_name' => $firstname,
                        'last_name'  => $lastname,
                        'isUser'     => 1,
                        'process_step' => 1,
                        'isActive'   => 1,
                        'isDelete'   => 0,
                    ]
                );

                $user = WebinarOrder::updateOrCreate(
                    [
                        'userid'     => $user->id,
                        'webinar_id' => $webinar->id,
                    ],
                    [
                        'rec_date'   => now(),
                        'isUser'     => 1,
                    ]
                );

                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP is correct',
                    'redirect' => route('webinar.step3')
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP',
            ], 422);
        } catch (\Exception $e) {
            Log::error('verifyWebinarOtpStep failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while verifying the OTP. Please try again.',
            ], 500);
        }
    }
    public function resendwebinarOtp(Request $request)
    {
        try {
            $mobile_no = session('mobile_no');
            if (!$mobile_no) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mobile number not found in session.',
                ], 422);
            }

            // Track resend count and time in session
            $resendSessionKey = 'otp_resend_' . $mobile_no;
            $resendData = session($resendSessionKey, [
                'count' => 0,
                'last_time' => null,
                'date' => now()->toDateString(),
            ]);

            // Reset count if it's a new day
            if ($resendData['date'] !== now()->toDateString()) {
                $resendData = [
                    'count' => 0,
                    'last_time' => null,
                    'date' => now()->toDateString(),
                ];
            }

            // Check daily resend limit
            if ($resendData['count'] >= 5) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have reached your OTP resend limit for today. Please try again tomorrow.',
                ], 429);
            }

            // Check 30-second cooldown
            if ($resendData['last_time'] && now()->diffInSeconds($resendData['last_time']) < 30) {
                $remaining = 30 - now()->diffInSeconds($resendData['last_time']);
                return response()->json([
                    'status' => 'error',
                    'message' => "Please wait {$remaining} seconds before resending OTP.",
                ], 429);
            }

            $generatedOtp = generateOtp($mobile_no, 9);

            // Update session values
            $resendData['count'] += 1;
            $resendData['last_time'] = now();
            session([$resendSessionKey => $resendData]);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP has been resent successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('resendWebinarOtp failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while resending the OTP. Please try again.',
            ], 500);
        }
    }

    public function postalDetails(Request $request)
    {
        try {
            $promise = getPostalDetailsByPincode($request->input('pincode'));
            return response()->json(['status' => 'success', 'district' => $promise['city'], 'state' => $promise['state'],]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'district' => '', 'state' => '', 'message' => 'Invalid Pincode']);
        }
    }

    public function storewebinarStep3(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|digits:6',
            'earning_goal' => 'required',
            'occupation' => 'required'
        ]);
        try {

            $webinar = WebinarEvent::where('program_type', 0)
                ->where('isActive', 1)
                ->where('isDelete', 0)
                ->whereDate('event_datetime', '>', now())
                ->orderBy('event_datetime', 'asc')
                ->first();

            $user = WebinarRegistration::where('mobile', session('mobile_no'))->firstOrFail();

            $user->update([
                'rec_date' => now(),
                'email' => $request->email,
                'pincode' => $request->pincode,
                'city'  => $request->city,
                'state' => $request->state,
                'earning_goal' => $request->earning_goal,
                'occupation' => $request->occupation,
                'process_step' => 2
            ]);

            /* ===============================
            STORE SESSION DATA
            =============================== */

            session([
                'email' => $request->email,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'earning_goal' => $request->earning_goal,
                'occupation' => $request->occupation,
                'step3_completed' => true,
            ]);

            /* ===============================
            FB LEAD ENTRY
            =============================== */

            $fbid = FbAdsEntry::insertGetId([
                'rec_date' => now(),
                'userid'   => $user->id,
                'fbclid'   => session('fbclid'),
            ]);
            /* interakt code here starts */
            $data2 = array(
                'phoneNumber' => session('mobile_no'),
                'countryCode' => '+91',
                'traits' => array(
                    'name' => session('firstname') . ' ' . session('lastname'),
                ),
                'tags' => array('Lead Generated'),
            );
            $restrack1 = user_track($data2);
            $data3 = array(
                'phoneNumber' => session('mobile_no'),
                'countryCode' => '+91',
                'event' => 'Lead Generated',
            );
            $restrack2 = event_track($data3);
            /* interakt code ends */

            return response()->json([
                'type' => 'SUCCESS',
                'message' => 'User registered successfully!',
                'redirect' => route('webinar.step4')
            ]);
        } catch (\Exception $e) {
            Log::error('storeWebinarStep3 failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'type' => 'ERROR',
                'message' => 'Something went wrong during registration. Please try again later.',
            ], 500);
        }
    }

    public function webinarStep4()
    {
        $meta = fintechRegisterMeta();
        if (!session()->has('firstname') || !session()->has('lastname') || !session()->has('mobile_no')) {
            return redirect()->route('webinar.step1');
        }

        if (!session()->has('otp_verified')) {
            return redirect()->route('webinar.step2');
        }

        if (!session()->has('step3_completed')) {
            return redirect()->route('webinar.step3');
        }
        $webinar = WebinarEvent::where('program_type', 0)
            ->where('isActive', 1)
            ->where('isDelete', 0)
            ->whereDate('event_datetime', '>', Carbon::today())
            ->orderBy('event_datetime', 'asc')
            ->first();
        return view('front.Webinar.step4', compact('webinar', 'meta'));
    }

    public function initiateWebinarPayment(Request $request)
    {
        try {

            /* ===============================
            GET UPCOMING WEBINAR
            =============================== */
            $id = $request->id;
            $webinar = WebinarEvent::where('id', $id)
                ->where('program_type', 0)
                ->where('isActive', 1)
                ->where('isDelete', 0)
                ->firstOrFail();

            $user = WebinarRegistration::where('mobile', session('mobile_no'))->first();

            $user->process_step = 3;
            $user->save();
            /* ===============================
            CREATE ORDER Id
            =============================== */
            $orderid  = substr(hash('sha256', mt_rand() . microtime()), 0, 20);

            if ($webinar->event_offer_price == 0) {
                FreeLogModel::create(attributes: [
                    'rec_date'    => now(),
                    'entryfor'    => 9,
                    'userid'      => $user->id,
                    'orderid'     => $orderid,
                    'orderamount' => 0,
                    'ordernote'   => 'Free Registation'
                ]);

                return response()->json([
                    'type' => 'FREE',
                    'message' => 'Please wait we are redirecting...',
                    'redirect_url' => route('api.webinar.free.response', ['orderid' => $orderid])
                ]);
            }

            $baseAmount  = $webinar->event_offer_price ? $webinar->event_offer_price : $webinar->event_main_price;
            $totalAmount = $baseAmount;

            $returnUrl = route('api.webinar.buynow.response');

            // $curlurl = "https://api.zaakpay.com/api/paymentTransact/V8";
            $curlurl = "https://zaakstaging.zaakpay.com/api/paymentTransact/V8";

            $uatNumbers = explode(',', env('UAT_MOBILE_NUMBERS', ''));
            if (in_array(session('mobile_no'), $uatNumbers)) {
                $totalAmount = 1;
            }

            if ($totalAmount != 1) {
                if (strtolower(session('state')) === 'gujarat') {
                    $totalAmount += ($totalAmount * 0.18);
                    $taxNote = 'CGST 9% + SGST 9% applied';
                } else {
                    $totalAmount += ($totalAmount * 0.18);
                    $taxNote = 'IGST 18% applied';
                }
            }
            $totalAmount = round($totalAmount, 2);

            $firstname = $user->first_name . ' ' . $user->last_name;
            $zaakpayPostData = array(
                "merchantIdentifier" => env('ZAAKPAY_MERCHANT_IDENTIFIER'),
                "orderId" => $orderid,
                "returnUrl" => $returnUrl,
                "currency" => 'INR',
                "amount" => $totalAmount * 100,
                "buyerEmail" => $user->email,
                "buyerFirstName" => $firstname,
                "buyerPhoneNumber" => $user->mobile,
                "buyerCountry" => 'India',
                "productDescription" => 'webinar',
            );

            ksort($zaakpayPostData);
            $checksumData = "";

            foreach ($zaakpayPostData as $key => $value) {
                $checksumData .= $key . '=' . $value . '&';
            }

            $checksum = hash_hmac('sha256', $checksumData, env('ZAAKPAY_SECRET_KEY'));

            $zaakPayData = array(
                'rec_date' => now(),
                'entryfor' => 99,
                'userid' => $user->id,
                'orderid' => $orderid,
                'orderamount' => $totalAmount,
                'ordernote' => 'webinar',
            );
            $response = ZaakpayEntry::create($zaakPayData);

            $html = view('pg.zaakpay-checkout', compact('zaakpayPostData', 'checksum', 'curlurl'))->render();
            session(['payment_attempted' => true]);
            return response()->json([
                'type'    => 'SUCCESS',
                'message' => 'Please wait we are redirecting...',
                'html'    => $html,
            ]);
        } catch (\Exception $e) {
            Log::error('initiateFintechPayment failed: ' . $e->getMessage());
            return response()->json([
                'type' => 'ERROR',
                'message' => 'Unable to initiate payment. Please try again.',
            ], 500);
        }
    }

    public function webinarBuyNowResponse(Request $request)
    {
        try {

            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;

            $orderId = $request->orderId;
            $responseCode = $request->responseCode;
            $orderAmount = $request->amount / 100;
            $txnId = $request->pgTransId;
            $paymentMode = $request->paymentMode;
            $recd_checksum = $request->checksum;

            $checksum = $checksumData = '';

            $checksumsequence = [
                "amount",
                "bank",
                "bankid",
                "cardId",
                "cardScheme",
                "cardToken",
                "cardhashid",
                "doRedirect",
                "orderId",
                "paymentMethod",
                "paymentMode",
                "responseCode",
                "responseDescription",
                "productDescription",
                "product1Description",
                "product2Description",
                "product3Description",
                "product4Description",
                "pgTransId",
                "pgTransTime"
            ];

            foreach ($checksumsequence as $field) {
                if (array_key_exists($field, $request->all())) {
                    $checksumData .= $field;
                    $checksumData .= "=";
                    $checksumData .= htmlspecialchars($request->input($field));
                    $checksumData .= "&";
                }
            }

            $checksum = hash_hmac('sha256', $checksumData, env('ZAAKPAY_SECRET_KEY'));

            $paymentdata = ZaakpayEntry::where('orderid', $orderId)->first();

            $paymentdata->update([
                'rec_date' => now(),
                'orderamount' => $orderAmount,
                'statuscode' => $responseCode,
                'transactionid' => $txnId,
                'paymentmode' => $paymentMode
            ]);

            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;

            $userOrderData = WebinarOrder::where('userid', $paymentdata->userid)->orderBy('id', 'desc')->first();

            $userData = WebinarRegistration::find($paymentdata->userid);

            $webinar = WebinarEvent::where('id', $userOrderData->webinar_id)
                ->where('program_type', 0)
                ->where('isActive', 1)
                ->where('isDelete', 0)
                ->firstOrFail();

            $cardno = random_code(16);
            $checkStatus = isset($request->status) && $request->status != "";

            if (isset($request->responseCode) && $request->responseCode != "") {
                $status = $request->responseCode;

                if ($status == "100") {

                    $userOrderData->update([
                        'rec_date'  => now(),
                        'amount'    => $paymentdata->orderamount,
                        'paymentid' => $txnId,
                        'orderid'   => $orderId,
                        'isUser'    => 2,
                        'isAttend'  => 0,
                    ]);

                    //Create Invoice after Payment Success
                    $baseAmount = $webinar->event_offer_price > 0 ? $webinar->event_offer_price : $webinar->event_main_price;
                    // GST calculation
                    $netamount   = $baseAmount;
                    $cgstamount  = 0;
                    $sgstamount  = 0;
                    $igstamount  = 0;

                    if (strtolower($userData->state) == 'gujarat') {
                        $cgstamount = round($baseAmount * 0.09, 2);
                        $sgstamount = round($baseAmount * 0.09, 2);
                    } else {
                        $igstamount = round($baseAmount * 0.18, 2);
                    }
                    $grandtotal = round($netamount + $cgstamount + $sgstamount + $igstamount, 2);

                    // Fetch current invoice number
                    $invoiceNo = SiteOption::where('option_key', 'newinvoiceno')->select('option_value')->first();

                    // Prevent duplicate invoice for same user + webinar
                    $existingInvoice = Invoice::where('userid', $paymentdata->userid)
                        ->where('inv_prefix', 'Webinar_')
                        ->where('cardid', $userData->id)
                        ->first();

                    if (!$existingInvoice) {
                        DB::beginTransaction();
                        try {
                            $invData = [
                                'rec_date' => now(),
                                'userid' => $userData->id,
                                'cardid' => $userOrderData->id,
                                'inv_prefix' => 'Webinar_',
                                'inv_number' => $invoiceNo->option_value,
                                'inv_date' => now()->toDateString(),
                                'inv_price' => number_format($netamount, 2),
                                'inv_cgst' => number_format($cgstamount, 2),
                                'inv_sgst' => number_format($sgstamount, 2),
                                'inv_igst' => number_format($igstamount, 2),
                                'inv_grandtotal' => number_format($grandtotal, 2),
                                'is_refund' => 0,
                                'isdelete' => 0
                            ];
                            $invoice = Invoice::create($invData);
                            SiteOption::where('option_key', 'newinvoiceno')->update([
                                'rec_date'     => now(),
                                'option_value' => $invoiceNo->option_value + 1
                            ]);

                            DB::commit();
                            $mailData = [
                                'fullname' => $userData->first_name . ' ' . $userData->last_name,
                                'mobile' => $userData->mobile,
                                'email' => $userData->email,
                                'order_number' => $request->pgTransId,
                                'order_date' => date('d-m-Y'),
                                'order_amount' => $grandtotal,
                                'transactionId' => $request->pgTransId,
                            ];

                            $sendGreetings = view('mail.fintechMailTemplate', [
                                'userData' => $userData,
                                'webinar'  => $webinar,
                                'mailData' => $mailData
                            ])->render();

                            $invoiceData = [
                                'inv_prefix'      => $invoice->inv_prefix,
                                'inv_number'      => $invoice->inv_number,
                                'inv_date'        => $invoice->inv_date,
                                'inv_price'       => $invoice->inv_price,
                                'inv_cgst'        => $invoice->inv_cgst,
                                'inv_sgst'        => $invoice->inv_sgst,
                                'inv_igst'        => $invoice->inv_igst,
                                'inv_grandtotal'  => $invoice->inv_grandtotal,
                                'fullname' => trim($userData->first_name . ' ' . $userData->last_name),
                                'mobile'   => $userData->mobile ?? '',
                                'email'    => $userData->email ?? '',
                                'city'     => $userData->city ?? '',
                                'state'    => $userData->state ?? '',
                                'paymentid' => $txnId ?? '',
                                'acc_type'         => 1,
                                'card_number'      => 'N/A',
                                'registration_date' => now(),
                                'expiry_date'      => now(),
                                'invoice' => $invoice,
                                'userData' => $userData,
                                'webinar' => $webinar,
                                'isCustomer' => true,
                            ];

                            $invoiceHtml = view('mail.invoice', $invoiceData)->render();
                            $pdf = Pdf::loadHTML($invoiceHtml)->setPaper('A4', 'portrait')->output();
                            $base64Pdf = base64_encode($pdf);

                            $attachments = [
                                [
                                    'content' => $base64Pdf,
                                    'name' => 'Invoice_' . $invoice->inv_prefix . $invoice->inv_number . '.pdf'
                                ]
                            ];

                            $subject = 'Welcome! Webinar Registration Successful — Important Next Step!';
                            sendBrevoHtmlMail2($mailData, $subject, $sendGreetings, '', $attachments);
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('Invoice creation failed', [
                                'userid'    => $paymentdata->userid,
                                'webinarid' => $webinar->id,
                                'error'     => $e->getMessage()
                            ]);
                        }
                    }

                    // whartsapp track
                    $configs = DB::table('interakt_settings')->where('product', 'Webinar')->where('type', 'payment_success')->first();
                    $data = [
                        'fullPhoneNumber' => '+91' . $userData->mobile,
                        'callbackData' => 'some text here',
                        'type' => 'Template',
                        'template' => [
                            'name' => $configs->template_name ?? '',
                            'languageCode' => 'en',
                            'headerValues' => [
                                $configs->img_url ?? ''
                            ],
                            'bodyValues' => [
                                $userData->first_name . ' ' . $userData->last_name
                            ]
                        ]
                    ];

                    interakt_message('fintech', $data, $configs->api_key ?? '');

                    /* interakt code here starts */
                    $data2 = array(
                        'phoneNumber' => $userData->mobile,
                        'countryCode' => '+91',
                        'traits' => array(
                            'name' => $userData->first_name . ' ' . $userData->last_name,
                        ),
                        'tags' => array('Payment Successful'),
                    );
                    $restrack1 = user_track($data2);

                    $data3 = array(
                        'phoneNumber' => $userData->mobile,
                        'countryCode' => '+91',
                        'event' => 'Payment Successful',
                    );
                    $restrack2 = event_track($data3);
                    /* interakt code ends */


                    $mobile_no = $userData->mobile;

                    $senderOption = InfoPages::where('slug', 'webinar-sender-id')->first();
                    $sender_id = $senderOption ? $senderOption->content : 'KRDTBZ';

                    $msg = DB::table('sms_list')->where('type', 5)->where('slug', 'payment_successfull')->first()->message;
                    if (!$msg || $msg == '#') {
                    } else {
                        try {
                            $paymentSmsResult = sendSingleSMS($mobile_no, $msg, $sender_id);
                        } catch (\Exception $e) {
                            Log::error('Payment Success SMS exception occurred', [
                                'mobile' => $mobile_no,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }

                    /* ===============================
                    FACEBOOK CONVERSION TRACKING
                    =============================== */
                    try {

                        $fbleads = FbAdsEntry::where('userid', $userData->userid)->orderByDesc('id')->limit(1)->first();

                        $fbdata = [
                            'type' => 'webinar',
                            'firstname' => $userData->first_name,
                            'lastname' => $userData->last_name,
                            'mobile' => "91" . $userData->mobile,
                            'email' => strtolower($userData->email),
                            'city' => $userData->city,
                            'state' => $userData->state,
                            'zip' => $userData->pincode,
                            'orderid' => $txnId,
                            'odamount' => $paymentdata->orderamount,
                            'sourceurl' => 'https://wisemudra.com/webinar/payment-response/true'
                        ];

                        if ($fbleads && !empty($fbleads->fbclid)) {
                            $fbdata['fbclid'] = "fb.0." . round(microtime(true) * 1000) . "." . $fbleads->fbclid;
                        } else {
                            $fbdata['fbclid'] = '';
                        }

                        $fbresponse = fbconversioncurl($fbdata, 11);

                        $dataleads = [
                            'rec_date' => now(),
                            'send_data' => json_encode($fbdata),
                            'received_data' => is_array($fbresponse)
                                ? json_encode($fbresponse)
                                : $fbresponse
                        ];

                        if ($fbleads) {
                            FbAdsEntry::where('id', $fbleads->id)->update($dataleads);
                        }
                    } catch (\Exception $fbException) {

                        Log::error('Facebook Conversion Failed', [
                            'message' => $fbException->getMessage(),
                            'line' => $fbException->getLine()
                        ]);
                    }
                    /* fb conversion code ends here */

                    session([
                        'last_payment_reference' => $txnId,
                        'last_payment_amount' => $paymentdata->orderamount,
                        'user_email' => $userData->email,
                        'user_mobile' => $userData->mobile_no,
                        'user_firstname' => $userData->firstname,
                        'user_lastname' => $userData->lastname
                    ]);

                    return redirect()->route('webinar.payment.success');
                } else if ($status == '102') {
                    $configs = DB::table('interakt_settings')->where('product', 'Webinar')->where('type', 'payment_unsuccessful')->first();
                    $data = [
                        'fullPhoneNumber' => '+91' . $userData->mobile,
                        'callbackData' => 'some text here',
                        'type' => 'Template',
                        'template' => [
                            'name' => $configs->template_name ?? '',
                            'languageCode' => 'en',
                            'headerValues' => [
                                $configs->img_url ?? ''
                            ],
                            'bodyValues' => [
                                $userData->first_name . ' ' . $userData->last_name
                            ]
                        ]
                    ];

                    interakt_message('fintech', $data, $configs->api_key ?? '');


                    // Send Payment Failed SMS
                    $sender_id = DB::table('info_pages')->where('slug', 'webinar-senderid')->first()->content;

                    $msg = DB::table('sms_list')->where('type', 5)->where('slug', 'payment_unsucessful')->first()->message;
                    if ($msg != '#') {
                        sendSingleSMS($userData->mobile, $msg, $sender_id);
                    }
                    return redirect()->route('webinar.payment.failed');
                } else {
                    return redirect()->route('webinar.payment.failed');
                }
            }
            return redirect()->route('webinar.payment.failed');
        } catch (\Exception $e) {

            Log::error('An error occured in webinarBuyNowRespone()', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect()->route('webinar.payment.failed');
        }
    }

    public function webinarFreeRegistration($orderid)
    {
        try {
            $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;

            $paymentData = FreeLogModel::where('orderid', $orderid)->firstOrFail();

            $webinarUser = WebinarRegistration::where('id', $paymentData->userid)->firstOrFail();

            if ($webinarUser) {
                $userData = WebinarOrder::where('userid', $webinarUser->id)
                    ->orderBy('id', 'desc')
                    ->first();
            } else {
                $userData = null;
            }

            $webinar = WebinarEvent::where('id', $userData->webinar_id)
                ->where('program_type', 0)
                ->where('isActive', 1)
                ->where('isDelete', 0)
                ->firstOrFail();

            // Update Webinar User Data after Payment Success
            $userData->update([
                'rec_date'     => now(),
                'program_id'   => $webinar->id,
                'isUser'       => 2,
                'process_step' => 4,
            ]);

            // mail send
            $mailData = [
                'fullname' => $webinarUser->first_name . ' ' . $webinarUser->last_name,
                'mobile' => $webinarUser->mobile,
                'email' => $webinarUser->email,
                'order_number' => $paymentData->orderid,
                'order_date' => date('d-m-Y'),
                'order_amount' => $grandtotal,
                'transactionId' => '',
            ];

            $sendGreetings = view('mail.fintechFreeMailTemplate', [
                'userData' => $webinarUser,
                'webinar'  => $webinar,
                'mailData' => $mailData
            ])->render();

            $subject = 'Webinar Registration Confirmed';
            $sendmail = sendBrevoHtmlMail2($mailData, $subject, $sendGreetings, '');

            $configs = DB::table('interakt_settings')->where('product', 'WEBINAR')->where('type', 'payment_success')->first();
            $data = [
                'fullPhoneNumber' => '+91' . $webinarUser->mobile,
                'callbackData' => 'some text here',
                'type' => 'Template',
                'template' => [
                    'name' => $configs->template_name ?? '',
                    'languageCode' => 'en',
                    'headerValues' => [
                        $configs->img_url ?? ''
                    ],
                    'bodyValues' => [
                        $webinarUser->first_name . ' ' . $webinarUser->last_name
                    ]
                ]
            ];

            interakt_message('fintech', $data, $configs->api_key ?? '');

            /* interakt code here starts */
            $data2 = array(
                'phoneNumber' => $webinarUser->mobile,
                'countryCode' => '+91',
                'traits' => array(
                    'name' => $webinarUser->first_name . ' ' . $webinarUser->last_name,
                ),
                'tags' => array('Payment Successful'),
            );
            $restrack1 = user_track($data2);

            $data3 = array(
                'phoneNumber' => $webinarUser->mobile,
                'countryCode' => '+91',
                'event' => 'Payment Successful',
            );
            $restrack2 = event_track($data3);
            /* interakt code ends */

            // SMS for successful registration
            $mobile_no = $webinarUser->mobile;
            // Fetch sender ID
            $senderOption = InfoPages::where('slug', 'webinar-sender-id')->first();
            $sender_id = $senderOption ? $senderOption->content : 'KRDTBZ';

            // Get Offer SMS
            $paymentSmsTemplate = DB::table('sms_list')->where('type', 5)->where('slug', 'payment_successfull')->where('isActive', 1)->first();

            if ($paymentSmsTemplate && $paymentSmsTemplate->message != '#') {
                try {
                    $paymentSmsResult = sendSingleSMS($mobile_no, $paymentSmsTemplate->message, $sender_id);
                } catch (\Exception $e) {
                    Log::error('Payment SMS exception', ['mobile' => $mobile_no, 'error' => $e->getMessage()]);
                }
            }
            /* ===============================
            FACEBOOK CONVERSION TRACKING
            =============================== */

            try {

                $fbleads = FbAdsEntry::where('userid', $webinarUser->id)
                    ->latest('id')
                    ->first();

                $fbdata = [
                    'type'       => 'webinar',
                    'firstname'  => $webinarUser->first_name,
                    'lastname'   => $webinarUser->last_name,
                    'mobile'     => "91" . $webinarUser->mobile,
                    'email'      => strtolower($webinarUser->email),
                    'city'       => $webinarUser->city,
                    'state'      => $webinarUser->state,
                    'zip'        => $webinarUser->pincode,
                    'orderid'    => $paymentData->orderid,
                    'odamount'   => $paymentData->orderamount,
                    'sourceurl'  => 'https://wisemudra.com/webinar/payment-response/true'
                ];

                // Safe fbclid handling
                if ($fbleads && !empty($fbleads->fbclid)) {

                    $fbclidpl = "fb.0." . round(microtime(true) * 1000) . "." . $fbleads->fbclid;

                    $fbdata['fbclid'] = $fbclidpl;
                } else {

                    $fbdata['fbclid'] = '';
                }

                // Send to Facebook Conversion API
                $fbresponse = fbconversioncurl($fbdata, 21);

                // Save response safely
                $dataleads = [
                    'rec_date'      => now(),
                    'send_data'     => json_encode($fbdata),
                    'received_data' => is_array($fbresponse)
                        ? json_encode($fbresponse)
                        : (string) $fbresponse
                ];

                // Update existing FB lead
                if ($fbleads) {

                    FbAdsEntry::where('id', $fbleads->id)
                        ->update($dataleads);
                }
            } catch (\Exception $fbException) {

                Log::error('Webinar FB Conversion Failed', [
                    'message' => $fbException->getMessage(),
                    'line'    => $fbException->getLine(),
                    'file'    => $fbException->getFile(),
                ]);
            }
            /* fb conversion code ends here */

            session([
                'last_payment_reference' => '',
                'last_payment_amount' => $paymentData->orderamount,
                'user_email' => $webinarUser->email,
                'user_mobile' => $webinarUser->mobile,
                'user_firstname' => $webinarUser->first_name,
                'user_lastname' => $webinarUser->last_name
            ]);

            return redirect()->route('webinar.payment.success');
        } catch (\Exception $e) {

            Log::error('An error occured in webinarFreeRegistration()', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect()->route('webinar.payment.success');
        }
    }
    public function webinarThankyou()
    {
        $meta = fintechRegisterMeta();
        return view('front.Webinar.thankyou', compact('meta'));
    }
    public function paymentSuccess()
    {
        // 1. Try session first
        $meta = fintechRegisterMeta();
        $referenceId = session('last_payment_reference');

        if (!session()->has('firstname') || !session()->has('lastname') || !session()->has('mobile_no')) {
            return redirect()->route('webinar.step1');
        }

        if ($referenceId) {
            $conversionData = [
                'referenceId' => $referenceId,
                'value'       => session('last_payment_amount', 0),
                'email'       => strtolower(trim(session('user_email', ''))),
                'phone'       => preg_replace("/[^0-9]/", "", session('user_mobile', '')),
                'firstName'   => strtolower(trim(session('user_firstname', ''))),
                'lastName'    => strtolower(trim(session('user_lastname', ''))),
            ];

            return view('front.Webinar.paymentSuccess', compact('conversionData'));
        }

        // 2. Fallback to DB (no redirect, no failure)
        $payment = ZaakpayEntry::where('entryfor', 98) // webinar = 8
            ->where('statuscode', '100')
            ->orderByDesc('id')
            ->first();

        // If not found in PayU, check PayTM
        if (!$payment) {
            $payment = ZaakpayEntry::where('entryfor', 98) // webinar = 8
                ->where('statuscode', '100')
                ->orderByDesc('id')
                ->first();
        }

        // If not found in PayTM, check Paygic
        if (!$payment) {
            $payment = ZaakpayEntry::where('entryfor', 98) // webinar = 8
                ->where('statuscode', '100')
                ->orderByDesc('id')
                ->first();
        }

        $conversionData = [
            'referenceId' => $payment->referenceid ?? '',
            'value'       => $payment->orderamount ?? 0,
            'email'       => isset($payment->userid) ? strtolower(trim(optional(WebinarRegistration::find($payment->userid))->email)) : '',
            'phone'       => isset($payment->userid) ? preg_replace("/[^0-9]/", "", optional(WebinarRegistration::find($payment->userid))->mobile) : '',
            'firstName'   => isset($payment->userid) ? strtolower(trim(optional(WebinarRegistration::find($payment->userid))->first_name)) : '',
            'lastName'    => isset($payment->userid) ? strtolower(trim(optional(WebinarRegistration::find($payment->userid))->last_name)) : '',
        ];

        $mobile = session('mobile_no');
        $customer = WebinarRegistration::where('mobile', $mobile)->first();

        if ($customer) {
            $userData = WebinarOrder::where('userid', $customer->id)
                ->orderBy('id', 'desc')
                ->first();
        } else {
            $userData = null;
        }

        $webinar = WebinarEvent::where('id', $userData->webinar_id)
            ->where('program_type', 0)
            ->where('isActive', 1)
            ->where('isDelete', 0)
            ->firstOrFail();

        return view('front.Webinar.paymentSuccess', compact('conversionData', 'meta', 'webinar'));
    }
    public function paymentFailed()
    {
        $meta = fintechRegisterMeta();
        return view('front.Webinar.paymentFailed', compact('meta'));
    }

    public function userProcess(Request $request)
    {
        try {
            //dd(route('fintech.process',['id' => encryptData(7848)]));
            // Step 1: Get user ID from URL
            $userId = $request->id ?? null;
            if (!$userId) {
                return redirect()->route('webinar.step1');
            }

            // Step 2: Decrypt the user ID
            $userId = decryptData($userId);

            // Step 3: Find the user
            $userDetail = WebinarRegistration::where('id', $userId)
                ->where('user_from', 0)
                ->first();

            if (!$userDetail) {
                return redirect()->route('webinar.step1');
            }

            // Step 4: Store encrypted user id in session
            Session::put('webinar_user_id', Crypt::encryptString($userId));

            // Step 5: Pre-fill session so fintech step guards pass
            Session::put('firstname',          $userDetail->first_name);
            Session::put('lastname',           $userDetail->last_name);
            Session::put('mobile_no',          $userDetail->mobile);
            Session::put('email',              $userDetail->email);
            Session::put('city',               $userDetail->city);
            Session::put('state',              $userDetail->state);
            Session::put('pincode',            $userDetail->pincode);
            Session::put('current_occupation', $userDetail->current_occupation);
            Session::put('earning_goal',       $userDetail->earning_goal);

            // Step 6: Redirect based on steps
            switch ($userDetail->process_step) {

                case 1:
                    // Name/mobile saved, OTP verified → go to profile step
                    Session::put('otp_verified', true);
                    $nextRoute = 'webinar.step3';
                    break;

                case 2:
                case 3:
                    // Profile saved / payment attempted → go to payment step
                    Session::put('otp_verified',    true);
                    Session::put('step3_completed', true);
                    $nextRoute = 'webinar.step4';
                    break;

                case 4:
                    // Already paid → go to success page
                    Session::put('otp_verified',        true);
                    Session::put('step3_completed',     true);
                    Session::put('last_payment_amount', '');
                    Session::put('user_email',          $userDetail->email);
                    Session::put('user_mobile',         $userDetail->mobile);
                    Session::put('user_first_name',      $userDetail->first_name);
                    Session::put('user_last_name',       $userDetail->last_name);
                    $nextRoute = 'webinar.payment.success';
                    break;

                default:
                    // Unknown state → start from beginning
                    $nextRoute = 'webinar.step1';
                    break;
            }

            return redirect()->route($nextRoute);
        } catch (\Exception $e) {
            Log::error('userProcess (webinar)', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return redirect()->route('webinar.step1');
        }
    }
}
