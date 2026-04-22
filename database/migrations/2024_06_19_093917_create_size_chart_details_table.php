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
        Schema::create('size_chart_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('size_chart_id');
            $table->integer('measurement_point_id');
            $table->integer('attribute_value_id');
            $table->string('inch_value')->nullable();
            $table->string('cen_value')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('size_chart_details');
    }
};
