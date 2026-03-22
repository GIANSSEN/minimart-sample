<?php
// app/Console/Commands/SyncStockData.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Stock;

class SyncStockData extends Command
{
    protected $signature = 'stock:sync';
    protected $description = 'Sync stock data for all products';

    public function handle()
    {
        $this->info('Syncing stock data...');

        $products = Product::doesntHave('stock')->get();
        $bar = $this->output->createProgressBar($products->count());

        foreach ($products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'quantity' => 0,
                'min_quantity' => $product->reorder_level ?? 10,
                'max_quantity' => $product->max_level ?? 1000,
                'location' => $product->shelf_location ?? 'A1',
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Stock data synced successfully!');
    }
}