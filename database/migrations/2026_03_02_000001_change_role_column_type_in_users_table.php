<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Converts the `role` column on `users` from an enum to a simple string.  The
     * application pulls available roles from the `roles` table (slug field), so
     * keeping the column as an enum was overly restrictive and caused mismatches
     * when new roles were added.
     *
     * **Note:** altering column types requires the `doctrine/dbal` package.
     * Run `composer require doctrine/dbal` before executing this migration.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // if using MySQL/i.e. the default array of allowed values is hardcoded,
            // we simply change it to string.  `change()` uses doctrine/dbal.
            $table->string('role')->default('cashier')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'merchandiser', 'cashier'])->default('cashier')->change();
        });
    }
};
