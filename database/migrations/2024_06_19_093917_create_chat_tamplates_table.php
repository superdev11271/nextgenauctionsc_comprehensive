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
        Schema::create('chat_tamplates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('message');
            $table->enum('used_by', ['seller', 'bidder'])->default('seller');
            $table->boolean('with_amount')->default(false);
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
        Schema::dropIfExists('chat_tamplates');
    }
};
