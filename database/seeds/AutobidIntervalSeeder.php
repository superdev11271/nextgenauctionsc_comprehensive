<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AutobidIntervalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bid_increments = [
            ["min_bid" => 0, "max_bid" => 5, "increment" => 1],
            ["min_bid" => 6, "max_bid" => 20, "increment" => 2],
            ["min_bid" => 21, "max_bid" => 50, "increment" => 5],
            ["min_bid" => 51, "max_bid" => 200, "increment" => 10],
            ["min_bid" => 201, "max_bid" => 500, "increment" => 20],
            ["min_bid" => 501, "max_bid" => 1000, "increment" => 50],
            ["min_bid" => 1001, "max_bid" => 2000, "increment" => 100],
            ["min_bid" => 2001, "max_bid" => 5000, "increment" => 200],
            ["min_bid" => 5001, "max_bid" => 9999, "increment" => 300],
            ["min_bid" => 10001, "max_bid" => 25000, "increment" => 500],
            ["min_bid" => 25001, "max_bid" => 50000, "increment" => 1000],
            ["min_bid" => 50001, "max_bid" => 120000, "increment" => 2000],
            ["min_bid" => 120001, "max_bid" => 999999999, "increment" => 5000]
        ];
        \App\Models\AutobidInterval::truncate();
        foreach ($bid_increments as $bid_increment) {
            \App\Models\AutobidInterval::create($bid_increment);
        }
    }
}
