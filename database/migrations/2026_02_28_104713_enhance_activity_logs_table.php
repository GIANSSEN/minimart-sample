<?php
// database/migrations/[timestamp]_enhance_activity_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('action_type')->nullable()->after('action'); // create, update, delete, login, logout
            $table->string('model_type')->nullable()->after('action_type'); // User, Product, Sale, etc.
            $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            $table->json('old_values')->nullable()->after('new_data');
            $table->json('new_values')->nullable()->after('old_values');
            $table->string('device')->nullable()->after('user_agent');
            $table->string('browser')->nullable()->after('device');
            $table->string('platform')->nullable()->after('browser');
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn([
                'action_type', 'model_type', 'model_id', 
                'old_values', 'new_values', 'device', 'browser', 'platform'
            ]);
        });
    }
};