<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatTamplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tamplates =[
            [
                'message' => "I can bid",
                'used_by' => "bidder",
                'with_amount' => 1,
            ],
            [
                'message' => "This is my last bid.",
                'used_by' => "bidder",
                'with_amount' => 1,
            ],
            [
                'message' => "Please consider my last bid.",
                'used_by' => "bidder",
                'with_amount' => 0,
            ],
            [
                'message' => "Can you go higher",
                'used_by' => "seller",
                'with_amount' => 0,
            ],
            [
                'message' => "Make your best offer.",
                'used_by' => "seller",
                'with_amount' => 0,
            ],
            [
                'message' => "I am about to sell.",
                'used_by' => "seller",
                'with_amount' => 0,
            ],
            [
                'message' => "Can you go up to.",
                'used_by' => "seller",
                'with_amount' => 1,
            ],
        ];
        foreach($tamplates as $tamplate) {
            DB::table('chat_tamplates')->insert([
                'message' => $tamplate["message"],
                'used_by' => $tamplate["used_by"],
                'with_amount' => $tamplate["with_amount"],
            ]);
        }
    }
}
