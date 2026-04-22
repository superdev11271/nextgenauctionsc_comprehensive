<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XeroWebHook extends Model
{
    use HasFactory;
    protected $fillable = ["resource_url","data","event_category","event_type","status","status_description "];
}
