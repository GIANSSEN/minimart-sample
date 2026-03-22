<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        
        DB::table('stocks')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('brands')->truncate();
        
        Schema::enableForeignKeyConstraints();
    }
}
