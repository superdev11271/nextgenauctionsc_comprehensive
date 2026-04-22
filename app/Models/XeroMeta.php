<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XeroMeta extends Model
{
    use HasFactory;
    protected $fillable=["bid_id","invoice_number","invoice_id","mail_sent","total_amount"];
}
