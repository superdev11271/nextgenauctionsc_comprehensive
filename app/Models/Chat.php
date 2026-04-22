<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        "product_id",
        "bid_id",
        "sender",
        "receiver",
        "amount",
        "chat_tamplate_id",
        "viewed"
    ];
    public function tamplate(){
        return $this->belongsTo(ChatTamplate::class,"chat_tamplate_id","id");
    }
}
