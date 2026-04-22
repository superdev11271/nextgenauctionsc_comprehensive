<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'category_id',
        'attribute_id',
        'attribute_name',
        'value',
        'status'
    ];
    public function field(){
        return $this->belongsTo(AuctionAttribute::class,"attribute_id");
    }
    public function type(){
        return $this->field?->field_type;
    }
}
