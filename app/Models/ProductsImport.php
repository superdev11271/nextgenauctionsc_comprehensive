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
use Auth;
use Carbon\Carbon;
use Storage;

//class ProductsImport implements ToModel, WithHeadingRow, WithValidation
class ProductsImport implements ToCollection, WithHeadingRow, WithValidation, ToModel
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
        $canImport = true;
        
        $user = Auth::user();
        if (($user->user_type == 'seller' || $user->shop) && addon_is_activated('seller_subscription')) {
            if ( $user->shop?->created_product_count  > $user->shop?->product_upload_limit
                || $user->shop?->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($user->shop?->package_invalid_at), false) < 0
            ) {
                $canImport = false;
                flash(translate('Please upgrade your package.'))->warning();
            }
        }
        if (($user->user_type == 'seller' || $user->shop) && addon_is_activated('seller_subscription')) {
            $shop = Shop::where('user_id', $user->id)->first();
            $counter = $shop->created_product_count;
        }


        if ($canImport) {
            foreach ($rows as $row) {
                
                $this->rowFields = array_keys($row->toArray()); 
                $this->fieldsNames = AuctionAttribute::where('category_id', $row['category_id'])->pluck('fields_name');
                foreach ($this->fieldsNames as $fieldName) {
                    $formattedFieldName = format_to_underscore($fieldName);
                    if (!in_array($formattedFieldName, $this->rowFields)) {
                        flash("Attribute field name '{$fieldName}' is missing form the Excel");
                        return back();
                    }
                }

                $approved = 1;
                if (($user->user_type == 'seller' || $user->shop) && $user->shop && $user->shop?->verification_status == 0) {
                    $approved = 0;
                }

                $productId = Product::create([
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'added_by' => ($user->user_type == 'seller' || $user->shop) ? 'seller' : 'admin',
                    'user_id' =>  $user->id,
                    'approved' => $approved,
                    'category_id' => $row['category_id'],
                    'brand_id' => $row['brand_id'],
                    'auction_end_date' => strtotime($row['expiry_end_date']),
                    'video_provider' => $row['video_provider'],
                    'video_link' => $row['video_link'],
                    'tags' => $row['tags'],
                    'unit_price' => $row['unit_price'],
                    'unit' => $row['unit'],
                    'meta_title' => $row['meta_title'],
                    'meta_description' => $row['meta_description'],
                    'est_shipping_days' => $row['est_shipping_days'],
                    'colors' => json_encode(array()),
                    'choice_options' => json_encode(array()),
                    'variations' => json_encode(array()),
                    'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($row['slug']))) . '-' . Str::random(5),
                    'thumbnail_img' => $this->downloadThumbnail($row['thumbnail_img']),
                    'photos' => $this->downloadGalleryImages($row['photos']),
                ]);
                ProductStock::create([
                    'product_id' => $productId->id,
                    'qty' => $row['current_stock'],
                    'price' => $row['unit_price'],
                    'sku' => $row['sku'],
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

                if($row['multi_categories'] != null){
                    foreach (explode(',', $row['multi_categories']) as $category_id) {
                        ProductCategory::insert([
                            "product_id" => $productId->id,
                            "category_id" => $category_id
                        ]);
                    }
                }
                if (($user->user_type == 'seller' || $user->shop) && addon_is_activated('seller_subscription')) {
                    $counter++;
                }
            }
            if (($user->user_type == 'seller' || $user->shop) && addon_is_activated('seller_subscription')) {
                $shop->created_product_count = $counter;
                $shop->save();
            }

            flash(translate('Products imported successfully'))->success();
        }
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
            'category_id' => [
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
            'unit_price' => 'required|numeric|gt:0',
            'expiry_end_date' => [
                    function ($attribute, $value, $fail) {
                        $value = trim($value);
                        try {
                            $startDate = Carbon::createFromFormat('d-m-Y H:i:s', $value);
            
                            if (!$startDate || $startDate->format('d-m-Y H:i:s') !== $value) {
                                return $fail('Expiry Date must be a valid date (d-m-Y H:i:s).');
                            }
            
                            if ($startDate->lessThanOrEqualTo(Carbon::now())) {
                                return $fail('Expiry Date must be greater than the current date and time.');
                            }
                        } catch (\Exception $e) {
                            return $fail('Invalid Expiry Date format.');
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

        return $validation;
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
