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
        Schema::create('autobid_intervals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('min_bid');
            $table->bigInteger('max_bid');
            $table->bigInteger('increment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autobid_intervals');
    }
};
