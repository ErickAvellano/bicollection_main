<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerSupportController extends Controller
{
    public function landing()
    {
        return view('customersupport.landing');
    }
}
