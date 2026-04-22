<?php

namespace Database\Seeders;

use App\Models\AuctionAttribute;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            AuctionAttribute::insert([
                "fields_name" => 'City',
                "field_type" => "3",
                "field_optional" => "2",
                'category_id' => $category->id,
                'dd_value' => 'AutoPopulated,'
            ]);
        }
    }
}
