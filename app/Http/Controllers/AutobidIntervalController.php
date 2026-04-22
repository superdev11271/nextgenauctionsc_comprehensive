<?php

namespace App\Http\Controllers;

use App\Models\AutobidInterval;
use Illuminate\Http\Request;

class AutobidIntervalController extends Controller
{
    public function index(Request $request)
    {
        $intervals = AutobidInterval::paginate();
        return view("backend.system.autobid_intervals", compact("intervals"));
    }
    public function store(Request $request)
    {
        $validData = $request->validate([
            "min_bid" => "required",
            "max_bid" => "required|gt:min_bid",
            "increment" => "required"
        ],[
            "min_bid.required"=> "Please Enter Min Bid.",
            "max_bid.required"=> "Please Enter Max Bid.",
            "max_bid.gt"=> "Max Bid must be greater than Min Bid.",
            "increment.required"=> "Please Enter Increment.",
        ]);
        $increment_validation = $this->validateIncrement($request);
        if ($increment_validation["status"] == false) {
            return redirect()->back()->withErrors($increment_validation["errors"])->withInput();
        }


        $tamplate = (new AutobidInterval)->fill($validData)->save();
        flash(translate('Autobid Interval created successfully.'))->success();
        return redirect()->back();

    }
    public function update(Request $request, AutobidInterval $autobid)
    {
        $validData = $request->validate([
            "min_bid" => "required",
            "max_bid" => "required|gt:min_bid",
            "increment" => "required"
        ],[
            "min_bid.required"=> "Please Enter Min Bid.",
            "max_bid.required"=> "Please Enter Max Bid.",
            "max_bid.gt"=> "Max Bid must be greater than Min Bid.",
            "increment.required"=> "Please Enter Increment.",
        ]);

        $increment_validation = $this->validateIncrement($request,$autobid->id);
        if ($increment_validation["status"] == false) {
            return redirect()->back()->withErrors($increment_validation["errors"])->withInput();
        }

        $autobid->fill($validData)->save();
        flash(translate('Autobid Interval updated successfully.'))->success();
        return redirect()->back();
    }
    public function destroy(Request $request, AutobidInterval $autobid)
    {
        $autobid->delete();
        flash(translate('Autobid Interval deleted successfully.'))->success();
        return redirect()->back();
    }

    public function validateIncrement(Request $request, $interval_id = null){
        $errors = [];
        // this function checks if min is greater than previously maximum.
        // ex previous interval = min:0 to max:5 increment:1 then next interval's min should be greater then min:6

        // find previous(if its update) or last interval and compare them to current
        $last_interval = $interval_id?AutobidInterval::find($interval_id-1):AutobidInterval::orderBy("id","desc")->first();
        
        if($last_interval==null) return ["status" => empty($errors), "errors" =>[]];
        if (!($last_interval->max_bid < $request->min_bid)) $errors[] = "Minimum bid shold is greater than its previous maximum bid amount";
        return ["status" => empty($errors), "errors" =>$errors];
    }
}
