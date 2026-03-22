<?php
// database/migrations/[timestamp]_update_activity_logs_table_add_columns.php

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
        Schema::table('activity_logs', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('activity_logs', 'action_type')) {
                $table->string('action_type')->nullable()->after('action');
            }
            
            if (!Schema::hasColumn('activity_logs', 'model_type')) {
                $table->string('model_type')->nullable()->after('action_type');
            }
            
            if (!Schema::hasColumn('activity_logs', 'model_id')) {
                $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            }
            
            if (!Schema::hasColumn('activity_logs', 'old_data')) {
                $table->text('old_data')->nullable()->after('description');
            }
            
            if (!Schema::hasColumn('activity_logs', 'new_data')) {
                $table->text('new_data')->nullable()->after('old_data');
            }
            
            if (!Schema::hasColumn('activity_logs', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn([
                'action_type',
                'model_type',
                'model_id',
                'old_data',
                'new_data',
                'user_agent'
            ]);
        });
    }
};