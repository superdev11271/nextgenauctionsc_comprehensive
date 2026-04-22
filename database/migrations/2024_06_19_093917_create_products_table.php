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
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 200)->index('name');
            $table->string('added_by', 6)->default('admin');
            $table->integer('user_id');
            $table->integer('category_id');
            $table->integer('brand_id')->nullable();
            $table->string('photos', 2000)->nullable();
            $table->string('thumbnail_img', 100)->nullable();
            $table->string('video_provider', 20)->nullable();
            $table->string('video_link', 100)->nullable();
            $table->string('tags', 500)->nullable()->index('tags');
            $table->longText('description')->nullable();
            $table->double('unit_price', 20, 2)->index('unit_price');
            $table->double('purchase_price', 20, 2)->nullable();
            $table->integer('variant_product')->default(0);
            $table->string('attributes', 1000)->default('[]');
            $table->mediumText('choice_options')->nullable();
            $table->mediumText('colors')->nullable();
            $table->text('variations')->nullable();
            $table->integer('todays_deal')->default(0);
            $table->integer('published')->default(0);
            $table->boolean('approved')->default(false);
            $table->string('stock_visibility_state', 10)->default('quantity');
            $table->boolean('cash_on_delivery')->default(false)->comment('1 = On, 0 = Off');
            $table->integer('featured')->default(0);
            $table->integer('new_arraival');
            $table->integer('top_rate');
            $table->integer('best_seller');
            $table->integer('seller_featured')->default(0);
            $table->integer('current_stock')->default(0);
            $table->string('unit', 20)->nullable();
            $table->decimal('weight', 10)->nullable();
            $table->integer('min_qty')->default(1);
            $table->integer('low_stock_quantity')->nullable();
            $table->double('discount', 20, 2)->nullable();
            $table->string('discount_type', 10)->nullable();
            $table->integer('discount_start_date')->nullable();
            $table->integer('discount_end_date')->nullable();
            $table->double('starting_bid', 20, 2)->nullable()->default(0);
            $table->integer('auction_start_date')->nullable();
            $table->integer('auction_end_date')->nullable();
            $table->double('tax', 20, 2)->nullable();
            $table->string('tax_type', 10)->nullable();
            $table->string('shipping_type', 20)->nullable()->default('flat_rate');
            $table->double('shipping_cost', 20, 2)->default(0);
            $table->boolean('is_quantity_multiplied')->default(false)->comment('1 = Mutiplied with shipping cost');
            $table->integer('est_shipping_days')->nullable();
            $table->integer('num_of_sale')->default(0);
            $table->mediumText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('meta_img', 255)->nullable();
            $table->string('pdf', 255)->nullable();
            $table->mediumText('slug');
            $table->double('rating', 8, 2)->default(0);
            $table->string('barcode', 255)->nullable();
            $table->integer('digital')->default(0);
            $table->integer('auction_product')->default(0);
            $table->string('file_name', 255)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->string('external_link', 500)->nullable();
            $table->string('external_link_btn', 255)->nullable()->default('Buy Now');
            $table->integer('wholesale_product')->default(0);
            $table->string('frequently_brought_selection_type', 19)->nullable()->default('product');
            $table->timestamp('created_at')->useCurrent()->index('created_at');
            $table->timestamp('updated_at')->useCurrent();
            $table->string('lot', 250)->nullable();
            $table->longText('shipping_info')->nullable();
            $table->longText('terms_conditions')->nullable();
            $table->float('estimate_start', 10)->nullable();
            $table->float('estimate_end', 10)->nullable();
            $table->string('auction_number', 255)->nullable()->default('0');
            $table->dateTime('deleted_at')->nullable();
            $table->string('pickup_days')->nullable();
            $table->tinyText('pickup_time')->nullable();
            $table->string('sold_status')->nullable()->comment('only for auction product.');
            $table->bigInteger('sold_to')->nullable()->comment('only for auction product.');
            $table->decimal('reserved_price', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
