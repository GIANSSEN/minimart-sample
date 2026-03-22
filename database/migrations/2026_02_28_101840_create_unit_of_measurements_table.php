<?php
// database/migrations/[timestamp]_create_unit_of_measurements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('unit_of_measurements', function (Blueprint $table) {
            $table->id();
            $table->string('uom_code')->unique();
            $table->string('uom_name');
            $table->string('symbol', 10);
            $table->enum('type', ['piece', 'weight', 'volume', 'length', 'package'])->default('piece');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit_of_measurements');
    }
};