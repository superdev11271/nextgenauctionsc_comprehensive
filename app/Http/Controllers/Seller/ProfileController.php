<?php

namespace App\Http\Controllers\Seller;

use App\Http\Requests\SellerProfileRequest;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses; 
        return view('seller.profile.index', compact('user','addresses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request , $id)
    {
        $rules=[];
        if($request->has('name')){
            $rules['name'] = 'required|string|max:255';
        }
        if($request->filled('phone')){
            $rules['phone'] = ['required', 'phone:AU,IN'];
        }
        if ($request->filled('new_password')) {
            $rules['new_password'] = ['nullable','string','min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/', 'same:confirm_password'];
        }

        if ($request->hasFile('photo')) {
            $rules['photo'] = 'nullable|image|max:2048';
        }

        if($request->has('bank_name')|| $request->has('bank_acc_no') || $request->has('bank_routing_no')){
            $rules['cash_on_delivery_status'] = 'nullable|boolean';
            $rules['bank_payment_status'] = 'required|boolean';
            $rules['bank_name'] = 'required|string|max:255';
            $rules['bank_acc_name'] = 'required|string|max:255';
            $rules['bank_acc_no'] = 'required|string|max:255';
            $rules['bank_routing_no'] = 'nullable|string|max:50';
        }
        $messages = [ 
            'new_password.regex' => 'The password must include at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'bank_payment_status' => 'Please ON bank payment or Cash Payment at least one',
            'phone.phone' => 'The phone number format is invalid. Please provide a valid phone number from India or Australia.',
            'phone.phone:IN' => 'Please provide a valid phone number from India, starting with +91 or a valid local format.',
            'phone.phone:AU' => 'Please provide a valid phone number from Australia, starting with +61 or a valid local format.',
            'phone.unique' => 'The phone number has already been registered with another user. Please provide a different phone number.',
        ];
        $validatedData = $request->validate($rules,$messages);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        if($request->new_password && $request->confirm_password)
        {
            if(!($request->new_password == $request->confirm_password)){
                flash(translate('Passwords do not match'))->error();
                return back();
            }
            if($request->new_password != null && ($request->new_password == $request->confirm_password)){
                $user->password = Hash::make($request->new_password);
            }
        }
        $user->avatar_original = $request->photo;

        $shop = $user->shop;

        if($shop){
            $shop->cash_on_delivery_status = $request->cash_on_delivery_status;
            $shop->bank_payment_status = $request->bank_payment_status;
            $shop->bank_name = $request->bank_name;
            $shop->bank_acc_name = $request->bank_acc_name;
            $shop->bank_acc_no = $request->bank_acc_no;
            $shop->bank_routing_no = $request->bank_routing_no;

            $shop->save();
        }

        $user->save();

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();
    }
}
