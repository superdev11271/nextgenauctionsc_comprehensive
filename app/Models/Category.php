<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Category extends Model
{
    protected $with = ['category_translations'];

    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $category_translation = $this->category_translations->where('lang', $lang)->first();
        return $category_translation != null ? $category_translation->$field : $this->$field;
    }


    public function category_translations(){
    	return $this->hasMany(CategoryTranslation::class);
    }

    public function coverImage(){
    	return $this->belongsTo(Upload::class, 'cover_image');
    }

    public function getIdsWithChildrens(){
        $childrens = $this->childrenCategories()->pluck("id")->toArray();
        $childrens[] = $this->id;
    	return $childrens;
    }

    public function getAllChildrenIdsWithSearch()
    {
        $childIds = $this->childrenCategories()->pluck('id')->toArray();
        foreach ($this->childrenCategories as $child) {
            $childIds = array_merge($childIds, $child->getAllChildrenIdsWithSearch());
        }
        return $childIds;
    }



    public function catIcon(){
    	return $this->belongsTo(Upload::class, 'icon');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    public function bannerImage(){
    	return $this->belongsTo(Upload::class, 'banner');
    }

    public function classified_products(){
    	return $this->hasMany(CustomerProduct::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('categories');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function sizeChart()
    {
        return $this->belongsTo(SizeChart::class, 'id', 'category_id');
    }
    public static function getParentCategories(){
        return \Cache::remember('parentCategories', 86400, function () {
            return Category::where("level",0)->with(["childrenCategories","auctionAttributes"])->get();
        });
    }
    public function auctionAttributes()
    {
        return $this->hasMany(AuctionAttribute::class,"category_id","id")
        ->whereIn("field_type",[3,4,5])
        // ->select(["fields_name","dd_value"])
        ;
    }

}
