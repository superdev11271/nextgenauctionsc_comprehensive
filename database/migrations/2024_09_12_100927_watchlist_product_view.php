<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // adding extra columns for send mail check;
        Schema::table('wishlists', function (Blueprint $table) {
            $table->boolean('email_sent')->default(false);
            $table->integer('email_send_count')->default(0);
        });

        // $query = DB::table('wishlists')
        //     ->join('users', 'users.id', '=', 'wishlists.user_id')
        //     ->join('products', 'products.id', '=', 'wishlists.product_id')
        //     ->select('wishlists.id', 'users.email as user_email', 'users.name as user_name','products.name as product_name','products.auction_start_date','products.auction_end_date','products.auction_product','wishlists.email_sent','wishlists.email_send_count','products.slug as product_slug')
        //     ->where('wishlists.email_sent', 0)
        //     ->toSql();

        // DB::statement("CREATE VIEW watchlist_view AS {$query}");

        $sql = "
            CREATE VIEW watchlist_view AS
            SELECT
                wishlists.id,
                wishlists.product_id AS product_id,
                users.id AS user_id,
                users.email AS user_email,
                users.name AS user_name,
                products.name AS product_name,
                products.auction_start_date,
                products.auction_end_date,
                products.auction_product,
                wishlists.email_sent,
                wishlists.email_send_count,
                products.slug AS product_slug,
                products.thumbnail_img AS product_thumbnail_img
            FROM
                wishlists
            JOIN
                users ON users.id = wishlists.user_id
            JOIN
                products ON products.id = wishlists.product_id
            WHERE
                wishlists.email_sent = 0
        ";

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS watchlist_view");

        // Remove extra columns
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropColumn(['email_sent', 'email_send_count']);
        });
    }
};
