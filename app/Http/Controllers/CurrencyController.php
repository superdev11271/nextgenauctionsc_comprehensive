<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:currency_setup'])->only('currency','create','edit');
    }

    public function changeCurrency(Request $request)
    {
        $currency = Currency::where('code', $request->currency_code)->first();
        $request->session()->put('currency_code', $request->currency_code);
        $request->session()->put('currency_symbol', $currency->symbol);
        $request->session()->put('currency_exchange_rate', $currency->exchange_rate);
    	flash(translate('Currency changed to ').$currency->name)->success();
    }

    public function currency(Request $request)
    {
        $sort_search =null;
        $currencies = Currency::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $currencies = $currencies->where('name', 'like', '%'.$sort_search.'%');
        }
        $currencies = $currencies->paginate(10);

        $active_currencies = Currency::where('status', 1)->get();
        return view('backend.setup_configurations.currencies.index', compact('currencies', 'active_currencies','sort_search'));
    }

    public function updateYourCurrency(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        $currency->name = $request->name;
        $currency->symbol = $request->symbol;
        $currency->code = $request->code;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->status = $currency->status;
        if($currency->save()){
            flash(translate('Currency updated successfully'))->success();
            return redirect()->route('currency.index');
        }
        else {
            flash(translate('Something went wrong'))->error();
            return redirect()->route('currency.index');
        }
    }

    public function create()
    {
        return view('backend.setup_configurations.currencies.create');
    }

    public function edit(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        return view('backend.setup_configurations.currencies.edit', compact('currency'));
    }

    public function store(Request $request)
    {
        
        $rules = [
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'code' => 'required|string|max:3',
            'exchange_rate' => 'required|numeric',
        ];
    
        $messages = [
            'name.required' => 'The name field is required.',
            'symbol.required' => 'The symbol field is required.',
            'code.required' => 'The code field is required.',
            'exchange_rate.required' => 'The exchange rate field is required.',
        ];
    
        $validatedData = $request->validate($rules, $messages);
    
        $currency = new Currency;
        $currency->name = $validatedData['name'];
        $currency->symbol = $validatedData['symbol'];
        $currency->code = $validatedData['code'];
        $currency->exchange_rate = $validatedData['exchange_rate'];
        $currency->status = '0';

        
        if($currency->save()){
            flash(translate('Currency updated successfully'))->success();
            return redirect()->route('currency.index');
        }
        else {
            flash(translate('Something went wrong'))->error();
            return redirect()->route('currency.index');
        }
    }

    public function update_status(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        if($request->status == 0){
            if (get_setting('system_default_currency') == $currency->id) {
                return 0;
            }
        }
        $currency->status = $request->status;
        $currency->save();
        return 1;
    }
}
