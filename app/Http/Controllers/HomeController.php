<?php

namespace App\Http\Controllers;

use App\Models\CareerEnquiry;
use App\Models\Careers;
use App\Models\InfoPages;
use Illuminate\Http\Request;
use App\Models\ContactEnquiry;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function home(){
        $meta = homeMeta();
        $msg = InfoPages::where('slug','welcome-message')->select('content','status')->first();
        return view('front.home',compact('meta','msg'));
    }

    public function creditScore(){
        $meta = creditScoreMeta();
        return view('front.creditScore',compact('meta'));
    }

    public function faqs(){
        $meta = faqsMeta();
        return view('front.faqs',compact('meta'));
    }

    public function service(){
        $meta = serviceMeta();
        return view('front.service',compact('meta'));
    }

    public function contactUs(){
        $meta = contactUsMeta();
        return view('front.contactUs',compact('meta'));
    }

    public function contactUsStore(Request $request){
        $input = $request->all();
        $validator = $request->validate([
            'fullname' => 'required',
            'email' => 'required|email',
            'mobile' => ['required','numeric', 'regex:/^[6-9]\d{9}$/'],
            'subject' => 'required',
            'desc' => 'required',
        ],[
            'desc.required' => 'The message field is required'
        ]);
        $newinput = [
            'fullname' => $input['fullname'],
            'email' => strtolower($input['email']),
            'mobile' => $input['mobile'],
            'subject' => ucwords(strtolower($input['subject'])),
            'message' => $input['desc'],
            'server_ip' => $request->ip()
        ];
        $result = ContactEnquiry::create($newinput);
        $message = 'Contact form successfully submitted.';
        if($result){
            return response()->json(array('type' => 'SUCCESS', 'message' => $message, 'data' => $result));
        } else {
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.', 'data' => []));
        }
    }

    public function career(){
        $meta = careerMeta();
        $data = Careers::where(['isActive'=>1, 'isDelete'=>0])->get();
        return view('front.career',compact('meta','data'));
    }

    public function applycareer($code){
        $meta = careerMeta();
        $data = Careers::where(['slug'=>$code])->first();
        return view('front.applycareer',compact('meta','data'));
    }

    public function storeCareer(Request $request){
        $input = $request->all();
        $validator = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'mobile' => ['required','numeric', 'regex:/^[6-9]\d{9}$/'/*,Rule::unique('career_enquiries', 'mobile')->ignore(auth()->id())*/],
            'city' => 'required',
            'qualifications' => 'required',
            'experience' => 'required',
            'keyskills' => 'required',
            'resume' => 'mimes:png,jpg,jpeg,pdf|max:2048|required'
        ],[
            'resume.required' => 'Please upload resume.'
        ]);

        $newinput = [];
        $image_name = '';
        if ($request->hasFile('resume')) {
            $image = $request->file('resume');
            $image_name = time() . '.' . $request->resume->extension();
            $path = public_path('upload/resumes');
            $dest = $image->move($path, $image_name);
            $newinput['resume'] = $image_name;
        }

        $newinput['rec_date'] = date('Y-m-d H:i:s');
        $newinput['firstname'] = $input['firstname'];
        $newinput['lastname'] = $input['lastname'];
        $newinput['email'] = $input['email'];
        $newinput['mobile'] = $input['mobile'];
        $newinput['applyfor'] = $input['applyfor'];
        $newinput['qualifications'] = $input['qualifications'];
        $newinput['experience'] = $input['experience'];
        $newinput['keyskills'] = $input['keyskills'];
        $newinput['city'] = $input['city'];
        $newinput['server_ip'] = request()->ip();

        $result = CareerEnquiry::create($newinput);
        $message = 'Thank you for showing interest with us. Our HR team will get back to you soon. Have a nice day.';
        if($result){
           try {
                $maildata = array(
                    'fullname' => "Wisemudra HR",
                    'email' => "hr@wisemudra.com"
                );
                $maildata2 = array(
                    'fullname' => $input['firstname'].' '.$input['lastname'],
                    'email' => $input['email']
                );
                $subject = 'Career Form Submission';
                $subject2 = 'Welcome to Wisemudra';
                $message1 = view('mail.applyCareerHR',[
                    'name' => $input['firstname'].' '.$input['lastname'],
                    'email' => $input['email'],
                    'mobile' => $input['mobile'],
                ])->render();
                $message2 = view('mail.applyCareer',[
                    'name' => $input['firstname'].' '.$input['lastname'],
                ])->render();
                $attachmentPath = public_path('upload/resumes/' . $image_name);
                $sendMail = sendBrevoHtmlMail($maildata, $subject, $message1, 3, $attachmentPath);
                $sendMail = sendBrevoHtmlMail($maildata2, $subject2, $message2, 3);
            } catch (\Exception $e) {
                return response()->json(array('type' => 'ERROR', 'message' => $e->getMessage(), 'data' => []));
            }
            return response()->json(array('type' => 'SUCCESS', 'message' => $message, 'data' => $result));
        } else {
            return response()->json(array('type' => 'ERROR', 'message' => 'Oops! Something went wrong.', 'data' => []));
        }
    }

    public function company(){
        $meta = companyMeta();
        return view('front.company',compact('meta'));
    }

    public function emiCalculator(){
        $meta = emiCalcMeta();
        return view('front.emicalculator',compact('meta'));
    }
    
    public function showPdf($userid)
    {
        $userId = customDecrypt($userid);
        $userData = DB::table('user_registrations')->select('id','first_name','last_name','email','mobile','pancard','city','state','pincode','dob','acc_type')->where('id',$userId)->first();
        $loanData = DB::table('loan_applications')->select('id','monthly_income','currentemi','loan_amount')->where('userid',$userId)->first();
        $offers = optional(DB::table('user_offers')->where('userid', $userId)->first())->offerdata;
        $offers = json_decode($offers);
        return View('pdf.offerRemarketing',compact('userData','loanData','offers'));
    }
    
    public function generateSitemap(){
        $url = config('app.url');
        $path = public_path('sitemap.xml'); // we'll use this as a raw file

        SitemapGenerator::create($url)->writeToFile($path);

        return response()->json(['message' => 'Sitemap generated']);
    }
    
    public function viewSitemap()
    {
        $rawPath = public_path('sitemap-base.xml');
        $styledOutput = public_path('sitemap.xml');

        if (!File::exists($rawPath)) {
            return abort(404);
        }

        $rawXml = File::get($rawPath);

        // Inject XSL reference after XML declaration
        $styledXml = preg_replace(
            '/(<\?xml[^>]+\?>)/',
            '$1' . PHP_EOL . '<?xml-stylesheet type="text/xsl" href="' . asset('sitemap-style.xsl') . '"?>',
            $rawXml,
            1
        );

        File::put($styledOutput, $styledXml);

        return response($styledXml, 200)->header('Content-Type', 'application/xml');
    }
        
    public function sitemap(){
        $meta = sitemapMeta();
        return View('front.sitemap',compact('meta'));
    }

    public function testdata(){
       $mailData = array(
            'fullname' => 'Parth S',
            'mobile' => '9408881214',
            'email' => 'verloop.dev4@gmail.com',
            'password' => '121212',
            'order_number' => '123',
            'order_date' => now()->format('d-m-Y'),
            'order_amount' => '199.00',
            'transactionId' => 'dsaffafjahsfhjsaf'
        );

        $sendGreetings = view('mail.welcomeGreetings', $mailData)->render();
        
        $invData3 = array(
            'rec_date' => date('Y-m-d H:i:s'),
            'userid' => 12,
            'cardid' => '1231451562',
            // 'inv_for' => $invfor,
            'inv_prefix' => 'SA_',
            'inv_number' => '123',
            'inv_date' => date('Y-m-d'),
            'inv_price' => '199.00',
            'inv_cgst' => '0.00',
            'inv_sgst' => '0.00',
            'inv_igst' => '0.00',
            'inv_grandtotal' => '199.00',
            'isdelete' => 0
        );

        $invAttach = array_merge($invData3,
            [
                'fullname' => 'Parth S',
                'city' => 'Surat',
                'mobile' => '9408881214',
                'email' => 'verloop.dev4@gmail.com',
                'acc_type' => '1',
                'state' => 'Gujarat',
                'isCustomer' => 0
            ],
            [
                'card_number' => '123456789123456',
                'registration_date' => '2026-01-21',
                'expiry_date' => '2026-02-21',
                'paymentid' => 'dsaffafjahsfhjsaf'
            ]
        );
        /* invoice data */
        $invoiceData = view('mail.invoice', $invAttach)->render();

        $pdf = Pdf::loadHTML($invoiceData)->setPaper('A4', 'portrait')->output();
        $base64Pdf = base64_encode($pdf);
        
        /* creating attachments array */
        $attachments = [
            [
                'content' => $base64Pdf,
                'name' => 'Invoice.pdf'
            ]
        ];
    
        /* send email in brevo */
        $res = sendBrevoHtmlMail2($mailData, 'Congratulations! Payment Successful for Wisemudra’s Self-Apply Plan.', $sendGreetings, 3, $attachments);

        dd($res);
    }
}
