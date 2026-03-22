<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no')->unique();
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->enum('transaction_type', ['in', 'out', 'adjustment']);
            $table->integer('quantity');
            $table->integer('old_quantity')->nullable();
            $table->integer('new_quantity')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index('transaction_no');
            $table->index('product_id');
            $table->index('transaction_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};