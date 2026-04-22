<?php

namespace Database\Seeders;

use App\Models\AttributeProduct;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CityProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $auctionProducts = Product::where([['auction_product','=',1]])->get();
        $cities = ['Sydney',
                    'Melbourne',
                    'Brisbane',
                    'Perth',
                    'Adelaide'];


        foreach ($auctionProducts as $product) {
            $cityAttributeID = $product->main_category?->auctionAttributes?->where('fields_name','City')?->first()?->id;
            if ($cityAttributeID ==null ) continue;
            $payload = [
                "product_id" => $product->id,
                "category_id" => $product->main_category->id,
                "attribute_id" => $cityAttributeID,
                "attribute_name" => 'City',
                "value" => $cities[array_rand($cities)],
                "status" => 1
            ];
            AttributeProduct::insert($payload);
            // dd($payload);
        }
    }
}
