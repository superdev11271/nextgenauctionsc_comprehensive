<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use Stripe;

class CardController extends Controller
{
    public function bankInfo(Request $request)
    {
        return view('frontend.xt-user.xt-bank-info');
    }
}






