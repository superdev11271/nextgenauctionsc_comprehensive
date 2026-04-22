<?php

namespace App\Http\Controllers;

use App\Jobs\XeroInvoiceUpdateEvent;
use App\Jobs\XeroMarkInvoiceAsPaid;
use App\Models\Product;
use App\Services\XeroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TestController extends Controller
{
    public function createInvoiceonXeroTest(){

        dd(Product::select('auction_number')->where("user_id", auth()->id())->onlyAuctionNotOver()->distinct()->get()->toArray());
        XeroMarkInvoiceAsPaid::dispatch(29);
        dd("done");
    }
    public static function runAllCronJobs(){
        Artisan::call('reserved_price_cron_job');
        dump("Cron ran successfully.");
        return;
    }
    public static function clearCache(){
        Artisan::call('cache:clear');
        Artisan::call('optimize:clear');
        dump("Clear Successfully.");
        return;
    }
    public static function testNotification(){
        //write code here
        dump("Notification sent successfully");
        return;
    }
    public static function testpage_event(){

    }
}
