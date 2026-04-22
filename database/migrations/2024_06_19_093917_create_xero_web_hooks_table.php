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
        Schema::create('xero_web_hooks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('resource_url');
            $table->json('data')->nullable()->comment('Data is stored after the webhook fires event then a fetch request is made to fetch the data.');
            $table->string('event_category');
            $table->string('event_type');
            $table->enum('status', ['pending', 'processed', 'rejected'])->default('pending')->comment('Status should be rejected if event is not the expected one like payment is not fully paid.');
            $table->string('status_description')->nullable()->comment('Store description: why the status is set to rejected.');
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
        Schema::dropIfExists('xero_web_hooks');
    }
};
