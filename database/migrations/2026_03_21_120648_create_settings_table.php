<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50)->default('general')->index();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('text');
            $table->timestamps();
        });

        // Seed default settings
        $defaults = [
            // General Group
            ['key' => 'store_name',            'value' => "CJ's Minimart", 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_email',           'value' => 'admin@cjsminimart.com', 'group' => 'general', 'type' => 'email'],
            ['key' => 'store_phone',           'value' => '', 'group' => 'general', 'type' => 'text'],
            ['key' => 'store_address',         'value' => '', 'group' => 'general', 'type' => 'textarea'],
            ['key' => 'store_website',         'value' => '', 'group' => 'general', 'type' => 'url'],
            ['key' => 'store_logo',            'value' => null, 'group' => 'general', 'type' => 'image'],

            // Localization Group
            ['key' => 'timezone',              'value' => 'Asia/Manila', 'group' => 'localization', 'type' => 'select'],
            ['key' => 'date_format',           'value' => 'M d, Y', 'group' => 'localization', 'type' => 'select'],
            ['key' => 'time_format',           'value' => 'h:i A', 'group' => 'localization', 'type' => 'select'],

            // Currency & Pricing Group
            ['key' => 'currency_symbol',       'value' => '₱', 'group' => 'currency', 'type' => 'text'],
            ['key' => 'currency_code',         'value' => 'PHP', 'group' => 'currency', 'type' => 'text'],
            ['key' => 'decimal_places',        'value' => '2', 'group' => 'currency', 'type' => 'number'],
            ['key' => 'tax_mode',              'value' => 'exclusive', 'group' => 'currency', 'type' => 'select'],
            ['key' => 'default_tax_rate',      'value' => '12', 'group' => 'currency', 'type' => 'number'],

            // Receipt Group
            ['key' => 'receipt_header',        'value' => "CJ's Minimart", 'group' => 'receipt', 'type' => 'text'],
            ['key' => 'receipt_footer',        'value' => 'Thank you for shopping with us!', 'group' => 'receipt', 'type' => 'text'],
            ['key' => 'receipt_show_tax',      'value' => '1', 'group' => 'receipt', 'type' => 'boolean'],
            ['key' => 'receipt_show_barcode',  'value' => '0', 'group' => 'receipt', 'type' => 'boolean'],

            // Inventory Group
            ['key' => 'low_stock_threshold',   'value' => '10', 'group' => 'inventory', 'type' => 'number'],
            ['key' => 'near_expiry_days',      'value' => '30', 'group' => 'inventory', 'type' => 'number'],

            // System Group
            ['key' => 'last_cache_cleared',    'value' => null, 'group' => 'system', 'type' => 'datetime'],
        ];

        foreach ($defaults as $setting) {
            DB::table('settings')->insertOrIgnore([
                'key'        => $setting['key'],
                'value'      => $setting['value'],
                'group'      => $setting['group'],
                'type'       => $setting['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
