<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::statement("
            CREATE VIEW product_calculated_price_view AS
            SELECT
            p.id,
            p.name,
            p.unit_price,
            p.discount,
            p.discount_type,
            p.discount_start_date,
            p.discount_end_date,
            CASE
                WHEN CURDATE() BETWEEN COALESCE(p.discount_start_date, CURDATE()) AND COALESCE(FROM_UNIXTIME(p.discount_end_date), CURDATE()) THEN
                    'Applicable'
                ELSE 'Not Applicable'
            END as discount_status,
            CASE
                WHEN p.discount_type = 'percent' THEN p.unit_price * (p.discount / 100)
                WHEN p.discount_type = 'amount' THEN p.discount
                ELSE 0
            END AS calculated_discount,
            SUM(
                CASE WHEN pt.tax_type = 'percent' THEN pt.tax ELSE 0 END
            ) AS tax_percentage,
            SUM(
                CASE WHEN pt.tax_type = 'amount' THEN pt.tax ELSE 0 END
            ) AS tax_flat,
            (p.unit_price +
                SUM(
                    CASE
                        WHEN pt.tax_type = 'percent' THEN p.unit_price * (pt.tax / 100)
                        WHEN pt.tax_type = 'amount' THEN pt.tax
                        ELSE 0
                    END
                ) -
                CASE
                    WHEN CURDATE() BETWEEN COALESCE(p.discount_start_date, CURDATE()) AND COALESCE(FROM_UNIXTIME(p.discount_end_date), CURDATE()) THEN
                        CASE
                            WHEN p.discount_type = 'percent' THEN p.unit_price * (p.discount / 100)
                            WHEN p.discount_type = 'amount' THEN p.discount
                            ELSE 0
                        END
                    ELSE 0
                END
            ) AS calculated_price
        FROM
            products p
        LEFT JOIN
            product_taxes pt ON pt.product_id = p.id
        GROUP BY
            p.id,
            p.name,
            p.unit_price,
            p.discount,
            p.discount_type;
        ");
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS product_calculated_price_view');
    }
};


// CASE
// WHEN p.discount_type = 'percentage' THEN p.unit_price - (p.unit_price * (p.discount / 100))
// WHEN p.discount_type = 'flat' THEN p.unit_price - p.discount
// ELSE p.unit_price
// END AS final_price
