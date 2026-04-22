<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        for ($i = 0; $i < 10; $i++) {
            User::create(['name' => fake()->firstName(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'user_type' => fake()->text('seller'),
            'password'=> bcrypt("12345678"),
            'email_verified_at' => now(),
            'remember_token' => str::random(8),]);

        }
        

   
    }

}


