<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatTamplate extends Model
{
    use HasFactory;
    protected $fillable = ["message","used_by","with_amount"];
    public function usedInChat(){
        return $this->hasMany(Chat::class,"chat_tamplate_id");
    }
}
