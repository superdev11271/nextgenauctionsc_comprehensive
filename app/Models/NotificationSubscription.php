<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSubscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'endpoint',
        'p256dh',
        'auth',
    ];
    protected $table = 'subscriptions';
    public function user(){
        return $this->belongsTo(User::class);
    }
}
