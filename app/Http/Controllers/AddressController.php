<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\City;
use App\Models\State;
use Auth;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_my_addresse()
    {
        $adderess = Auth::user()->addresses()->get()->groupBy("address_type");
        return view('frontend.xt-user.xt_view_address', compact('adderess'));
    }
    public function addressMakeDefault(Request $request)
    {
        try {
            Auth::user()->addresses()->where('address_type', $request->addresstype)->update(['set_default' => '0']);
            Address::find($request->address_id)->update(['set_default' => '1']);
            return response()->json(array('status' => "success", 'msg' => "Address set to default successfully."));
        } catch (\Exception $e) {
            return response()->json(array('status' => "danger", 'msg' => "Someting went wrong please try again."));
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            "address" => "required",
            "postal_code" => "required",
            // "phone" => ['required', 'phone:AU,IN'],
        ], [
            'phone.phone' => 'The phone number format is invalid. Please provide a valid phone number from India or Australia.',
            'phone.phone:IN' => 'Please provide a valid phone number from India, starting with +91 or a valid local format.',
            'phone.phone:AU' => 'Please provide a valid phone number from Australia, starting with +61 or a valid local format.',
            'phone.unique' => 'The phone number has already been registered with another user. Please provide a different phone number.',
        ]);
        $address = new Address;
        if ($request->has('customer_id')) {
            $address->user_id   = $request->customer_id;
        } else {
            $address->user_id   = Auth::user()->id;
        }

        Address::where('user_id', Auth::user()->id)->where('address_type', $request->address_modal)?->update(['set_default' => '0']);
        $address->address       = $request->address;
        $address->country_id    = $request->country_id;
        $address->state_id      = $request->state_id;
        $address->city_id       = $request->city_id;
        $address->longitude     = $request->longitude;
        $address->latitude      = $request->latitude;
        $address->postal_code   = $request->postal_code;
        $address->phone         = $request->phone;
        $address->set_default   = '1';
        $address->address_type  = $request->address_modal;
        $address->save();

        if (isset($request->address_type)) {
            $newAddress = $address->replicate();
            Address::where('user_id', Auth::user()->id)->where('address_type', $request->address_type)->update(['set_default' => '0']);
            $newAddress->address_type = $request->address_type;
            $newAddress->set_default   = '1';
            $newAddress->save();
        }

        flash(translate('Address info Stored successfully'))->success();
        return back();
    }

    public function edit($id)
    {
        $data['address_data'] = Address::findOrFail($id);
        $data['states'] = State::where('status', 1)->where('country_id', $data['address_data']->country_id)->get();
        $data['cities'] = City::where('status', 1)->where('state_id', $data['address_data']->state_id)->get();
        $returnHTML = view('frontend.' . get_setting('homepage_select') . '.partials.address_edit_modal', $data)->render();
        return response()->json(array('data' => $data, 'html' => $returnHTML));
    }


    public function update(Request $request, $id)
    {
        $rules = [
            "address" => "required",
            "postal_code" => "required",
            "phone" => ['required', 'phone:AU,IN'],
        ];

        $messages = [
            'phone.phone' => 'The phone number format is invalid. Please provide a valid phone number from India or Australia.',
            'phone.phone:IN' => 'Please provide a valid phone number from India, starting with +91 or a valid local format.',
            'phone.phone:AU' => 'Please provide a valid phone number from Australia, starting with +61 or a valid local format.',
            'phone.unique' => 'The phone number has already been registered with another user. Please provide a different phone number.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $data['address_data'] = Address::findOrFail($id);
            $data['states'] = State::where('status', 1)->where('country_id', $data['address_data']->country_id)->get();
            $data['cities'] = City::where('status', 1)->where('state_id', $data['address_data']->state_id)->get();
            return redirect()->back()->withErrors($validator)->withInput()->with(['redirection_from'=> "edit","data"=>$data]);
        }

        $address = Address::findOrFail($id);

        $address->address       = $request->address;
        $address->country_id    = $request->country_id;
        $address->state_id      = $request->state_id;
        $address->city_id       = $request->city_id;
        $address->longitude     = $request->longitude;
        $address->latitude      = $request->latitude;
        $address->postal_code   = $request->postal_code;
        $address->phone         = $request->phone;

        $address->save();

        flash(translate('Address info updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        if (!$address->set_default) {
            $address->delete();
            flash(translate('Address deleted successfully.'))->success();
            return back();
        }
        flash(translate('Default address cannot be deleted'))->warning();
        return back();
    }

    public function getStates(Request $request)
    {
        $states = State::where('status', 1)->where('country_id', $request->country_id)->get();
        $html = '<option value="">' . translate("Select State") . '</option>';

        foreach ($states as $state) {
            $html .= '<option value="' . $state->id . '">' . $state->name . '</option>';
        }

        echo json_encode($html);
    }

    public function getCities(Request $request)
    {
        $cities = City::where('status', 1)->where('state_id', $request->state_id)->get();
        $html = '<option value="">' . translate("Select City") . '</option>';

        foreach ($cities as $row) {
            $html .= '<option value="' . $row->id . '">' . $row->getTranslation('name') . '</option>';
        }

        echo json_encode($html);
    }

    public function set_default($id)
    {
        foreach (Auth::user()->addresses as $key => $address) {
            $address->set_default = 0;
            $address->save();
        }
        $address = Address::findOrFail($id);
        $address->set_default = 1;
        $address->save();

        return back();
    }
}
