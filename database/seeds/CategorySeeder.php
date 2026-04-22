<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function subcategoryInsert ($subcategories, $category_id) {
        foreach($subcategories as $subcategory) {
            DB::table('categories')->insert([
                'parent_id' => $category_id,
                'level' => 1,
                'name' => $subcategory,
                'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $subcategory)).'-'.Str::random(5),
            ]);
        }
     }
    public function run()
    {
        $categories = [
            'Mining, Construction',
            'Agriculture',
            'Manufacturing & Engineering',
            'Cars, Bikes & Accessories',
            'Transport',
            'Marine',
            'Warehouse & Forklift',
            'Caravan – Motorhomes',
            'Home & Garden',
            'IT & Electronics',
            'Businesses for sale Gemstones & Jewellery',
            'Fine Arts',
            'Livestock and Pets',
            'Miscellaneous items'
        ];

        $mining_subcategory = [
            'Earth moving & Mobile Plant',
            'Construction, scaffolding & formwork',
            'Mining',
            'Road construction' 
        ];
        $mining_subcategory = [
            'Earth moving & Mobile Plant',
            'Construction, scaffolding & formwork',
            'Mining',
            'Road construction' 
        ];

        $agriculture_subcategory = [
            'Tractors',
            'Implements',
            'Cropping Machinery',
            'Livestock Machinery',
            'Orchard – Vineyard Machinery',
            'Specialist machinery',
            'Fertilizer',
            'Seed / Hay / Silage',
            'Other Farming products',
        ];

        $manufacturing_subcategory = [
            'Engineering',
            'Manufacturing',
            'Automotive Manufacturing',
            'Metalworking & Fabrication',
            'Printing & Packaging',
            'Plant hire – Transportable buildings – Sea containers',
            'Laboratory'
        ];

        $carbikes_subcategory = [
            'Cars',
            'Classic / Collectable Cars',
            'Luxury Cars',
            'Racing Cars (Speedway – Drags)',
            'Four Wheel drives',
            'Motorbikes / Mopeds / Off road bikes',
            'Salvage'
        ];

        $transport_subcategory = [
            'Trucks',
            'Trailers',
            'Dollies',
            'Accessories',
            'Buses'
        ];

        $marine_subcategory = [
            'Fishing Boat',
            'Power / Speed Boat',
            'Sail Boat',
            'House boat',
            'Dingy / Tinny',
            'Racing Boats',
            'Jetskis',
        ];

        $warehouse_subcategory = [
            'Forklifts',
            'Cranes',
            'Pallet truck',
            'Container handler',
            'Walk behind',
        ];

        $caravan_subcategory = [
            'Caravan',
            'Motorhome',
            'Camper trailers',
        ];

        $home_subcategory = [
            'Appliances and whitegoods',
            'Homewares',
            'Furniture',
            'Tools',
            'Revovations',
            'Outdoor and BBQ',
        ];

        $it_subcategory = [
            'Desktop PC',
            'Laptop',
            'Printers – Copiers – Scanners',
            'Smart phones – Tablets',
            'TV – Home theatre systems'
        ];

        $gemstones_subcategory = [
            'Fine Jewellery (Certified – Stamped)',
            'Rings',
            'Nacklaces',
            'Earrings',
            'Pendants – Broche',
            'Fine Watches (Certified Brands)',
            'Fine Diamonds and Gemstones',
            'Natural Ruby',
            'Natural Emerald',
            'Natural Diamond',
            'Natural Tanzanite',
            'Natural Sapphire',
            'Natural Pearl',
            'Natural Opal',
            'Coloured Gemstones (All other gemstones)',
            'Lab grown or Synthetic Diamonds/Gemstones',
            'Fashion Jewellery'
        ];

        $fineArts_subcategory = [
            'Historic Arts (Certified)',
            'Painting',
            'Prints',
            'Photography',
            'Drawing',
            'Books – Poetry',
        ];

        $livestock_subcategory = [
            'Dogs',
            'Cats',
            'Rabbits – Guinea pig',
            'Ferrets',
            'Mice – Rats',
            'Birds',
            'Horses and Ponies',
            'Camels – Llama - Alpaca',
            'Livestock',
            'Cattle',
            'Sheep',
            'Goats',
            'Chickens',
        ];

        DB::statement("TRUNCATE TABLE `categories`;");

        foreach($categories as $category) {
            $category_id = DB::table('categories')->insertGetId([
                'name' => $category,
                'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $category)).'-'.Str::random(5),
            ]);

            if($category == 'Mining, Construction' && isset($category_id)) {

                $this->subcategoryInsert($mining_subcategory, $category_id);
            }

            if($category == 'Agriculture' && isset($category_id)) {

                $this->subcategoryInsert($agriculture_subcategory, $category_id);
            }

            if($category == 'Manufacturing & Engineering' && isset($category_id)) {

                $this->subcategoryInsert($manufacturing_subcategory, $category_id);
            }

            if($category == 'Cars, Bikes & Accessories' && isset($category_id)) {
            
                $this->subcategoryInsert($carbikes_subcategory, $category_id);
            }

            if($category == 'Transport' && isset($category_id)) {
                
                $this->subcategoryInsert($transport_subcategory, $category_id);
            }

            if($category == 'Marine' && isset($category_id)) {

                $this->subcategoryInsert($marine_subcategory, $category_id);
            }

            if($category == 'Warehouse & Forklift' && isset($category_id)) {
                
                $this->subcategoryInsert($warehouse_subcategory, $category_id);
            }

            if($category == 'Caravan – Motorhomes' && isset($category_id)) {

                $this->subcategoryInsert($caravan_subcategory, $category_id);
            }

            if($category == 'Home & Garden' && isset($category_id)) {
                
                $this->subcategoryInsert($home_subcategory, $category_id);
            }

            if($category == 'IT & Electronics' && isset($category_id)) {
                
                $this->subcategoryInsert($it_subcategory, $category_id);
            }

            if($category == 'Businesses for sale Gemstones & Jewellery' && isset($category_id)) {
                
                $this->subcategoryInsert($gemstones_subcategory, $category_id);
            }

            if($category == 'Fine Arts' && isset($category_id)) {

                $this->subcategoryInsert($fineArts_subcategory, $category_id);
            } 

            if($category == 'Livestock and Pets' && isset($category_id)) {
                
                $this->subcategoryInsert($livestock_subcategory, $category_id);
            }

        }
    }
}
