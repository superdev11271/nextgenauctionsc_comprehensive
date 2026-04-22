<?php

namespace App\Models;

use App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

use function PHPUnit\Framework\isEmpty;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = ['choice_attributes'];

    protected $with = ['product_translations', 'taxes', 'thumbnail'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return ($product_translations != null && $product_translations->$field) ? $product_translations->$field : $this->$field;
    }
    public function getStartDateAttribute()
    {
        return Carbon::createFromTimestamp($this->auction_start_date);
    }

    public function getEndDateAttribute()
    {
        return Carbon::createFromTimestamp($this->auction_end_date);
    }

    public function getPickupTimeAttribute($val)
    {
        if (empty($val) || !strpos($val, ' - ')) {
            return null;
        }

        list($startTime, $endTime) = explode(' - ', $val);

        try {
            $start = Carbon::createFromFormat('h:i A', trim($startTime));
            $end = Carbon::createFromFormat('h:i A', trim($endTime));

            if (!$start || !$end) {
                throw new \Exception("Invalid time format for pickup times.");
            }

            $startFormatted = $start->format('g:i A');
            $endFormatted = $end->format('g:i A');

            return "$startFormatted to $endFormatted";
        } catch (\Exception $e) {
            return null;
        }
    }

    public function product_translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }


    public function scopefilterMaxPrice($query, $max_price, $column)
    {
        if ($max_price == null) return $query;

        if ($column == "unit_price")
            return $query->whereRelation('product_price', 'calculated_price', '<=', $max_price);

        return $query->where($column, "<=", $max_price);
    }

    public function scopefilterMinPrice($query, $min_price, $column)
    {
        if ($min_price == null) return $query;

        if ($column == "unit_price")
            return $query->whereRelation('product_price', 'calculated_price', '>=', $min_price);

        return $query->where($column, ">=", $min_price);
    }

    public function scopefilterBrand($query, $brand_ids)
    {
        if ($brand_ids == null || arrayContainsOnlyNull($brand_ids)) return $query;

        return $query->whereIn("brand_id", $brand_ids);
    }

    public function scopefilterCategories($query, $category_ids)
    {
        if ($category_ids != null)
            return $query->whereIn("category_id", $category_ids);
        return $query;
    }

    public function scopefilterKeywords($query, $keywords)
    {
        // if ($keywords != null) {
        //     $query->where(function ($q) use ($keywords) {
        //         foreach (explode(' ', trim($keywords)) as $word) {
        //             $q->where('name', 'like', '' . $word . '%')
        //                 ->orWhere('tags', 'like', '%' . $word . '%')
        //                 ->orWhereHas('product_translations', function ($q) use ($word) {
        //                     $q->where('name', 'like', '%' . $word . '%');
        //                 })
        //                 ->orWhereHas('stocks', function ($q) use ($word) {
        //                     $q->where('sku', 'like', '%' . $word . '%');
        //                 })
        //                 ->orwhere('auction_label','like', '%' . $word . '%')
        //                 ->orWhere('auction_number', 'like', '%' . $word . '%');
        //         }

        //     });
        // }
        if ($keywords != null) {
            $keywordsArray = explode(' ', trim($keywords));
            $query->where(function ($q) use ($keywordsArray) {
                foreach ($keywordsArray as $word) {
                    $q->where(function ($q) use ($word) {
                        $q->where('name', 'like', '%' . $word . '%')
                            ->orWhere('tags', 'like', '%' . $word . '%')
                            ->orWhereHas('product_translations', function ($q) use ($word) {
                                $q->where('name', 'like', '%' . $word . '%');
                            })
                            ->orWhereHas('stocks', function ($q) use ($word) {
                                $q->where('sku', 'like', '%' . $word . '%');
                            })
                            ->orWhere('auction_label', 'like', '%' . $word . '%')
                            ->orWhere('auction_number', 'like', '%' . $word . '%');
                    });
                }
            });
        }
        return $query;
    }


    public function scopefilterOnsale($query, $on_sale)
    {
        if ($on_sale != null)
            return $query->whereHas('flash_deal_product');
        return $query;
    }

    public function scopefilterColor($query, $color)
    {
        if ($color != null)
            return $query->where('colors', 'like', '%' . $color . '%');
        return $query;
    }

    public function scopefilterFeatured($query, $featured)
    {
        if ($featured != null)
            return $query->where('featured', '1')->latest();
        return $query;
    }

    public function scopefilterTodayDeal($query)
    {
        return $query->where('todays_deal', '1')->latest();
    }

    public function scopefilterBestSelling($query)
    {
        return $query->orderBy('num_of_sale', 'desc');
    }

    public function main_category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function frequently_brought_products()
    {
        return $this->hasMany(FrequentlyBroughtProduct::class);
    }

    public function product_categories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function product_queries()
    {
        return $this->hasMany(ProductQuery::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function taxes()
    {
        return $this->hasMany(ProductTax::class);
    }

    public function flash_deal_product()
    {
        return $this->hasOne(FlashDealProduct::class);
    }

    public function bids()
    {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function highestBid()
    {
        return $this->bids()->where("notified", 0)->orderBy('amount', 'desc')->first();
    }
    public function getHighestBid()
    {
        return $this->bids()->where("status", "open")->orderBy('amount', 'desc')->first();
    }

    public function thumbnail()
    {
        return $this->belongsTo(Upload::class, 'thumbnail_img');
    }

    public function scopePhysical($query)
    {
        return $query->where('digital', 0);
    }

    public function scopeDigital($query)
    {
        return $query->where('digital', 1);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }


    public function scopeIsApprovedPublished($query)
    {
        return $query->where('approved', '1')->where('published', 1);
    }

    public function last_viewed_products()
    {
        return $this->hasMany(LastViewedProduct::class);
    }
    public function scopesortBy($query, $by, $column)
    {
        switch ($by) {
            case 'newest':
                return $query->orderBy('created_at', 'desc');

            case 'oldest':
                return $query->orderBy('created_at', 'asc');

            case 'price-asc':
                return $query->orderBy($column, 'asc');

            case 'price
            -desc':
                return $query->orderBy($column, 'desc');

            case 'best_selling':
                return $query->orderBy('num_of_sale', 'desc');
        }
        return $query;
    }
    public function scopeonlyLiveAuctions($query)
    {
        return $query->where('auction_start_date', '<=', strtotime("now"))
            ->where('auction_end_date', '>=', strtotime("now"));
    }

    public function scopeonlyUpcomingAuctions($query)
    {
        $currentTime = strtotime("now");
        return $query->where('auction_start_date', '>', $currentTime);
    }
    public function scopeLiveAndUpcomingAuctions($query)
    {
        $currentTime = strtotime("now");
        return $query->where(function ($query) use ($currentTime) {
            $query->where('auction_start_date', '<=', $currentTime)
                ->where('auction_end_date', '>=', $currentTime);
        })->orWhere(function ($query) use ($currentTime) {
            $query->where('auction_start_date', '>', $currentTime);
        });
    }


    public function scopeNotExpiryMarkplaceProduct($query)
    {
        $currentTime = \Carbon\Carbon::now();
        return $query->where('auction_product', 0)
                 ->where(function ($query) use ($currentTime) {
                     $query->where('auction_end_date', '>=', strtotime($currentTime))
                           ->orWhereNull('auction_end_date');
                 });
    }


    public function scopeonlyAuctionProducts($query)
    {
        return $query->where('auction_product', 1);
    }
    public function scopeonlyReservedProducts($query)
    {
        return $query->whereNotNull('reserved_price');
    }
    public function scopefilterAucitonAttributes($query, $attributes, $isproductTypeAuction)
    {
        if (!$isproductTypeAuction) {
            return $query;
        }

        $attributes = array_filter($attributes ?? []);
        foreach ($attributes as $value) {
            $query->whereRelation("attrs", "value", "like", "%$value%");
        }
        return $query;
    }
    public function attrs()
    {
        return $this->hasMany(AttributeProduct::class, "product_id", "id");
    }
    public function isLive()
    {
        return ($this->auction_start_date <= strtotime("now")) && ($this->auction_end_date >= strtotime("now"));
    }
    public function isAuctionStarted()
    {
        return ($this->auction_start_date <= strtotime("now"));
    }

    public function  scopeOnlyNotStartedAuctions($query)
    {
        return $query->where("auction_start_date", ">=", strtotime("now"));
    }
    public function isReclaimed()
    {
        return $this->sold_status == "reclaimed";
    }


    public function isAuctionOver()
    {
        return $this->auction_end_date <= strtotime("now");
    }
    public function passedReservedPrice()
    {
        if ($this->reserved_price == null) {
            return true;
        }
        return $this->bids->max('amount') >= $this->reserved_price;
    }
    public function isSold()
    {
        return $this->sold_status == "sold";
    }


    public function isAuctionUpcomming()
    {
        return $this->auction_start_date >= strtotime("now");
    }

    public function scopeonlyAuctionNotOver($query)
    {
        return $query->where('auction_end_date', '>=', strtotime("now"));
    }

    public function lots()
    {
        $lots = $this->hasMany(Product::class, "auction_number", "auction_number")->orderBy('auction_end_date', 'desc');
        return $lots->LiveAndUpcomingAuctions();
    }
    public function getFormattedAuctionNumber()
    {
        $parts = explode('-', $this->auction_number);
        return isset($parts[1]) ? $parts[1] : $this->auction_number;
    }

    public function setAuctionNumberAttribute($value)
    {
        $parts = explode('-', $value);
        if (isset($parts[1])) {
            $this->attributes['auction_number'] = $value;
            return;
        }
        $this->attributes['auction_number'] = auth()->id() . "-" . $value;
    }

    public function productUnit()
    {
        return $this->hasOne(ProductUnit::class, "id", "unit");
    }
    public function product_price()
    {
        return $this->hasOne(ProductCalculatedPrice::class, "id", "id");
    }
    public function getCollectionLabel()
    {
        return !empty($this->auction_label) ? $this->auction_label : $this->getFormattedAuctionNumber();
    }
    public function hasTaxes()
    {
        return $this->taxes->where('tax', '!=', 0)->count() > 0;
    }
    public function nextProductLot()
    {
        // return $this->lots->where('id', '>', $this->id)->first();
                return  $this->hasMany(Product::class, "auction_number", "auction_number")->orderBy('auction_end_date', 'asc')->where('id', '>', $this->id)->first();

    }
    public function previousProductLot()
    {
        return $this->lots->where('id', '<', $this->id)->first();
    }   
}
