<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index($city){
        $formattedCity = ucwords($city);
    
        $meta = [
            "title" => "Personal Loan in {$formattedCity} – Instant Approval | Wisemudra",
            "description" => "Apply for a personal loan in {$formattedCity} with Wisemudra. Get quick approval, minimal documents & best interest rates from top NBFCs. Check eligibility now!",
            "keywords" =>  "personal loan in {$formattedCity}, {$formattedCity} personal loan, instant personal loan {$formattedCity}, personal loan NBFC {$formattedCity}, low interest loan in {$formattedCity}, Wisemudra loan {$formattedCity}"
        ];
    
        return view('cities.index', compact('meta', 'city'));
    }
    
}
