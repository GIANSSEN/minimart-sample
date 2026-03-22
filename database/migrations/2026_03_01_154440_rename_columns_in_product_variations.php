<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_variations', function (Blueprint $table) {
            $table->renameColumn('name', 'variant_name');
            $table->renameColumn('value', 'variant_value');
            // Optional: keep 'type' column or remove it
        });
    }

    public function down()
    {
        Schema::table('product_variations', function (Blueprint $table) {
            $table->renameColumn('variant_name', 'name');
            $table->renameColumn('variant_value', 'value');
        });
    }
};