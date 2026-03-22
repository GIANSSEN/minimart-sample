<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique();
            $table->string('customer_name')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->enum('payment_method', ['cash', 'card', 'gcash', 'paymaya'])->default('cash');
            $table->decimal('amount_tendered', 15, 2);
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->enum('status', ['completed', 'voided', 'refunded'])->default('completed');
            $table->text('notes')->nullable();
            $table->foreignId('cashier_id')->constrained('users');
            $table->foreignId('voided_by')->nullable()->constrained('users');
            $table->timestamp('voided_at')->nullable();
            $table->string('void_reason')->nullable();
            $table->timestamps();
            
            $table->index('receipt_no');
            $table->index('cashier_id');
            $table->index('created_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};