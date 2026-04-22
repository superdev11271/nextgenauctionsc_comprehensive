<?php

namespace Database\Seeders;

use App\Models\AuctionAttribute;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $vehicles_and_machinery = [
        ["fields_name" => 'Odometer', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'Engine Hours', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'Engine', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'Inside Front seat', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'Inside Back seat', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'inside Boot', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'Registration Papers', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'Receipts for work done', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'Reports if damaged.', "field_type" => "0", "field_optional" => "2"],
    ];


    public $general_fields = [
        ["fields_name" => 'Front', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'Back', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'Side one', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'Side two', "field_type" => "0", "field_optional" => "1"],
    ];

    public $truck = [
        ['fields_name' => 'Make', "field_type" => "1", "field_optional" => "1"],
        ['fields_name' => 'Model', "field_type" => "1", "field_optional" => "1"],
        ['fields_name' => 'Motor type', "field_type" => "1", "field_optional" => "1"],
        ['fields_name' => 'Motor HP', "field_type" => "1", "field_optional" => "1"],
        ['fields_name' => 'Gear box', "field_type" => "1", "field_optional" => "1"],
        ['fields_name' => 'Status', "field_type" => "1", "field_optional" => "1"],
    ];

    public $livestock = [
        ["fields_name" => 'Registration/Medical Paperwork', "field_type" => "0", "field_optional" => "1"],
        ["fields_name" => 'Injuries', "field_type" => "0", "field_optional" => "2"],
    ];

    public $category;
    public function run()
    {
        // AuctionAttribute::truncate();
        $categories = Category::all();
        foreach ($categories as $category) {
            $this->category = $category;

            $this->addCategoryIdToFields($this->general_fields);

            $isTruckCategory = ($category->name == "Trucks");
            $isLiveStockCategory = ($category->name == "Livestock and Pets" || $category->parentCategory?->name == "Livestock and Pets");
            $isTransprotCategory = ($category->name == "Transport" || $category->parentCategory?->name == "Transport" && $category->name != "Accessories");


            if ($isTruckCategory) {
                $this->addCategoryIdToFields($this->truck);
                $fields = array_merge($this->general_fields, $this->truck);
            }

            elseif ($isTransprotCategory) {
                $this->addCategoryIdToFields($this->vehicles_and_machinery);
                $fields = array_merge($this->general_fields, $this->vehicles_and_machinery);
            }

            elseif ($isLiveStockCategory) {
                $this->addCategoryIdToFields($this->livestock);
                $fields = array_merge($this->general_fields, $this->livestock);
            }else{
                $fields = $this->general_fields;
            }

            AuctionAttribute::insert($fields);
        }
    }
    private function addCategoryIdToFields(&$fields)
    {
        foreach ($fields as &$field) {
            $field['category_id'] = $this->category->id;
        }
    }
}
