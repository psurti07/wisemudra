<?php

use App\Models\ApplyLink;
use App\Models\OtpVerification;
use App\Models\UserDocument;
use App\Models\Bank;
use App\Models\UserRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use App\Models\InfoPages;


/* cities list on map */
if (!function_exists('citiesone')) {
    function citiesone()
    {
        $cities = [
            'surat' => 'surat',
            'ahmedabad' => 'ahmedabad',
            'chennai' => 'chennai',
            'coimbatore' => 'coimbatore',
            'madurai' => 'madurai',
            'trichy' => 'trichy',
            'kochi' => 'kochi',
            'mumbai' => 'mumbai',
            'pune' => 'pune',
            'kolkata' => 'kolkata',
            'delhi' => 'delhi',
            'indore' => 'indore',
            'jaipur' => 'jaipur',
            'udaipur' => 'udaipur',
            'hyderabad' => 'hyderabad',
            'lucknow' => 'lucknow',
            'bhopal' => 'bhopal',
            'amritsar' => 'amritsar',
            'vijaywada' => 'vijaywada',
            'bengaluru' => 'bengaluru',
            'kanpur' => 'kanpur',
            'nagpur' => 'nagpur',
            'nashik' => 'nashik',
            'mysore' => 'mysore',
            'vadodara' => 'vadodara',
            'rajkot' => 'rajkot',
            'trivandrum' => 'trivandrum',
            'guwahati' => 'guwahati',
            'gurgaon' => 'gurgaon',
            'meerut' => 'meerut',
            'warangal' => 'warangal',
            'patna' => 'patna',
            'ludhiana' => 'ludhiana',
            'agra' => 'agra',
            'varanasi' => 'varanasi',
            'dehradun' => 'dehradun',
            'ajmer' => 'ajmer',
            'mangalore' => 'mangalore',
            'kozhikode' => 'kozhikode',
            'prayagraj' => 'prayagraj',
            'bilaspur' => 'bilaspur',
            'jamshedpur' => 'jamshedpur',
            'salem' => 'salem',
            'ujjain' => 'ujjain',
            'vapi' => 'vapi',
            'ratnagiri' => 'ratnagiri',
            'ghaziabad' => 'ghaziabad',
        ];
        $chunkedCities = array_chunk($cities, 16);
        return $chunkedCities;
    }
}

/* nbfc banks */
if (!function_exists('nbfcsList')) {
    function nbfcsList()
    {
        $banks = Bank::where('isActive',1)->where('isDelete',0)->get();
        /*$array = [
            'Bajaj Finserv' => 'Bajaj-Finserv.webp',
            'Credi Saison' => 'Credi-saison.webp',
            'Credizy' => 'Credizy.webp',
            'Faircent' => 'Faircent.webp',
            'Fibe' => 'Fibe.webp',
            'Finable' => 'Finable.webp',
            'InvestKraft' => 'InvestKraft.webp',
            'L&T Finance' => 'L&T Finance.webp',
            'Mahindra Finance' => 'Mahindra-Finance.webp',
            'Moneytap' => 'Moneytap.webp',
            'Moneyview' => 'Moneyview.webp',
            'Prefr' => 'Prefr.webp',
            'Rupee Circle' => 'Rupee-Circle.webp',
            'Tata Capital' => 'Tata-Capital.webp',
            'Upscale' => 'Upscele.webp',
            'Upwards' => 'Upwards.webp',
            'Werise' => 'Werise.webp',
        ];*/
        $data = [
            'list' => '',
            'carousel' => ''
        ];
        /*$currentSegment = Request::segment(count(Request::segments()));
        if ($currentSegment === 'self-apply' || $currentSegment === 'loan-details' || $currentSegment === 'loan-agent') {
            unset($array['Credizy'], $array['Credi Saison'], $array['Faircent'], $array['Fibe']);
        }*/
        $loop = 0;
        foreach ($banks as $bank) {
            $data['list'] .= '<div class="col company" data-index="' . $loop . '">
                            <a href="javascript:;" class="in_tool it-1 r-10">
                                <div class="bg--white-100 block-shadow r-10 mb-0">
                                    <img class="img-fluid" src="https://manage.wisemudra.com/public/upload/banks/'.$bank->bank_image.'" alt="' . $bank->bank_name . '" width="auto">
                                </div>
                                <h6 class="s-14 w-700">' . $bank->bank_name . '</h6>
                            </a>
                        </div>';

            $data['carousel'] .= '<div class="bg--white-100 border border-primary r-10 mb-10">
                                    <a href="javascript:;">
                                        <img class="img-fluid" src="https://manage.wisemudra.com/public/upload/banks/'.$bank->bank_image.'" alt="' . $bank->bank_name . '" width="auto">
                                    </a>
                                </div>';
            $loop++;
        }
        return $data;
    }
}

/* raise request faqs */
if (!function_exists('raiseRequestFaqs')) {
    function raiseRequestFaqs()
    {
        return '
            <ul class="accordion">
                <li class="accordion-item mb-10">
                    <div class="accordion-thumb">
                        <h6 class="s-16 w-500">I made the payment but the account is still not created. What should I do?</h6>
                    </div>
                    <div class="accordion-panel">
                        <div class="accordion-panel-item">
                            <div class="faqs-2-answer">
                                <p>
                                This may happen if your amount is held by the payment gateway and yet to be credited to the company’s account. Do not worry; once the funds are credited to the company’s  account, your account will be created and you will be notified via email. Otherwise, the payment gateway will refund your funds in accordance with their policies.</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-item mb-10">
                    <div class="accordion-thumb">
                        <h6 class="s-16 w-500">Even after so many days, I have not received my refund. What should I do?</h6>
                    </div>
                    <div class="accordion-panel">
                        <div class="accordion-panel-item">
                            <div class="faqs-2-answer">
                                <p>
                                    This could happen if your money is held by the bank or payment gateway. It will be refunded as per the bank/payment gateway’s rules and regulations.
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-item mb-10">
                    <div class="accordion-thumb">
                        <h6 class="s-16 w-500">I misunderstood the company’s service/made payment by mistake. Can I get a refund?</h6>
                    </div>
                    <div class="accordion-panel">
                        <div class="accordion-panel-item">
                            <div class="faqs-2-answer">
                                <p>
                                    The subscription plan payment is only refundable under the company’s cancellation and refund policy. <a href="' . route('front.refund.policy') . '">Click here</a> to know more.
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-item mb-10">
                    <div class="accordion-thumb">
                        <h6 class="s-16 w-500">I was shown pre-approved loan offers based on my eligibility, but I did not receive an actual loan. Why?</h6>
                    </div>
                    <div class="accordion-panel">
                        <div class="accordion-panel-item">
                            <div class="faqs-2-answer">
                                <p>
                                    Please read the terms and conditions to get a clear understanding of what a pre-approval loan offer is.<a href="' . route('self.apply.main') . '">Click here.</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-item mb-10">
                    <div class="accordion-thumb">
                        <h6 class="s-16 w-500">Who can get a GST return? </h6>
                    </div>
                    <div class="accordion-panel">
                        <div class="accordion-panel-item">
                            <div class="faqs-2-answer">
                                <p>
                                    Anyone who has updated their GST information in the portal will receive a GST return.
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-item mb-10">
                    <div class="accordion-thumb">
                        <h6 class="s-16 w-500">I have changed my mind and do not want to use the company’s services. Can I get the refund? </h6>
                    </div>
                    <div class="accordion-panel">
                        <div class="accordion-panel-item">
                            <div class="faqs-2-answer">
                                <p>
                                   The subscription plan payment is only refundable under the company’s cancellation and refund policy. 
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-item mb-10">
                    <div class="accordion-thumb">
                        <h6 class="s-16 w-500">I am not happy with the company’s service. What should I do?</h6>
                    </div>
                    <div class="accordion-panel">
                        <div class="accordion-panel-item">
                            <div class="faqs-2-answer">
                                <p>
                                   We request you to kindly call the company on +91-{#VAR#} between 10 AM to 5 PM- Monday to Saturday (only business days). Allow us to discuss your concerns, and we will ensure that you receive the best solutions possible. 
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-item mb-10">
                    <div class="accordion-thumb">
                        <h6 class="s-16 w-500">What happens if I make more than one payment by mistake? Am I eligible for a refund? </h6>
                    </div>
                    <div class="accordion-panel">
                        <div class="accordion-panel-item">
                            <div class="faqs-2-answer">
                                <p>
                                   If a customer makes more than one payment, they are eligible for a refund. You can request a refund within 48 hours of making the payment via the website’s Raising A Request section or by calling the company’s registered contact number.
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-item mb-10">
                    <div class="accordion-thumb">
                        <h6 class="s-16 w-500">Can I get a refund if I purchase subscriptions/memberships from multiple companies in your group of companies?</h6>
                    </div>
                    <div class="accordion-panel">
                        <div class="accordion-panel-item">
                            <div class="faqs-2-answer">
                                <p>
                                   If a customer purchased Subscriptions/Memberships from multiple companies in our group of companies, the customer is eligible for a refund. You can request a refund within 48 hours of payment through the Raising A Request section of the website or by calling the company’s registered contact number.
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        ';
    }
}

/* redirect selfapply */
if (!function_exists('selfapplyurl')) {
    function selfapplyurl($steps)
    {
        $redirectUrl = '';
        switch ($steps) {
            case 2:
                $redirectUrl = 'self.apply.personal.details';
                break;
            case 3:
                $redirectUrl = 'self.apply.get.offers';
                break;
            case 4:
                $redirectUrl = 'self.apply.buyNow';
                break;
            default:
                $redirectUrl = 'self.apply.main';
                break;
        }
        return $redirectUrl;
    }
}

/* redirect loanagent */
if (!function_exists('loanagenturl')) {
    function loanagenturl($steps)
    {
        $redirectUrl = '';
        switch ($steps) {
            case 2:
                $redirectUrl = 'loan.agent.personal.details';
                break;
            case 3:
                $redirectUrl = 'loan.agent.get.offers';
                break;
            case 4:
                $redirectUrl = 'loan.agent.buyNow';
                break;
            default:
                $redirectUrl = 'loan.agent.main';
                break;
        }
        return $redirectUrl;
    }
}

/* redirect loan assistance */
if (!function_exists('loanassistant')) {
    function loanassistant($steps)
    {
        $redirectUrl = '';
        switch ($steps) {
            case 2:
                $redirectUrl = 'loan.assistant.personal.details';
                break;
            case 3:
                $redirectUrl = 'loan.assistant.get.offers';
                break;
            case 4:
                $redirectUrl = 'loan.assistant.buyNow';
                break;
            default:
                $redirectUrl = 'loan.assistant.main';
                break;
        }
        return $redirectUrl;
    }
}

/* get the single user data */
if (!function_exists('singleUserDetails')) {
    function singleUserDetails($data)
    {
        $user = UserRegistration::where($data)->where('isDelete', 0);
        return $user->first();
    }
}

/* count the otp send in 1 day */
if (!function_exists('countOTPs')) {
    function countOTPs($mobile)
    {
        $counts = OtpVerification::whereDate('rec_date', date('Y-m-d'))->where('mobile', $mobile)->count();
        return $counts < 5;
    }
}

/* cookie helper store in cookie */
if (!function_exists('cookieHelper')) {
    function cookieHelper($request, $lifetime)
    {
        // Get UTM parameters from the request with defaults
        $utm_source = $request->input('utm_source', 'web');
        $utm_medium = $request->input('utm_medium', 'direct');
        $utm_campaign = $request->input('utm_campaign', null);
        $utm_referral = $request->input('utm_referral', null);

        // Determine source ID based on source type
        $sourceId = null;
        $source = strtolower($utm_source);

        if ($source === 'google') {
            $sourceId = $request->input('gclid');
        } elseif (in_array($source, ['facebook', 'instagram', 'fb', 'ig', 'meta', 'facebookads', 'instagramads', 'facebook_instagram'])) {
            $sourceId = $request->input('fbclid');
        } elseif ($source === 'taboola') {
            $sourceId = $request->input('tbclid');
        } else {
            $sourceId = $request->input('fbclid') ?? null;
        }

        // Set cookies with standard parameters
        Cookie::queue('utm_source', $utm_source, $lifetime, '/', null, false, true, false, 'lax');
        Cookie::queue('utm_medium', $utm_medium, $lifetime, '/', null, false, true, false, 'lax');
        Cookie::queue('utm_campaign', $utm_campaign, $lifetime, '/', null, false, true, false, 'lax');
        Cookie::queue('utm_referral', $utm_referral, $lifetime, '/', null, false, true, false, 'lax');
        Cookie::queue('sourceId', $sourceId, $lifetime, '/', null, false, true, false, 'lax');

        session(['sourceId' => $sourceId]);
    }
}


 /* checkuserdata */
 if(!function_exists('checkuserdata')){
    function checkuserdata($applyid){
        $details = DB::table('loan_applications as a')
            ->selectRaw('r.id as userid, r.staff_id, r.rec_date, CONCAT(r.first_name," ",r.last_name) as fullname, r.dob, r.first_name, r.last_name, r.pincode, r.mobile, r.email, r.city, r.state, r.isUser, r.process_step, a.id, a.loan_type, a.loan_amount, a.monthly_income, a.currentemi')
            ->join('user_registrations as r','r.id','=','a.userid')
            ->where('a.id', $applyid)->where('r.isDelete',0)->first();
        return $details;
    }
}

/* order data */
if(!function_exists('orderdata')){
    function orderdata($orderid, $tbl){
        return DB::table($tbl)->where('orderid',$orderid)->first();
    }
}

/* check account type is selfapply or loan-agent */
if(!function_exists('accType')){
    function accType(){
        $userId = Auth::user()->id;
        $accType = UserRegistration::where('id',$userId)->pluck('acc_type')->first();
        return $accType;
    }
}

if (!function_exists('render_html')) {
    function render_html($userType)
    {
        $userId = Auth::user()->id;
        $doc = UserDocument::where('userid', $userId)->first();

        $html = '<div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <div id="kt_user_view_details">
                            <h5 class="fw-bold text-uppercase border-bottom pb-5 text-danger">Documents</h5>
                            <div class="pb-5 fs-6 border-bottom">';

        $fields = [
            'aadharcard' => 'Aadhaar Card',
            'pancard' => 'PAN Card',
            'lightbill' => 'Address Proof - Light bill',
            'cancelcheque' => 'Cancel Cheque',
            'bankstatement' => 'Bank Statement - Last 6 months',
        ];
        
        if($userType == 1){
            $fields['formsixteen'] = 'Form Sixteen';
            $fields['salaryslip'] = 'Salary Slip';
        } else {
            $fields['businessproof'] = 'Business Proof';
            $fields['itreturn'] = 'IT Return';
        }

        foreach ($fields as $field => $label) {
            $icon = (isset($doc->$field) && ($doc->$field != null))
                ? '<i class="fa fa-check text-success"></i> '
                : '<i class="fa fa-times text-danger"></i> ';
            $html .= "<div class='fw-bold mt-3'>{$icon}&nbsp;{$label}</div>";
        }

        $html .= '</div>
                            <p class="fw-bold text-uppercase py-2 text-danger">Note : All of the above documents are not mandatory, provide what you have.</p>
                        </div>
                    </div>
                </div>';

        return $html;
    }
}




if (!function_exists('handleFileUpload')) {
    function handleFileUpload($fieldName, $inputName, $userId, $directory, $filePrefix, $document, $file)
    {
        if ($document->$fieldName) {
            if (is_array($document->$fieldName)) {
                foreach (json_decode($document->$fieldName, true) as $oldFile) {
                    $fullOldFilePath = public_path($oldFile);
                    if (file_exists($fullOldFilePath)) {
                        unlink($fullOldFilePath);
                    }
                }
            } else {
                $fullOldFilePath = public_path($document->$fieldName);
                if (file_exists($fullOldFilePath)) {
                    unlink($fullOldFilePath);
                }
            }
        }

        $fullDirectory = public_path("kyc_document/{$userId}/{$directory}");
        if (!file_exists($fullDirectory)) {
            mkdir($fullDirectory, 0755, true);
        }

        $filename = "{$filePrefix}_" . time() . "_{$userId}." . $file->getClientOriginalExtension();

        $file->move($fullDirectory, $filename);

        return "kyc_document/{$userId}/{$directory}/{$filename}";
    }

    if (!function_exists('handleFileUpload')) {
        function handleFileUpload($fieldName, $inputName, $userId, $directory, $filePrefix, $document, $file)
        {
            if ($document->$fieldName) {
                if (is_array($document->$fieldName)) {
                    foreach (json_decode($document->$fieldName, true) as $oldFile) {
                        $fullOldFilePath = public_path($oldFile);
                        if (file_exists($fullOldFilePath)) {
                            unlink($fullOldFilePath);
                        }
                    }
                } else {
                    $fullOldFilePath = public_path($document->$fieldName);
                    if (file_exists($fullOldFilePath)) {
                        unlink($fullOldFilePath);
                    }
                }
            }

            $fullDirectory = public_path("kyc_document/{$userId}/{$directory}");
            if (!file_exists($fullDirectory)) {
                mkdir($fullDirectory, 0755, true);
            }

            $filename = "{$filePrefix}_" . Str::uuid(). "_{$userId}." . $file->getClientOriginalExtension();

            $file->move($fullDirectory, $filename);

            return "kyc_document/{$userId}/{$directory}/{$filename}";
        }
    }
}


if(!function_exists('sendBrevoHtmlMail')){
    function sendBrevoHtmlMail($maildata, $subject = '', $message = '', $sendmail = '', $attachmentPath = ''){
        $data['sender']['name'] = config('constant.APP_NAME');
        $data["sender"]["email"] = config('constant.COMPANY_INFO_MAIL');

        $user_res["name"] = $maildata["fullname"];
        $user_res["email"] = $maildata["email"];
        $userdata[] = $user_res;
        $data["to"] = $userdata;

        $data["subject"] = $subject;
        $data["htmlContent"] = $message;
        if ($attachmentPath && file_exists($attachmentPath)) {
            $fileData = file_get_contents($attachmentPath);
            $fileName = basename($attachmentPath);
            $base64File = base64_encode($fileData);

            $data["attachment"] = [
                [
                    "content" => $base64File,
                    "name" => $fileName
                ]
            ];
        }

        // Turn Data to JSON
        $data_json = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.brevo.com/v3/smtp/email",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "api-key: ".config('constant.BREVO_API_KEY')
                ],
            )
        );
        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        return true;
    }
}

if(!function_exists('sendBrevoHtmlMail2')){
    function sendBrevoHtmlMail2($maildata, $subject = '', $message = '', $sendmail = '', $attachments = []){
        $data['sender']['name'] = config('constant.APP_NAME');
        $data["sender"]["email"] = config('constant.COMPANY_INFO_MAIL');

        $user_res["name"] = $maildata["fullname"];
        $user_res["email"] = $maildata["email"];
        $userdata[] = $user_res;
        $data["to"] = $userdata;

        $data["subject"] = $subject;
        $data["htmlContent"] = $message;

        /*$base64File = base64_encode($attachmentPath);

        $data["attachment"] = [
            [
                "content" => $base64File,
                "name" => 'Invoice.pdf'
            ]
        ];*/

        // Attachments (already base64 encoded)
        if (!empty($attachments)) {
            $data['attachment'] = $attachments;
        }

        // Turn Data to JSON
        $data_json = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.brevo.com/v3/smtp/email",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "api-key: ".config('constant.BREVO_API_KEY')
                ],
            )
        );
        $response = curl_exec($curl);
        $err = curl_error($curl);
        Log::info('sendBrevoHtmlMail2 response : ' . $response);
        Log::info('sendBrevoHtmlMail2 error : ' . $err);

        curl_close(handle: $curl);

        return true;
    }
}

if(!function_exists('assignAgent')){
    function assignAgent(){
        $lastAgentId = DB::table('site_options')->where('option_key','last_agent_id')->first()->option_value;
        $agentIds = \App\Models\Administrations::where('role', 2)->where(['isActive' => 1, 'isDelete' => 0])->pluck('id')->toArray();
        $nextAgentId = 0;
        if (!empty($agentIds)) {
            if ($lastAgentId > 0 && in_array($lastAgentId, $agentIds)) {
                $lastIndex = array_search($lastAgentId, $agentIds);
                $nextIndex = ($lastIndex + 1) % count($agentIds);
                $nextAgentId = $agentIds[$nextIndex];
            } else {
                // First time assignment or invalid lastAgentId
                $nextAgentId = $agentIds[0]; // Start with the first agent
            }

            $staff = \App\Models\Administrations::where('id', $nextAgentId)
                ->limit(1)
                ->first();
            $lastAgentIdUpdate = DB::table('site_options')->where('option_key','last_agent_id')->update(['option_value'=>$nextAgentId]);
            return $staff;
        }
         // If no agent found or $staff is null, return 0
        return 0;
    }
}

if (!function_exists('assignAgentSelf')) {
    function assignAgentSelf() {
        $lastAgentIdRow = DB::table('site_options')->where('option_key', 'last_self_agent_id')->first();
        $lastAgentId = $lastAgentIdRow ? $lastAgentIdRow->option_value : 0;

        $agentIds = \App\Models\Administrations::where('role', 5)
            ->where(['isActive' => 1, 'isDelete' => 0])
            ->pluck('id')
            ->toArray();

        $nextAgentId = 0;

        if (!empty($agentIds)) {
            if ($lastAgentId > 0 && in_array($lastAgentId, $agentIds)) {
                $lastIndex = array_search($lastAgentId, $agentIds);
                $nextIndex = ($lastIndex + 1) % count($agentIds);
                $nextAgentId = $agentIds[$nextIndex];
            } else {
                $nextAgentId = $agentIds[0]; // Start with the first agent
            }

            $staff = \App\Models\Administrations::where('id', $nextAgentId)
                ->limit(1)
                ->first();

            if ($staff) {
                DB::table('site_options')
                    ->where('option_key', 'last_self_agent_id')
                    ->update(['option_value' => $nextAgentId]);
                return $staff;
            }
        }

        // If no agent found or $staff is null, return 0
        return 0;
    }
}

    if(!function_exists('assignAssistant')){
        function assignAssistant(){
            $lastAssistantId = DB::table('site_options')->where('option_key','last_assistant_id')->first()->option_value;
            $assistantIds = \App\Models\Administrations::where('role', 7)->where(['isActive' => 1, 'isDelete' => 0])->pluck('id')->toArray();
            
            $nextAssistantId = 0;
            
            if (!empty($assistantIds)) {
                if ($lastAssistantId > 0 && in_array($lastAssistantId, $assistantIds)) {
                    $lastIndex = array_search($lastAssistantId, $assistantIds);
                    $nextIndex = ($lastIndex + 1) % count($assistantIds);
                    $nextAssistantId = $assistantIds[$nextIndex];
                } else {
                    // First time assignment or invalid lastassistantId
                    $nextassistantId = $assistantIds[0]; // Start with the first assistant
                }
                
                $staff = \App\Models\Administrations::where('id', $nextassistantId)
                    ->limit(1)
                    ->first();
                
                if ($staff) {
                    DB::table('site_options')
                        ->where('option_key', 'last_assistant_id')
                        ->update(['option_value' => $nextAssistantId]);
                    return $staff;
                }
            }
            
            // If no assistant found or $staff is null, return 0
            return 0;
        }
    }


if(!function_exists('sendPaymentGreetings')){
    function sendPaymentGreetings($fullname, $mobile, $email){
        /*if($mobile != '') {
            $smsmessage = "Dear Customer, Congratulations! Your loan application has been successfully submitted. Our Customer Executive will call you shortly. Thanks, Wisemudra";
            $smsresponse = sendtextSMSobb($mobile, $smsmessage, 'main');
        }*/
        if($email != '') {
            // Send email
            $subject = "Welcome to Wisemudra";
            $content = view('mail.simpleEmailTemplate',compact('fullname'))->render();
            if($content != '') {
                $maildata = array(
                    'fullname' => $fullname,
                    'email' => $email,
                    'mobile' => $mobile
                );
                $mailresponse = sendBrevoHtmlMail($maildata, $subject, $content, 5);
            }
        }

        return TRUE;
    }
}

if(!function_exists('sendForgetPassword')){
    function sendForgetPassword($fullname, $mobile, $email, $pswd){
        /*if($mobile != '') {
            $smsmessage = "Dear Customer, Congratulations! Your loan application has been successfully submitted. Our Customer Executive will call you shortly. Thanks, Wisemudra";
            $smsresponse = sendtextSMSobb($mobile, $smsmessage, 'main');
        }*/
        if($email != '') {
            // Send email
            $subject = "New Password Set for Your Account";
            $content = view('mail.forgetPassword',compact('fullname', 'pswd'))->render();
            if($content != '') {
                $maildata = array(
                    'fullname' => $fullname,
                    'email' => $email,
                    'mobile' => $mobile
                );
                $mailresponse = sendBrevoHtmlMail($maildata, $subject, $content, 5);
            }
        }

        return TRUE;
    }
}

if(!function_exists('getFacebookDomainVerificationId')){
    function getFacebookDomainVerificationId(){
        return InfoPages::where('slug','sa_facebookdomain')->first()->content;
    }
}

if(!function_exists('getFacebookPixelKey')){
    function getFacebookPixelKey(){
        $url = request()->segment(1);
        $pxKey = ($url == 'loan-agent') ? 'la_facebookpixelkey' : 'sa_facebookpixelkey';
        return InfoPages::where('slug',$pxKey)->first()->content;
    }
}


if(!function_exists('getFBConversionData')){
    function getFBConversionData($type){
        $arr_data = [];
        if($type == 'self-apply'){
            $data = InfoPages::whereIn('slug',['sa_facebookaccesstoken','sa_facebookeventname','sa_facebookeventid'])->get()->pluck('content','slug');
            $arr_data[] = [
                'fbaccesstoken' => $data['sa_facebookaccesstoken'] ?? '',
                'fbeventname' => $data['sa_facebookeventname'] ?? '',
                'fbeventid' => $data['sa_facebookeventid'] ?? ''
            ];
        } else if($type == 'hire-agent'){
            $data = InfoPages::whereIn('slug',['la_facebookaccesstoken','la_facebookeventname','la_facebookeventid'])->get()->pluck('content','slug');
            $arr_data[] = [
                'fbaccesstoken' => $data['la_facebookaccesstoken'] ?? '',
                'fbeventname' => $data['la_facebookeventname'] ?? '',
                'fbeventid' => $data['la_facebookeventid'] ?? ''
            ];
        } else {
            $arr_data['fbaccesstoken'] = $arr_data['fbeventname'] = $arr_data['fbeventid'] = '';
        }
        return $arr_data;
    }
}

if (!function_exists('fbconversioncurl')) {
    function fbconversioncurl($userdata, $ver = 21)
    {
        $FBConversionData = getFBConversionData($userdata['type']);
        $fbaccesstoken = $FBConversionData[0]['fbaccesstoken'];
        $eventname = $FBConversionData[0]['fbeventname'];
        $eventid = $FBConversionData[0]['fbeventid'];

        /* purchasde data */
        $data = array();

        $data["event_name"] = $eventname;
        $data["event_time"] = round(microtime(true));
        $data["event_id"] = $eventid;
        $data["event_source_url"] = $userdata['sourceurl'];
        $data["action_source"] = "website";

        $fnarr[] = hash("sha256", $userdata['firstname']);
        $data["user_data"]["fn"] = $fnarr;

        $lnarr[] = hash("sha256", $userdata['lastname']);
        $data["user_data"]["ln"] = $lnarr;

        $emarr[] = hash("sha256", $userdata['email']);
        $data["user_data"]["em"] = $emarr;

        $pharr[] = hash("sha256", $userdata['mobile']);
        $data["user_data"]["ph"] = $pharr;

        $ctarr[] = hash("sha256", $userdata['city']);
        $data["user_data"]["ct"] = $ctarr;

        /*$dbarr[] = hash("sha256", $userdata['dob']);
    	$data["user_data"]["db"] = $dbarr;*/

        $statearr[] = hash("sha256", $userdata['state']);
        $data["user_data"]["st"] = $statearr;

        $zparr[] = hash("sha256", $userdata['zip']);
        $data["user_data"]["zp"] = $zparr;

        $countryarr[] = hash("sha256", "in");
        $data["user_data"]["country"] = $countryarr;

        $data["user_data"]["client_ip_address"] = request()->ip();
        $data["user_data"]["client_user_agent"] = request()->userAgent();

        if ($userdata['fbclid'] != "") {
            $data["user_data"]["fbc"] = $userdata['fbclid'];
        }
        $orderAmount = $userdata['odamount'] / (1 + (18 / 100));
        
        if($ver == 11 || $ver == 16){
            /* v11 code starts here */
            $contents["id"] = "MSF2026";
            $contents["quantity"] = 1;
            $data["contents"][] = $contents;

            $data["custom_data"]["currency"] = "INR";
            $data["custom_data"]["value"] = formatePriceIndia($orderAmount);
            $data["custom_data"]["order_id"] = $userdata['orderid'];
            /* v11 code ends here */
        } else {
            /* v21 code starts here */
            $data["custom_data"]["currency"] = "INR";
            $data["custom_data"]["value"] = formatePriceIndia($orderAmount);
            $data["custom_data"]["num_items"] = 1;
            $data["custom_data"]["content_type"] = "product";
            $data["custom_data"]["order_id"] = $userdata['orderid'];
            $data["custom_data"]["status"] = "registered";

            $contents["id"] = "MSF2026";
            $contents["quantity"] = 1;
            $contents["item_price"] = formatePriceIndia($orderAmount);
            $data["custom_data"]["contents"] = array($contents);
            /* v21 code ends here */
        }

        $data_json = json_encode(array($data));

        if ($userdata['type'] == 'self-apply') {
            $fbpixel = InfoPages::where('slug', 'sa_facebookpixelkey')->first()->content;
            $accesstoken = $fbaccesstoken;
        } else if ($userdata['type'] == 'hire-agent') {
            $fbpixel = InfoPages::where('slug', 'la_facebookpixelkey')->first()->content;
            $accesstoken = $fbaccesstoken;
        } else {
            $fbpixel = '';
            $accesstoken = $fbaccesstoken;
        }

        // Fill available fields
        $fields = array();
        $fields['access_token'] = $accesstoken;
        $fields['upload_tag'] = "orders"; // You should set a tag here (feel free to adjust)
        $fields['data'] = $data_json;

        $curlUrl = "https://graph.facebook.com/v{$ver}.0/{$fbpixel}/events";
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $curlUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                //"content-type: multipart/form-data",
                "Accept: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        Log::info('Facebook Conversion API Response', [
            'response' => $response,
            'decoded_response' => json_decode($response, true),
            'curl_error' => $err,
            'curl_errno' => curl_errno($curl),
            'http_code' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
        ]);
                
        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
        //return $response;
    }
}

if (!function_exists('getWebinarFacebookDomainVerificationId')) {
    function getWebinarFacebookDomainVerificationId()
    {
        return InfoPages::where('slug', 'webinar_facebookdomain')->first()->content;
    }
}

if (!function_exists('getWebinarFacebookPixelKey')) {
    function getWebinarFacebookPixelKey()
    {
        $url = request()->segment(1);
        $pxKey = 'webinar_facebookpixelkey';
        return InfoPages::where('slug', $pxKey)->first()->content;
    }
}

if (!function_exists('getwebinarFBConversionData')) {
    function getwebinarFBConversionData($type)
    {
        $arr_data = [];
        if ($type == 'webinar') {
            $data = InfoPages::whereIn('slug', ['webinar_facebookaccesstoken', 'webinar_facebookeventname', 'webinar_facebookeventid'])->get()->pluck('content', 'slug');
            $arr_data[] = [
                'fbaccesstoken' => $data['webinar_facebookaccesstoken'] ?? '',
                'fbeventname' => $data['webinar_facebookeventname'] ?? '',
                'fbeventid' => $data['webinar_facebookeventid'] ?? ''
            ];
        } else {
            $arr_data['fbaccesstoken'] = $arr_data['fbeventname'] = $arr_data['fbeventid'] = '';
        }
        return $arr_data;
    }
}

if (!function_exists('fbwebinarconversioncurl')) {
    function fbwebinarconversioncurl($userdata, $ver = 21)
    {
        $FBConversionData = getWebinarFBConversionData($userdata['type']);
        $fbaccesstoken = $FBConversionData[0]['fbaccesstoken'];
        $eventname = $FBConversionData[0]['fbeventname'];
        $eventid = $FBConversionData[0]['fbeventid'];

        /* purchasde data */
        $data = array();

        $data["event_name"] = $eventname;
        $data["event_time"] = round(microtime(true));
        $data["event_id"] = $eventid;
        $data["event_source_url"] = $userdata['sourceurl'];
        $data["action_source"] = "website";

        $fnarr[] = hash("sha256", $userdata['firstname']);
        $data["user_data"]["fn"] = $fnarr;

        $lnarr[] = hash("sha256", $userdata['lastname']);
        $data["user_data"]["ln"] = $lnarr;

        $emarr[] = hash("sha256", $userdata['email']);
        $data["user_data"]["em"] = $emarr;

        $pharr[] = hash("sha256", $userdata['mobile']);
        $data["user_data"]["ph"] = $pharr;

        $ctarr[] = hash("sha256", $userdata['city']);
        $data["user_data"]["ct"] = $ctarr;

        /*$dbarr[] = hash("sha256", $userdata['dob']);
    	$data["user_data"]["db"] = $dbarr;*/

        $statearr[] = hash("sha256", $userdata['state']);
        $data["user_data"]["st"] = $statearr;

        $zparr[] = hash("sha256", $userdata['zip']);
        $data["user_data"]["zp"] = $zparr;

        $countryarr[] = hash("sha256", "in");
        $data["user_data"]["country"] = $countryarr;

        $data["user_data"]["client_ip_address"] = request()->ip();
        $data["user_data"]["client_user_agent"] = request()->userAgent();

        if ($userdata['fbclid'] != "") {
            $data["user_data"]["fbc"] = $userdata['fbclid'];
        }

        $orderAmount = $userdata['odamount'] / (1 + (18 / 100));

        /* v11 code starts here */
        $contents["id"] = "KB2025";
        $contents["quantity"] = 1;
        $data["contents"][] = $contents;

        $data["custom_data"]["currency"] = "INR";
        $data["custom_data"]["value"] = formatePriceIndia($orderAmount);
        $data["custom_data"]["order_id"] = $userdata['orderid'];
        /* v11 code ends here */

        $data_json = json_encode(array($data));

        if ($userdata['type'] == 'webinar') {
            $fbpixel = InfoPages::where('slug', 'webinar_facebookpixelkey')->first()->content;
            $accesstoken = $fbaccesstoken;
        } else {
            $fbpixel = '';
            $accesstoken = $fbaccesstoken;
        }

        // Fill available fields
        $fields = array();
        $fields['access_token'] = $accesstoken;
        $fields['upload_tag'] = "orders"; // You should set a tag here (feel free to adjust)
        $fields['data'] = $data_json;

        if ($ver == 11) {
            $curlUrl = "https://graph.facebook.com/v11.0/{$fbpixel}/events";
        } elseif ($ver == 16) {
            $curlUrl = "https://graph.facebook.com/v16.0/{$fbpixel}/events";
        } elseif ($ver == 21) {
            $curlUrl = "https://graph.facebook.com/v21.0/{$fbpixel}/events";
        } elseif ($ver == 23) {
            $curlUrl = "https://graph.facebook.com/v23.0/{$fbpixel}/events";
        } else {
            $curlUrl = "https://graph.facebook.com/v21.0/{$fbpixel}/events";
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $curlUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                //"content-type: multipart/form-data",
                "Accept: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
        //return $response;
    }
}


if(!function_exists('offersBankList')){
    function offersBankList($monthlyIncome, $userType, $loanAmount){
        try{
            $criteriaId = 0;
            switch($monthlyIncome) {
                case ($monthlyIncome >= 0 && $monthlyIncome <= 15000 && $userType == 1):
                    $criteriaId = 1;
                    break;
                case ($monthlyIncome >= 0 && $monthlyIncome <= 15000 && $userType == 2):
                    $criteriaId = 2;
                    break;
                case ($monthlyIncome > 15000 && $monthlyIncome <= 25000 && $userType == 1):
                    $criteriaId = 3;
                    break;
                case ($monthlyIncome > 15000 && $monthlyIncome <= 25000 && $userType == 2):
                    $criteriaId = 4;
                    break;
                case ($monthlyIncome > 25000 && $userType == 1):
                    $criteriaId = 5;
                    break;
                case ($monthlyIncome > 25000 && $userType == 2):
                    $criteriaId = 6;
                    break;
                default:
                    $criteriaId = 0;
                    break;
            }

            $maxAttempts = 5;
            $attempt = 0;

            $applyLinkIds = DB::table('applylink_criteria as ac')
                ->join('bankapplylink as ba', 'ba.id', '=', 'ac.applylink_id')
                ->where('ba.isDelete', 0)->where('ba.is_recommended', 0)
                ->where('ac.criteria_id', $criteriaId)
                ->inRandomOrder()
                ->limit(4)
                ->pluck('applylink_id');

            $recommended = ApplyLink::with('bank')->where('is_recommended',1)->where('isDelete', 0)->inRandomOrder()->limit(1)->get();
            $noRecommended = ApplyLink::with('bank')->whereIn('id', $applyLinkIds)->where('isDelete', 0)->get();
            $banks = $recommended->merge($noRecommended);

            $jsonData = [];
            foreach($banks as $applyLink){
                $jsonData[] = [
                    'apply_id' => $applyLink->id,
                    'rec_date' => $applyLink->rec_date,
                    'bankid' => $applyLink->bankid,
                    'roi' => $applyLink->roi,
                    'bank_name' => $applyLink->bank->bank_name,
                    'bank_image' => $applyLink->bank->bank_image,
                    'tenures' => $applyLink->tenures,
                    'option1' => $applyLink->option1,
                    'option2' => $applyLink->option2,
                    'option3' => $applyLink->option3,
                    'option4' => $applyLink->option4,
                    'option5' => $applyLink->option5,
                    'title' => $applyLink->title,
                    'applyurl' => $applyLink->applyurl,
                    'loanAmount' => $loanAmount,
                    'is_recommended' => $applyLink->is_recommended
                ];
            }
            return json_encode($jsonData, true);
        } catch(\Exception $e){
            dd($e->getMessage());
        }
    }
}
