<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use App\Models\Shop;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use App\Models\AttributeProduct;
use App\Models\Brand;

use Auth;
use Carbon\Carbon;
use Storage;

//class ProductsImport implements ToModel, WithHeadingRow, WithValidation
class AuctionProductsImport implements ToCollection, WithHeadingRow, WithValidation, ToModel
{
    private $rows = 0;

    private $firstCategoryId = null; 
    private $fieldsNames = null;
    private $rowFields = null;

    private $attributes = null;

    public function __construct($attributes){
        $this->attributes = $attributes;
    }

    public function collection(Collection $rows)
    {

        $user = Auth::user();
       
        foreach ($rows as $row) {
            $this->rowFields = array_keys($row->toArray());
            $this->fieldsNames = AuctionAttribute::where('category_id', $row['category_id'])->pluck('fields_name');
            foreach ($this->fieldsNames as $fieldName) {
                $formattedFieldName = format_to_underscore($fieldName);
                if (!in_array($formattedFieldName, $this->rowFields)) {
                    throw new \InvalidArgumentException("Attribute field name '{$fieldName}' is missing form the Excel");
                }
            }

            $approved = 1;
           
            if (($user->user_type == 'seller' || $user->shop) && $user->shop && $user->shop?->verification_status == 0) {
                $approved = 0;
            }
            $currentTime = now()->timestamp;
            $lotCount='';
            $isliveOrupcoming= false;
            if($row['auction_number']){
                if(Auth::user()->user_type == 'seller'){
                    $query = Product::where('auction_number',Auth::user()->id.'-'. $row['auction_number'])->where('user_id',Auth::user()->id)->orderBy('id', 'asc');
                }else{
                    $query = Product::where('auction_number',Auth::user()->id.'-'. $row['auction_number'])->orderBy('id', 'asc');
                }
                $lotCount = $query->first();
                if($lotCount){
                    $isOngoing = $lotCount->auction_start_date <= $currentTime && $lotCount->auction_end_date >= $currentTime;
                    $isUpcoming = $lotCount->auction_start_date > $currentTime;
                    $isliveOrupcoming = ($isOngoing || $isUpcoming);
                }
            }
            $brand_id = '';
            if (!empty($row['brand_id'])) {
                $brand = Brand::where('id', $row['brand_id'])->orWhere('name', $row['brand_id'])->first();
                if ($brand) {
                    $brand_id = $brand->id;
                }
            }

            $productId = Product::create([
                'name' => "{$row['name']}",
                'description' => $row['description'],
                'added_by' => ($user->user_type == 'seller' || $user->shop) ? 'seller' : 'admin',
                'user_id' => $user->id,
                'approved' => $approved,
                'category_id' => "{$row['category_id']}",
                'lot' => $lotCount == null ? 1 : $query->count() + 1,
                'auction_product' => '1',
                'auction_label' => $row['auction_label'],
                'auction_number' => !empty($row['auction_number']) ? ($isliveOrupcoming ? $row['auction_number'] : generateAuctionNumber()) : generateAuctionNumber(),
                'estimate_start' => $row['estimate_start'],
                'reserved_price' => $row['reserved_price'],
                'estimate_end' => $row['estimate_end'],
                'shipping_info' => $row['shipping_info'] ?? '',
                'terms_conditions' => $row['terms_conditions'],
                'brand_id' => $brand_id,
                'weight' => $row['weight'],
                'barcode' => $row['barcode'],
                'starting_bid' => $row['starting_bid'],
                'pickup_days' => $row['pickup_days'],
                'pickup_time' => $row['pickup_time'],
                'pickup_address' => $row['pickup_address'],
                'photos' => $this->downloadGalleryImages($row['photos']),
                'thumbnail_img' => $this->downloadThumbnail($row['thumbnail_img']),
                'tags' => $row['tags'],
                'video_provider' => $row['video_provider'],
                'video_link' => $row['video_link'],
                'auction_start_date' => strtotime($row['auction_start_date']),
                'auction_end_date' => strtotime($row['auction_end_date']),
                'meta_title' => $row['meta_title'],
                'meta_description' => $row['meta_description'],
                'est_shipping_days' => $row['est_shipping_days'] ?? '',
                'colors' => json_encode(array()),
                'choice_options' => json_encode(array()),
                'variations' => json_encode(array()),
                'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($row['slug']))) . '-' . Str::random(5),
            ]);

            ProductStock::create([
                'product_id' => $productId->id,
                'qty' => '1',
                'price' => 0,
                'sku' => $row['sku'] ?? '',
                'variant' => '',
            ]);

            if (isset($row['category_id'])) {
                $attributes = AuctionAttribute::where("category_id", $row['category_id'])->get();

                foreach ($attributes as $attribute) {

                    $attributeData = [  
                        "product_id" => $productId->id ?? '',
                        "category_id" => $row['category_id'],
                        "attribute_id" => $attribute->id,
                        "attribute_name" => $attribute->fields_name,
                        "status" => 1
                    ];

                    if ($row[format_to_underscore($attribute->fields_name)]) {
                        $attributeData["value"] = $this->downloadThumbnail($row[format_to_underscore($attribute->fields_name)]);
                    } else {
                        $attributeData["value"] = $row[format_to_underscore($attribute->fields_name)] ?? null;
                    }

                    AttributeProduct::insert($attributeData);
                }
            }

            if (isset($row['multi_categories']) && ($row['multi_categories'] != null)) {
                foreach (explode(',', $row['multi_categories']) as $category_id) {
                    ProductCategory::insert([
                        "product_id" => $productId->id,
                        "category_id" => $category_id
                    ]);
                }
            }
        }

        flash(translate('Auction Products imported successfully'))->success();
    }



    public function model(array $row)
    {
        ++$this->rows;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function rules(): array
    {   
        $validation = [
                'name' => 'required',
                'category_id' =>[
                    'required',
                    'numeric',
                    function ($attribute, $value, $fail) {
                        if (is_null($this->firstCategoryId)) {
                            $this->firstCategoryId = $value;
                        } elseif ($value != $this->firstCategoryId) {
                            $fail('All rows must have the same category ID.');
                        }
                    },
                ],

                'starting_bid' => 'required|numeric|gt:0',
                'estimate_start' => 'required|numeric|gt:0',
                'estimate_end' => 'required|numeric|gt:0',
            
                'auction_start_date' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $value = trim($value);
            
                        if ($value === '') {
                            return $fail('Auction Start Date is required.');
                        }
            
                        try {
                            $startDate = Carbon::createFromFormat('d-m-Y H:i:s', $value);
            
                            if (!$startDate || $startDate->format('d-m-Y H:i:s') !== $value) {
                                return $fail('Auction Start Date must be a valid date (d-m-Y H:i:s).');
                            }
            
                            if ($startDate->lessThanOrEqualTo(Carbon::now())) {
                                return $fail('Auction Start Date must be greater than the current date and time.');
                            }
                        } catch (\Exception $e) {
                            return $fail('Invalid Auction Start Date format.');
                        }
                    },
                ],

                'auction_end_date' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $value = trim($value);
        
                        if ($value === '') {
                            return $fail('Auction End Date is required.');
                        }
            
                        try {
                            $endDate = Carbon::createFromFormat('d-m-Y H:i:s', $value);
            
                            if ($endDate->format('d-m-Y H:i:s') !== $value) {
                                return $fail('Auction End Date must be a valid date (d-m-Y H:i) and greater than the Start Date.');
                            }
            
                            if ($endDate->lessThanOrEqualTo(Carbon::now())) {
                                return $fail('Auction End Date must be greater than the current date and time.');
                            }
                        } catch (\Exception $e) {
                            return $fail('Invalid Auction End Date format.');
                        }
                    },
                ],
                            
            ];
        
            if ($this->attributes) {
                foreach ($this->attributes as $key => $data) {
                    $validation[$key] = function ($attribute, $value, $onFailure) use ($data){
                        
                        if($data == 1){
                            if (empty($value)) {
                                $field =  explode('.',$attribute)[1];
                                $onFailure("{$field} is required");
                            }
                        }
                    };
                }
            }
        return  $validation;
    }

    public function prepareForValidation($data, $index)
    {
        // Convert timestamps to Carbon instances
        $auctionStartDate = isset($data['auction_start_date']) ? Carbon::parse($data['auction_start_date']) : null;
        $auctionEndDate = isset($data['auction_end_date']) ? Carbon::parse($data['auction_end_date']) : null;
        if ($auctionStartDate && $auctionEndDate) {
            if ($auctionEndDate->lessThan($auctionStartDate)) {
                $data['auction_end_date'] = 'invalid_date';
            }
        }
        return $data;
    }

    public function downloadThumbnail($url)
    {
        try {
            $upload = new Upload;
            $upload->external_link = $url;
            $upload->type = 'image';
            $upload->save();

            return $upload->id;
        } catch (\Exception $e) {
        }
        return null;
    }

    public function downloadGalleryImages($urls)
    {
        $data = array();
        foreach (explode(',', str_replace(' ', '', $urls)) as $url) {
            $data[] = $this->downloadThumbnail($url);
        }
        return implode(',', $data);
    }
}
