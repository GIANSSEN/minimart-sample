<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->string('released_by')->nullable()->after('received_by');
            $table->string('customer_name')->nullable()->after('released_by');
            $table->string('authorized_by')->nullable()->after('customer_name');
            $table->decimal('unit_price', 15, 2)->default(0)->after('unit_cost');
            $table->decimal('total_value', 15, 2)->default(0)->after('total_cost');
            $table->string('transaction_time')->nullable()->after('received_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'released_by',
                'customer_name',
                'authorized_by',
                'unit_price',
                'total_value',
                'transaction_time'
            ]);
        });
    }
};
