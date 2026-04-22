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
        Schema::create('addresses', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('address', 255)->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('state_id');
            $table->integer('city_id')->nullable();
            $table->float('longitude', 17, 15)->nullable();
            $table->float('latitude', 17, 15)->nullable();
            $table->string('postal_code', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->integer('set_default')->default(0);
            $table->string('address_type', 5)->default('1')->comment('1 => shipping, 2 => billing');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
