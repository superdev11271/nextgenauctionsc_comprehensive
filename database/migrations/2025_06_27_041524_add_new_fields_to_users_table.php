<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Personal Name Fields
            $table->string('first_name', 100)->after('name')->nullable();
            $table->string('last_name', 100)->after('first_name')->nullable();

            // Detailed Address Fields
            $table->string('street_number', 50)->after('address')->nullable();
            $table->string('street_name', 255)->after('street_number')->nullable();
            $table->string('suburb', 100)->after('street_name')->nullable();

            // Business Fields
            $table->boolean('is_business')->after('suburb')->default(0);
            $table->string('business_name', 255)->after('is_business')->nullable();
            $table->string('abn_can', 50)->after('business_name')->nullable();
            $table->string('business_phone', 20)->after('abn_can')->nullable();

            // // Backfill existing name into first_name/last_name
            // DB::statement("UPDATE users SET first_name = SUBSTRING_INDEX(name, ' ', 1)");
            // DB::statement("UPDATE users SET last_name = TRIM(SUBSTRING(name, LENGTH(SUBSTRING_INDEX(name, ' ', 1)) + 1))");
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'street_number',
                'street_name',
                'suburb',
                'is_business',
                'business_name',
                'abn_can',
                'business_phone'
            ]);
        });
    }
}
