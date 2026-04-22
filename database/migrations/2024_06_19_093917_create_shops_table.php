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
        Schema::create('shops', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('name', 200)->nullable();
            $table->string('logo', 255)->nullable();
            $table->longText('sliders')->nullable();
            $table->string('top_banner')->nullable();
            $table->string('banner_full_width_1')->nullable();
            $table->string('banners_half_width')->nullable();
            $table->string('banner_full_width_2')->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('address', 500)->nullable();
            $table->double('rating', 3, 2)->default(0);
            $table->integer('num_of_reviews')->default(0);
            $table->integer('num_of_sale')->default(0);
            $table->integer('seller_package_id')->nullable();
            $table->integer('product_upload_limit')->default(0);
            $table->integer('created_product_count')->default(0);
            $table->date('package_invalid_at')->nullable();
            $table->integer('verification_status')->default(0);
            $table->longText('verification_info')->nullable();
            $table->integer('rejected')->nullable();
            $table->text('remark')->nullable();
            $table->integer('cash_on_delivery_status')->default(0);
            $table->double('admin_to_pay', 20, 2)->default(0);
            $table->string('facebook', 255)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->string('google', 255)->nullable();
            $table->string('twitter', 255)->nullable();
            $table->string('youtube', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('pick_up_point_id')->nullable();
            $table->double('shipping_cost', 20, 2)->default(0);
            $table->float('delivery_pickup_latitude', 17, 15)->nullable();
            $table->float('delivery_pickup_longitude', 17, 15)->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_acc_name', 200)->nullable();
            $table->string('bank_acc_no', 50)->nullable();
            $table->integer('bank_routing_no')->nullable();
            $table->integer('bank_payment_status')->default(0);
            $table->string('gst_number', 255)->nullable();
            $table->integer('premium')->nullable()->comment('1=>premium seller; 0=> normal seller');
            $table->float('commission', 10, 0)->nullable()->comment('value in %');
            $table->string('agrement_form', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
};
