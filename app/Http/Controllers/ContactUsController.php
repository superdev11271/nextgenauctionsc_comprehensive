<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;


class ContactUsController extends Controller
{
    //
    public function __construct(){
        $this->middleware(['permission:contact_enquires'])->only('messages');
    }

    public function index(){
        return abort(404);
    }
    public function messages(){
        $messages = ContactUs::orderBy('created_at', 'desc')->paginate(15);
        return view('backend.contact_us.index', compact('messages'));
    }
}
