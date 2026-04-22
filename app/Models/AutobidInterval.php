<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutobidInterval extends Model
{
    use HasFactory;
    protected $fillable = [
        "min_bid",
        "max_bid",
        "increment"
    ];
}
