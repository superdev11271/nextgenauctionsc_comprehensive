<?php

namespace App\Http\Controllers;

use App\Models\ChatTamplate;
use Illuminate\Http\Request;
use Twilio\Rest\Chat;

class ChatTamplateController extends Controller
{
    public function index()
    {
        $tamplates = ChatTamplate::paginate();
        return view("backend.system.chat_tamplate",compact("tamplates"));
    }
    public function store(Request $request)
    {
        $validData = $request->validate([
            "message" => "required",
            "used_by" => "required",
            "with_amount" => "sometimes|required"
        ]);
        $tamplate = (new ChatTamplate)->fill($validData)->save();
        flash(translate('Tamplate created successfully.'))->success();
        return redirect()->back();
    }
    public function update(Request $request, ChatTamplate $chatTamplate)
    {
        $validData = $request->validate([
            "message" => "required",
            "used_by" => "required",
            "with_amount" => "sometimes|required"
        ]);
        $validData["with_amount"] = $request->has("with_amount")?1:0;
        $chatTamplate->fill($validData)->save();
        flash(translate('Tamplate updated successfully.'))->success();
        return redirect()->back();

    }
    public function destroy(ChatTamplate $chatTamplate)
    {
        if($chatTamplate->usedInChat->count()){
            flash(translate('Tamplate has been used. You may Chage it but can not delete it.'))->success();
            return redirect()->back();
        }
        $chatTamplate->delete();
        flash(translate('Tamplate deleted successfully.'))->success();
        return redirect()->back();
    }
}
