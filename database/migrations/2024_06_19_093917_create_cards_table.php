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
        Schema::create('cards', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('buying_card_no', 255);
            $table->string('buying_expiry_year', 255);
            $table->string('buying_expiry_month', 255);
            $table->string('buying_cvc', 255);
            $table->string('selling_card_no', 250);
            $table->string('selling_expiry_month', 250);
            $table->string('selling_expiry_year', 250);
            $table->string('selling_cvc', 250);
            $table->text('card_details');
            $table->string('selling_paypal_email', 250);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
};
