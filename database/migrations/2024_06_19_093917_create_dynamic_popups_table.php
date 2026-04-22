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
        Schema::create('dynamic_popups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->default(0);
            $table->string('title');
            $table->text('summary');
            $table->string('banner')->nullable();
            $table->string('btn_link');
            $table->string('btn_text')->nullable();
            $table->string('btn_text_color')->nullable();
            $table->string('btn_background_color')->nullable();
            $table->string('show_subscribe_form')->nullable();
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
        Schema::dropIfExists('dynamic_popups');
    }
};
