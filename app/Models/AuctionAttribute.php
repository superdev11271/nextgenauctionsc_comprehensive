<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionAttribute extends Model
{
    use HasFactory;
    protected $fillable = [
        "category_id",
        "fields_name",
        "added_by",
        "field_type",
        "field_optional",
        "dd_value"
    ];

    protected $field_type_int_to_str = ["0" => "Upload","1" => "Short text", "2" => "Detailed text", "3" => "Drop down", "4" => "Single Selection", "5" => "Multiple Selection"];
    public function field_type_str()
    {
        return $this->field_type_int_to_str[$this->field_type];
    }

    public function siblings()
    {
        return $this->hasMany(AuctionAttribute::class, 'category_id', 'category_id')->where('id', '!=', $this->id);
    }
    public function field(){
        return $this->hasMany(AttributeProduct::class,"attribute_id");
    }

    public function value($product_id=null){
        return $this->field()->firstWhere("product_id",$product_id)?->value;
    }
}
