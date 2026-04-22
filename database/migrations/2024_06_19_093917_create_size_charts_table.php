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
        Schema::create('size_charts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->integer('category_id')->unique('category_id');
            $table->string('fit_type')->nullable();
            $table->string('stretch_type')->nullable();
            $table->string('photos')->nullable();
            $table->text('description')->nullable();
            $table->string('measurement_points', 255);
            $table->string('size_options', 255);
            $table->string('measurement_option')->nullable();
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
        Schema::dropIfExists('size_charts');
    }
};
