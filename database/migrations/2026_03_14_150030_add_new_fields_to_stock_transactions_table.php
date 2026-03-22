<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null')->after('product_id');
            $table->decimal('unit_cost', 15, 2)->default(0)->after('quantity');
            $table->decimal('total_cost', 15, 2)->default(0)->after('unit_cost');
            $table->date('received_date')->nullable()->after('total_cost');
            $table->string('received_by')->nullable()->after('received_date');
            $table->string('reference')->nullable()->after('received_by');
            $table->string('location')->nullable()->after('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn([
                'supplier_id',
                'unit_cost',
                'total_cost',
                'received_date',
                'received_by',
                'reference',
                'location'
            ]);
        });
    }
};
