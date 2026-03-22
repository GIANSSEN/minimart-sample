<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('min_quantity')->nullable();
            $table->integer('max_quantity')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
            
            $table->index('quantity');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};