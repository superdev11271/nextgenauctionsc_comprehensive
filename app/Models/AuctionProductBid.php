<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionProductBid extends Model
{
    public function product(){
    	return $this->belongsTo(Product::class,"product_id","id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function xero()
    {
        return $this->hasOne(XeroMeta::class, "bid_id");
    }
    public function getUnviewdMsgCount($user_id){
        return $this->chats()->where(["sender"=>$user_id,"viewed"=>0])->count();
    }
    public function chats()
    {
        return $this->hasMany(Chat::class,"bid_id");
    }
}
