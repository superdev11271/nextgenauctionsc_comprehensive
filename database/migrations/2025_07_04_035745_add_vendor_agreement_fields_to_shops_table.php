<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('business_name')->nullable();
            $table->string('vendor_type')->nullable();
            $table->string('abn')->nullable();
            $table->string('acn')->nullable();
            $table->string('gst_registered')->nullable();

            $table->string('director1_name')->nullable();
            $table->string('director1_phone')->nullable();
            $table->string('director1_email')->nullable();
            $table->string('director2_name')->nullable();
            $table->string('director2_phone')->nullable();
            $table->string('director2_email')->nullable();

            $table->string('business_address')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('business_phone')->nullable();

            $table->string('contact1_mobile')->nullable();
            $table->string('contact2_mobile')->nullable();
            $table->string('contact3_mobile')->nullable();

            // $table->decimal('commission', 8, 2)->nullable();
            $table->text('vendor_costs')->nullable();
            $table->json('basis')->nullable();

            $table->decimal('photo_cost', 8, 2)->nullable();
            $table->decimal('catalogue_cost', 8, 2)->nullable();
            $table->decimal('staff_cost', 8, 2)->nullable();
            $table->decimal('travel_cost', 8, 2)->nullable();
            $table->decimal('air_travel_cost', 8, 2)->nullable();
            $table->string('other_costs')->nullable();

            $table->string('ack_name')->nullable();
            $table->string('ack_company')->nullable();
           $table->longText('signature')->nullable();
            $table->date('signed_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
                'business_name', 'vendor_type', 'abn', 'acn', 'gst_registered',
                'director1_name', 'director1_phone', 'director1_email',
                'director2_name', 'director2_phone', 'director2_email',
                'business_address', 'postal_address', 'business_phone',
                'contact1_mobile', 'contact2_mobile', 'contact3_mobile',
                'commission', 'vendor_costs', 'basis',
                'photo_cost', 'catalogue_cost', 'staff_cost',
                'travel_cost', 'air_travel_cost', 'other_costs',
                'ack_name', 'ack_company', 'signature', 'signed_date'
            ]);
        });
    }
};
