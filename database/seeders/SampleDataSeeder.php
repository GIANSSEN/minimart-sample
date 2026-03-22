<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Stock;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        
        Stock::withTrashed()->forceDelete();
        Product::withTrashed()->forceDelete();
        Brand::withTrashed()->forceDelete();
        Supplier::withTrashed()->forceDelete();
        Category::withTrashed()->forceDelete();
        
        Schema::enableForeignKeyConstraints();

        $this->call([
            CategorySeeder::class,
            SupplierSeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
