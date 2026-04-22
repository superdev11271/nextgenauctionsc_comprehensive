<?php

use App\Models\EmailTemplate;
use Database\Seeders\AttributeSeeder;
use Database\Seeders\AutobidIntervalSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ChatTamplateSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Database\Seeders\AddExtraPermissionSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //  if table has data then do not run the seeder
        if (!Schema::hasTable('categories')) {
            $this->call(CategorySeeder::class);
        }
        if (!Schema::hasTable('attributes')) {
            $this->call(AttributeSeeder::class);
        }
        if (!Schema::hasTable('chat_templates')) {
            $this->call(ChatTamplateSeeder::class);
        }
        if (!Schema::hasTable('autobid_intervals')) {
            $this->call(AutobidIntervalSeeder::class);
        }
        $this->call(AddExtraPermissionSeeder::class);
        Artisan::call('db:import '.base_path('database/seeds/seeder.sql'));
        Artisan::call('permission:cache-reset');

        EmailTemplate::updateOrCreate(
    ['name' => 'auction_won'],
    ['subject' => 'Congratulations! You\'ve Won the Auction', 'body' => 'Reserve has been met. Invoice will follow shortly.']
);

EmailTemplate::updateOrCreate(
    ['name' => 'auction_lost'],
    ['subject' => 'Auction Ended - Reserve Not Met', 'body' => 'Reserve not met - You can still make an after offer.']
);
    }


    // enable default country based on the domain
    protected function enableDefaultCountry()
    {
        $defaultDomain = config('setting.domain.default_domain');
        // countries make status 1 in countries table
         DB::table('countries')->whereIn('code', $defaultDomain)->update(['status' => 1]);
        // enable states based on the country
        $countries = DB::table('countries')->whereIn('code', $defaultDomain)->first();
        DB::table('states')->where('country_id', $countries->id)->update(['status' => 1]);
        $state_ids = DB::table('states')->where('status', 1)->pluck('id');
        DB::table('cities')->whereIn('state_id', $state_ids)->update(['status' => 1]);
    }
}
