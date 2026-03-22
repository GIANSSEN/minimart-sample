<?php
// database/migrations/[timestamp]_create_product_variations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->string('variation_code')->unique();
            $table->string('variation_name');
            $table->enum('variation_type', [
                'size', 
                'color', 
                'style', 
                'material', 
                'flavor',
                'packaging',
                'other'
            ])->default('other');
            $table->string('value')->nullable(); // e.g., "XL", "Red", "Cotton"
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // for color swatches, etc.
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variations');
    }
};