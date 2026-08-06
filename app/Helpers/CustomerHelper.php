<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UserRegistration;
use App\Models\MembershipOrder;
use App\Models\Product;
use App\Models\SiteOption;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

    /* isRouteActive check and set the class in navigation as active */
    if(!function_exists('isRouteActive')){
        function isRouteActive($routeName){
            return request()->routeIs($routeName) ? 'active' : '';
        }
    }

    /* customer dashboard message */
    if(!function_exists('custDashboard')){
        function custDashboard($process){
            switch ($process) {
                case '4':
                    $data = '<div class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex align-items-center">
                                    <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="40">0</div>
                                    %
                                </div>
                                <span class="text-gray-600 fw-semibold fs-5 lh-0">Membership<br/>Purchased</span>';
                    break;
                case '5':
                    $data = '<div class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex align-items-center">
                                    <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="50">0</div>
                                    %
                                </div>
                                <span class="text-gray-600 fw-semibold fs-5 lh-0">User<br/>Verified</span>';
                    break;
                case '6':
                    $data = '<div class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex align-items-center">
                                    <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="60">0</div>
                                    %
                                </div>
                                <span class="text-gray-600 fw-semibold fs-5 lh-0">Documents<br/>Verified</span>';
                    break;
                case '7':
                case '8':
                case '9':
                    $data = '<div class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex align-items-center">
                                    <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="80">0</div>
                                    %
                                </div>
                                <span class="text-gray-600 fw-semibold fs-5 lh-0">Application In<br/>Process</span>';
                    break;
                case '10':
                    $data = '<div class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex align-items-center">
                                    <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="100">0</div>
                                    %
                                </div>
                                <span class="text-gray-600 fw-semibold fs-5 lh-0">Application<br/>Rejected</span>';
                    break;
                case '11':
                    $data = '<div class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex align-items-center">
                                    <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="100">0</div>
                                    %
                                </div>
                                <span class="text-gray-600 fw-semibold fs-5 lh-0">Application<br/>Approved</span>';
                    break;
                default:
                    $data = '<div class="fs-lg-2hx fs-2x fw-bold text-gray-800 d-flex align-items-center">
                                    <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="40">0</div>
                                    %
                                </div>
                                <span class="text-gray-600 fw-semibold fs-5 lh-0">Membership<br/>Purchased</span>';
                    break;
            }
            return $data;
        }
    }

    if(!function_exists('loanEmiCalculator')){
        function loanEmiCalculator($principal,$roi,$tenure){
            $emi = 0;
            $roi = ($roi / 12) / 100;
            $emi = ($principal * $roi * pow(1 + $roi, $tenure)) / (pow(1 + $roi, $tenure) - 1);
            return ceil($emi);
        }
    }

    if(!function_exists('displayDate')){
        function displayDate($date) {
            if($date=="0000-00-00") {
                $dis_date = "-";
            } else {
                $dis_date = date("d/m/Y",strtotime($date));
            }
            return $dis_date;
        }
    }

    if(!function_exists('displayTime')){
        function displayTime($time){
            $dis_time = date("h:i A",strtotime($time));
            return $dis_time;
        }
    }

    if(!function_exists('dateDiffInMinutes')){
        function dateDiffInMinutes($date1, $date2) {
            // Calulating the difference in timestamps
            if($date1!="" && $date2!="") {
                $difference = strtotime($date2) - strtotime($date1);
                /*$minutes = floor($difference / 60);
                $out = floor($minutes / 60).':'.($minutes -   floor($minutes / 60) * 60);*/
                $out = abs(round($difference / 60));
            }
            else {
                $out = "-";
            }
            return $out;
        }
    }

    if(!function_exists('dateDiffInDays')){
        function dateDiffInDays($date1, $date2) {
            // Calulating the difference in timestamps
            if($date1!="" && $date2!="") {
                $difference = strtotime($date2) - strtotime($date1);
                /*$minutes = floor($difference / 60);
                $out = floor($minutes / 60).':'.($minutes -   floor($minutes / 60) * 60);*/
                $out = abs(round($difference / 86400));
            }
            else {
                $out = "-";
            }
            return $out;
        }
    }
    
    
    if(!function_exists('convertIntoCustomer')){
		function convertIntoCustomer($cardno, $userData, $cardData, $orderAmount, $txnId, $accType, $slug, $prefix, $offerpage) {
		    try {
                $password = trim(random_code(6));
                $fullname = $cardData->first_name . ' ' . $cardData->last_name;
                $grandtotal = $netamount = $cgstamount = $sgstamount = $igstamount = 0;
        
                DB::beginTransaction();
                
                try {
                    // 1. Create Membership Order
                    $existingMembership = MembershipOrder::where('userid', $userData->id)->first();
                    if (!$existingMembership) {
                        $membershipData = [
                            'rec_date' => now(),
                            'userid' => $userData->id,
                            'registration_date' => date('Y-m-d'),
                            'expiry_date' => date('Y-m-d', strtotime('+3 months')),
                            'card_number' => $cardno,
                            'amount' => $orderAmount,
                            'paymentid' => $txnId,
                            'isActive' => 1,
                            'isDelete' => 0
                        ];
                        
                        $membership = MembershipOrder::create($membershipData);
                        $membershipId = $membership->id;    
                    } else {
                        $membershipId = $existingMembership->id;
                    }
                    
                    
                    // 2. Update User Info
                    $passwordkey = Hash::make($password);
                    $refcode = strtolower(substr(str_replace(" ", "", $fullname), 0, 3)) . substr($userData->mobile, -4);
        
                    $regData = [
                        'rec_date' => now(),
                        'update_date' => now(),
                        'offerpage' => $offerpage,
                        'first_name' => $cardData->first_name,
                        'last_name' => $cardData->last_name,
                        'email' => $cardData->emailid,
                        'password' => $passwordkey,
                        'refcode' => $refcode,
                        'isUser' => 2,
                        'process_step' => 5,
                        'acc_type' => $accType
                    ];
                    
                    UserRegistration::where('id', $userData->id)->update($regData);
        
                    // 3. Get Product Info
                    $productData = Product::where('productslug', $slug)->first();
                    
                    if (!$productData) {
                        DB::rollBack();
                        Log::error('Product not found with slug: ' . $slug);
                        return false;
                    }
                    
                    $netamount = ($productData->inOffer == 1) ? $productData->offeramount : $productData->amount;
                    
                    // 4. Tax Calculation
                    if ($userData->state == 'Gujarat') {
                        $cgstamount = $netamount * 0.09;
                        $sgstamount = $netamount * 0.09;
                    } else {
                        $igstamount = $netamount * 0.18;
                    }
                    $grandtotal = $netamount + $cgstamount + $sgstamount + $igstamount;
                    
                    // 5. Invoice Creation
                    $invoiceNo = SiteOption::where('option_key', 'newinvoiceno')->lockForUpdate()->first();
                    
                    if (!$invoiceNo) {
                        DB::rollBack();
                        Log::error('Invoice number setting not found.');
                        return false;
                    }
        
                    $existingInvoice = Invoice::where('userid', $userData->id)
                        ->where('cardid', $membershipId)
                        ->first();
                    
                    if (!$existingInvoice) {
                        $invData3 = [
                            'rec_date' => now(),
                            'userid' => $userData->id,
                            'cardid' => $membershipId,
                            'inv_prefix' => $prefix,
                            'inv_number' => $invoiceNo->option_value,
                            'inv_date' => date('Y-m-d'),
                            'inv_price' => $netamount,
                            'inv_cgst' => $cgstamount,
                            'inv_sgst' => $sgstamount,
                            'inv_igst' => $igstamount,
                            'inv_grandtotal' => $grandtotal,
                            'isdelete' => 0
                        ];
                        
                        Invoice::create($invData3);
        
                        // 6. Increment Invoice Number
                        SiteOption::where('option_key', 'newinvoiceno')->update([
                            'rec_date' => now(),
                            'option_value' => $invoiceNo->option_value + 1
                        ]);    

                    }
                    
                    // 7. Assign Staff
                    $staffID = (($accType == 2) ? assignAgent() : (($accType == 3) ? assignAssistant() : assignAgentSelf()));
                    
                    UserRegistration::where('id', $userData->id)->update(['staff_id' => $staffID->id]);
        
                    DB::commit();
                    $remote_data = array(
						'company_code' => config('constant.COMPANY_CODE'),
						'company_local_ip' => '190.92.174.183',
						'product_code' => (($accType == 1) ? config('constant.PRODUCT_CODE_SELFAPPLY') : (($accType == 2) ? config('constant.PRODUCT_CODE_LOANAGENT') : config('constant.PRODUCT_CODE_SELFAPPLY') )),
						'customer_name' => $cardData->first_name.' '.$userData->last_name,
						'customer_email' => $cardData->emailid,
						'customer_mobile' => $userData->mobile,
						'userid' => $userData->id,
						'card_number' => $cardno,
						'rec_date' => now()->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
						'inv_prefix' => $prefix,
						'inv_number' => $invoiceNo->option_value,
						'inv_date' => now()->setTimezone(config('app.timezone'))->format('Y-m-d'),
						'inv_price' => $netamount,
						'inv_cgst' => $cgstamount,
						'inv_sgst' => $sgstamount,
						'inv_igst' => $igstamount,
						'inv_grandtotal' => $grandtotal,
					);
                    $api_response = sendOrderData(json_encode($remote_data));
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Transaction failed in convertIntoCustomer: ' . $e->getMessage());
                    return false;
                }
        
                // ------------------------------
                // MAIL SENDING SECTION (Only after successful DB transaction)
                // ------------------------------
                if (session('isMailSend') === true && session('cardno') === $cardno) {
                    Log::info("Mail already sent for card number: $cardno");
                    return true;
                }
                try
                {
                    $mailData = [
                        'fullname' => $fullname,
                        'mobile' => $userData->mobile,
                        'email' => $cardData->emailid,
                        'password' => $password,
                        'order_number' => $invoiceNo->option_value,
                        'order_date' => date('d-m-Y'),
                        'order_amount' => $grandtotal,
                        'transactionId' => $txnId
                    ];
                    
                    if ($accType == 2 || $accType == 3) {
                        $mailData['agentName'] = $staffID->fullname;
                        $mailData['agentMobile'] = $staffID->mobile;
                    }
            
                    
                    $greetingsFile = ($accType == 2 || $accType == 3) ? 'mail.welcomeGreetingsla' : 'mail.welcomeGreetings';
                    $sendGreetings = view($greetingsFile, $mailData)->render();
            
                    $invAttach = array_merge($invData3, [
                        'fullname' => $fullname,
                        'city' => $userData->city,
                        'mobile' => $userData->mobile,
                        'email' => $cardData->emailid,
                        'acc_type' => $accType,
                        'state' => $userData->state,
                        'isCustomer' => 0,
                        'card_number' => $membershipData['card_number'],
                        'registration_date' => $membershipData['registration_date'],
                        'expiry_date' => $membershipData['expiry_date'],
                        'paymentid' => $membershipData['paymentid'],
                    ]);
                    
                    $invoiceData = view('mail.invoice', $invAttach)->render();
                    
                    $pdf = Pdf::loadHTML($invoiceData)->setPaper('A4', 'portrait')->output();
                    $base64Pdf = base64_encode($pdf);
            
                    // Optional PDF Attachment
                    $attachments = [
                        [
                            'content' => $base64Pdf,
                            'name' => 'Invoice.pdf'
                        ]
                    ];
                    
                    $subject = (($accType == 2)
                        ? 'Congratulations! Payment for Wisemudra’s Hire Agent plan has been successful.'
                        : (($accType == 3) 
                        ? 'Congratulations! Payment for Wisemudra’s Self-Apply plan has been successful.'
                        : 'Congratulations! Payment Successful for Wisemudra’s Self-Apply Plan.'));
            
                    sendBrevoHtmlMail2($mailData, $subject, $sendGreetings, 3, $attachments);
                    session(['isMailSend'=>true, 'cardno'=> $cardno]);
                    return true;   
                } catch(\Exception $e){
                    Log::error('Mail sending failed: ' . $e->getMessage());
                    return true;    
                }
            } catch (\Exception $e) {
                Log::error('convertIntoCustomer helper function failed: ' . $e->getMessage());
                return false;
            }
        }

	}
