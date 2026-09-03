<?php
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/* declare global variable of application id and name */
// $applicationId = 'f9f3cad1d14488a15ae8f075f5ed5a0d';
// $applicationName = 'WIMDRA';


/* function to check the user`s duplicate application */
if(!function_exists('checkDuplicateApplication')){
    function checkDuplicateApplication($userData){

        $applicationId = 'f9f3cad1d14488a15ae8f075f5ed5a0d';
        $applicationName = 'WIMDRA';

        /* send the headers and body to check duplicate entry in faircent */
        $response = Http::withHeaders([
            'x-application-id'      => $applicationId,
            'x-application-name'    => $applicationName,
            'Content-Type'          => 'application/json',
        ])->post('https://fcnode5.faircent.com/v1/api/duplicateCheck', [
            'pan'       => $userData['pancard'],
            'mobile'    => $userData['mobile'],
            'email'     => $userData['email'],
        ]);

        /* getting response */
        return [
            'responseArr' => $response->body(),
            'responseStatus' => $response->status()
        ];
    }
}

/* apply as new user registration and create new application */
if(!function_exists('createNewApplication')){
    function createNewApplication($userData){

        $applicationId = 'f9f3cad1d14488a15ae8f075f5ed5a0d';
        $applicationName = 'WIMDRA';
        
        /*$data = [
            'fname'     => $userData['first_name'],
            'lname'     => $userData['last_name'],
            'dob'       => $userData['dob'],
            'address'   => $userData['city'],
            'city'      => $userData['city'],
            'state'     => $userData['state'],
            'pin'       => $userData['pincode'],
            'mobile'    => $userData['mobile'],
            'pan'       => $userData['pancard'],
            'email'      => $userData['email'],
            'gender'    => 'M', // pending to add @our side
            'employment_status' => $userData['user_type'] == 2 ? 58 : 57,
            'loan_purpose'      => 1364, // getting from dropdown list faircent
            'monthly_income'    => explode('-',$userData['monthly_income'])[0],// in this remove the slab and put it simple input
            'loan_amount'       => $userData['loan_amount'],
            'lat'       => '',
            'long'      => '',
            'consent'   => 'Y',
            'tnc_link'  => 'https://www.faircent.in/terms-conditions',
            'sign_ip'   => $userData['sign_ip'], // field is required
            'sign_time' => Carbon::now()->timestamp
        ];
        dd($data);*/
        /* send data to faircent using http to create/register new application/user */
        $response = Http::withHeaders([
            'x-application-id'      => $applicationId,
            'x-application-name'    => $applicationName,
            'Content-Type'          => 'application/json',
        ])->post('https://fcnode5.faircent.com/v1/api/aggregrator/register/user', [
            'fname'     => $userData['first_name'],
            'lname'     => $userData['last_name'],
            'dob'       => $userData['dob'],
            'address'   => $userData['city'],
            'city'      => $userData['city'],
            'state'     => $userData['state'],
            'pin'       => $userData['pincode'],
            'mobile'    => $userData['mobile'],
            'pan'       => $userData['pancard'],
            'email'      => $userData['email'],
            'gender'    => 'M', // pending to add @our side
            'employment_status' => $userData['user_type'] == 2 ? 58 : 57, //$userData['user_type'] == 2 ? "Salaried" : "Self Employed",
            'loan_purpose'      => 1364, // getting from dropdown list faircent
            'monthly_income'    => explode('-',$userData['monthly_income'])[0],// in this remove the slab and put it simple input
            'loan_amount'       => $userData['loan_amount'],
            'lat'       => '',
            'long'      => '',
            'consent'   => 'Y',
            'tnc_link'  => 'https://www.faircent.in/terms-conditions',
            'sign_ip'   => $userData['sign_ip'], // field is required
            'sign_time' => Carbon::now()->timestamp
        ]);

        /* getting response */
        return [
            'responseArr' => $response->body(),
            'responseStatus' => $response->status()
        ];
    }
}

/* get the dropdown list of loanpurpose and manymore*/
if(!function_exists('getDropdownList')){
    function getDropdownList(){

        $applicationId = 'f9f3cad1d14488a15ae8f075f5ed5a0d';
        $applicationName = 'WIMDRA';
        
        /* getting rthe dropdown list of many fields */
        $response = Http::withHeaders([
            'x-application-id'      => $applicationId,
            'x-application-name'    => $applicationName,
            'Content-Type'          => 'application/json',
        ])->get('https://fcnode5.faircent.com/v1/api/registration/dropdown', []);

        /* getting response */
        return [
            'responseArr' => $response->body(),
            'responseStatus' => $response->status()
        ];
    }
}
