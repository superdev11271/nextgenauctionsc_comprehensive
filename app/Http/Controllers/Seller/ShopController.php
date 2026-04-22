<?php

namespace App\Http\Controllers\Seller;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\ShopVerificationNotification;
use Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        return view('seller.shop', compact('shop'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $rules = [];
        if ($request->has('name') || $request->has('email') || $request->has('phone')) {
            $rules['name'] = ['required', 'max:191'];
            $rules['address'] = ['required', 'max:191'];
            $rules['phone'] = ['required', 'phone:AU,IN', Rule::unique('shops', 'phone')->ignore($request->shop_id)];
        }

        $messages = [
            'name.required'         => translate('Name is required'),
            'phone.phone' => 'The phone number format is invalid. Please provide a valid phone number from India or Australia.',
            'phone.phone:IN' => 'Please provide a valid phone number from India, starting with +91 or a valid local format.',
            'phone.phone:AU' => 'Please provide a valid phone number from Australia, starting with +61 or a valid local format.',
            'phone.unique' => 'The phone number has already been registered with another shop. Please provide a different phone number.',
        ];

        $this->validate($request, $rules, $messages);

        $shop = Shop::find($request->shop_id);
        // Remove aggrement_form file upload block entirely

        $shop->business_name = $request->business_name;
        $shop->vendor_type = $request->vendor_type;
        $shop->abn = $request->abn;
        $shop->acn = $request->acn;
        $shop->gst_registered = $request->gst_registered;

        $shop->director1_name = $request->director1_name;
        $shop->director1_phone = $request->director1_phone;
        $shop->director1_email = $request->director1_email;
        $shop->director2_name = $request->director2_name;
        $shop->director2_phone = $request->director2_phone;
        $shop->director2_email = $request->director2_email;

        $shop->business_address = $request->business_address;
        $shop->postal_address = $request->postal_address;
        $shop->business_phone = $request->business_phone;

        $shop->contact1_mobile = $request->contact1_mobile;
        $shop->contact2_mobile = $request->contact2_mobile;
        $shop->contact3_mobile = $request->contact3_mobile;

        $shop->commission = $request->commission;
        $shop->vendor_costs = $request->vendor_costs;
        $shop->basis = $request->basis ? json_encode($request->basis) : null;

        $shop->shipping_cost = $request->shipping_cost;
        $shop->photo_cost = $request->photo_cost;
        $shop->catalogue_cost = $request->catalogue_cost;
        $shop->staff_cost = $request->staff_cost;
        $shop->travel_cost = $request->travel_cost;
        $shop->air_travel_cost = $request->air_travel_cost;
        $shop->other_costs = $request->other_costs;

        $shop->ack_name = $request->ack_name;
        $shop->ack_company = $request->ack_company;
        $shop->signed_date = $request->signed_date;

        if ($request->filled('signature')) {
            $signatureData = $request->signature;

            // Remove the "data:image/png;base64," part
            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData); // replace spaces with plus in case browser changes them

            // Decode and store the image
            $signatureImage = base64_decode($signatureData);

            // Generate unique filename
            $signatureFilename = 'signatures/' . Str::random(40) . '.png';

            // Store the image in 'public/signatures' directory
            Storage::disk('public')->put($signatureFilename, $signatureImage);

            // Save the path in DB (adjust field accordingly)
            $shop->signature = 'storage/' . $signatureFilename;
        }

        if ($request->has('name') && $request->has('address')) {
            if ($request->has('shipping_cost')) {
                $shop->shipping_cost = $request->shipping_cost;
            }
            $shop->name             = $request->name;
            $shop->address          = $request->address;
            $shop->phone            = $request->phone;
            $shop->slug             = preg_replace('/\s+/', '-', $request->name) . '-' . $shop->id;
            $shop->meta_title       = $request->meta_title;
            $shop->meta_description = $request->meta_description;
            $shop->logo             = $request->logo;
            $shop->gst_number       = $request->gst_number;
        }

        if ($request->has('delivery_pickup_longitude') && $request->has('delivery_pickup_latitude')) {

            $shop->delivery_pickup_longitude    = $request->delivery_pickup_longitude;
            $shop->delivery_pickup_latitude     = $request->delivery_pickup_latitude;
        } elseif (
            $request->has('facebook') ||
            $request->has('google') ||
            $request->has('twitter') ||
            $request->has('youtube') ||
            $request->has('instagram')
        ) {
            $shop->facebook = $request->facebook;
            $shop->instagram = $request->instagram;
            $shop->google = $request->google;
            $shop->twitter = $request->twitter;
            $shop->youtube = $request->youtube;
        } elseif (
            $request->has('top_banner') ||
            $request->has('sliders') ||
            $request->has('banner_full_width_1') ||
            $request->has('banners_half_width') ||
            $request->has('banner_full_width_2')
        ) {
            $shop->top_banner = $request->top_banner;
            $shop->sliders = $request->sliders;
            $shop->banner_full_width_1 = $request->banner_full_width_1;
            $shop->banners_half_width = $request->banners_half_width;
            $shop->banner_full_width_2 = $request->banner_full_width_2;
        }

        if ($shop->save()) {
            flash(translate('Your Shop has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function verify_form()
    {
        if (Auth::user()->shop?->verification_info == null) {
            $shop = Auth::user()->shop;
            // Decode basis if it exists and is a string
            $shop->basis = $shop->basis ? json_decode($shop->basis, true) : [];

            return view('seller.verify_form', compact('shop'));
        } else {
            flash(translate('Sorry! You have sent verification request already.'))->error();
            return back();
        }
    }

    public function verify_form_store(Request $request)
    {
        $rowData = $request->all();
        $validationArray = [];
        $validationMessage = [];
        foreach ($rowData as $key => $data) {
            if (!in_array($key, ['_token', 'home']) && gettype($data) != 'object') {
                $validationArray[$key] = 'required';
                $validationMessage[$key] = 'Form Field No ' . explode('_', $key)[1] + 1 . ' is required';
            }
        }

        $this->validate($request, $validationArray, $validationMessage);

        $data = array();
        $i = 0;
        foreach (json_decode(BusinessSetting::where('type', 'verification_form')->first()->value) as $key => $element) {
            $item = array();
            if ($element->type == 'text') {
                $item['type'] = 'text';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'select' || $element->type == 'radio') {
                $item['type'] = 'select';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'multi_select') {
                $item['type'] = 'multi_select';
                $item['label'] = $element->label;
                $item['value'] = json_encode($request['element_' . $i]);
            } elseif ($element->type == 'file') {
                $item['type'] = 'file';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i]->store('uploads/verification_form');
            }
            array_push($data, $item);
            $i++;
        }
        $shop = Auth::user()->shop;
        $shop->verification_info = json_encode($data);
        $shop->rejected = null;
        $shop->remark = null;
        if ($shop->save()) {
            $users = User::findMany([auth()->user()->id, User::where('user_type', 'admin')->first()->id]);
            Notification::send($users, new ShopVerificationNotification($shop));

            flash(translate('Your shop verification request has been submitted successfully!'))->success();
            if ($request->home == 'home') {
                return redirect()->route('shops.create');
            }
            return redirect()->route('seller.dashboard');
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function show() {}
}
