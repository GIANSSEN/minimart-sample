<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('barcode')->unique()->nullable();
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->string('brand')->nullable();
            $table->string('unit')->default('pcs');
            $table->decimal('cost_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->decimal('wholesale_price', 15, 2)->nullable();
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(12);
            $table->integer('reorder_level')->default(10);
            $table->integer('max_level')->nullable();
            $table->integer('min_level')->nullable();
            $table->string('shelf_location')->nullable();
            $table->string('image')->nullable();
            $table->boolean('has_variants')->default(false);
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['product_code', 'barcode']);
            $table->index('product_name');
            $table->index('status');
            $table->index('category_id');
            $table->index('supplier_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};