<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function offer1()
    {
        $meta = selfApplyMeta();
        return view('offers.offer-1', compact('meta'));
    }

    public function offer2()
    {
        $meta = selfApplyMeta();
        return view('offers.offer-2', compact('meta'));
    }
}
