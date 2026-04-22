<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_coupons', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('coupon_id');
            $table->string('coupon_code', 255);
            $table->double('min_buy', 20, 2);
            $table->integer('validation_days');
            $table->double('discount', 20, 2);
            $table->string('discount_type', 20);
            $table->integer('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_coupons');
    }
};
