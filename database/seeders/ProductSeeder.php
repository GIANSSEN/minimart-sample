<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Category;
use App\Models\Supplier;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // Beverages (Category 1)
            [
                'product_code' => 'PRD001',
                'barcode' => '4801234567890',
                'product_name' => 'Coca-Cola 1.5L',
                'category_id' => 1,
                'supplier_id' => 1,
                'brand' => 'Coca-Cola',
                'description' => 'Coca-Cola soft drink 1.5L bottle',
                'cost_price' => 45.00,
                'selling_price' => 65.00,
                'unit' => 'bottle',
                'reorder_level' => 10,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 50,
                'image' => 'uploads/products/1773254365_69b1b6ddb10a8.jpg'
            ],
            [
                'product_code' => 'PRD002',
                'barcode' => '4801234567891',
                'product_name' => 'Sprite 1.5L',
                'category_id' => 1,
                'supplier_id' => 1,
                'brand' => 'Sprite',
                'description' => 'Sprite soft drink 1.5L bottle',
                'cost_price' => 45.00,
                'selling_price' => 65.00,
                'unit' => 'bottle',
                'reorder_level' => 10,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 45,
                'image' => 'uploads/products/1773255810_69b1bc827190f.jpg'
            ],
            [
                'product_code' => 'PRD003',
                'barcode' => '4801234567892',
                'product_name' => 'Royal 1.5L',
                'category_id' => 1,
                'supplier_id' => 1,
                'brand' => 'Royal',
                'description' => 'Royal soft drink 1.5L bottle',
                'cost_price' => 45.00,
                'selling_price' => 65.00,
                'unit' => 'bottle',
                'reorder_level' => 10,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 40,
                'image' => 'uploads/products/1773255929_69b1bcf906466.jpg'
            ],
            
            // Snacks (Category 2)
            [
                'product_code' => 'PRD004',
                'barcode' => '4801234567893',
                'product_name' => 'Piattos Sour Cream 85g',
                'category_id' => 2,
                'supplier_id' => 2,
                'brand' => 'Piattos',
                'description' => 'Piattos potato chips sour cream flavor',
                'cost_price' => 25.00,
                'selling_price' => 38.00,
                'unit' => 'pouch',
                'reorder_level' => 20,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 100,
                'image' => 'uploads/products/1773504342_69b58756e69e9.png'
            ],
            [
                'product_code' => 'PRD005',
                'barcode' => '4801234567894',
                'product_name' => 'Nova Country Cheddar 85g',
                'category_id' => 2,
                'supplier_id' => 2,
                'brand' => 'Nova',
                'description' => 'Nova potato chips cheddar flavor',
                'cost_price' => 25.00,
                'selling_price' => 38.00,
                'unit' => 'pouch',
                'reorder_level' => 20,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 95,
                'image' => 'uploads/products/1773504420_69b587a44842c.jpg'
            ],
            [
                'product_code' => 'PRD006',
                'barcode' => '4801234567895',
                'product_name' => 'V-Cut Hot & Spicy 85g',
                'category_id' => 2,
                'supplier_id' => 2,
                'brand' => 'V-Cut',
                'description' => 'V-Cut potato chips hot & spicy flavor',
                'cost_price' => 25.00,
                'selling_price' => 38.00,
                'unit' => 'pouch',
                'reorder_level' => 20,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 90,
                'image' => 'uploads/products/1773511222_69b5a23607563.jpg'
            ],
            
            // Canned Goods (Category 3)
            [
                'product_code' => 'PRD007',
                'barcode' => '4801234567896',
                'product_name' => 'San Marino Corned Tuna 155g',
                'category_id' => 3,
                'supplier_id' => 3,
                'brand' => 'San Marino',
                'description' => 'San Marino corned tuna spicy',
                'cost_price' => 30.00,
                'selling_price' => 45.00,
                'unit' => 'can',
                'reorder_level' => 30,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 60,
                'image' => 'uploads/products/1773511380_69b5a2d439c99.jpg'
            ],
            [
                'product_code' => 'PRD008',
                'barcode' => '4801234567897',
                'product_name' => 'Argentina Corned Beef 150g',
                'category_id' => 3,
                'supplier_id' => 3,
                'brand' => 'Argentina',
                'description' => 'Argentina corned beef',
                'cost_price' => 35.00,
                'selling_price' => 52.00,
                'unit' => 'can',
                'reorder_level' => 30,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 55,
                'image' => 'uploads/products/1773511505_69b5a35108738.jpg'
            ],
            
            // Noodles (Category 4)
            [
                'product_code' => 'PRD009',
                'barcode' => '4801234567898',
                'product_name' => 'Lucky Me! Beef 55g',
                'category_id' => 4,
                'supplier_id' => 2,
                'brand' => 'Lucky Me',
                'description' => 'Lucky Me! instant noodles beef flavor',
                'cost_price' => 8.00,
                'selling_price' => 12.00,
                'unit' => 'cup',
                'reorder_level' => 50,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 200,
                'image' => 'uploads/products/1773511616_69b5a3c0e0690.jpg'
            ],
            [
                'product_code' => 'PRD010',
                'barcode' => '4801234567899',
                'product_name' => 'Lucky Me! Chicken 55g',
                'category_id' => 4,
                'supplier_id' => 2,
                'brand' => 'Lucky Me',
                'description' => 'Lucky Me! instant noodles chicken flavor',
                'cost_price' => 8.00,
                'selling_price' => 12.00,
                'unit' => 'cup',
                'reorder_level' => 50,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 195,
                'image' => 'uploads/products/1773816185_69ba4979a76a7.jpg'
            ],
            // Nestle Philippines (Supplier 4)
            [
                'product_code' => 'PRD011',
                'barcode' => '4801234567900',
                'product_name' => 'Nescafe Classic 100g',
                'category_id' => 1,
                'supplier_id' => 4,
                'brand' => 'Nescafe',
                'description' => 'Nescafe classic instant coffee',
                'cost_price' => 85.00,
                'selling_price' => 110.00,
                'unit' => 'jar',
                'reorder_level' => 15,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 40
            ],
            [
                'product_code' => 'PRD012',
                'barcode' => '4801234567901',
                'product_name' => 'Bear Brand Powdered Milk 320g',
                'category_id' => 1,
                'supplier_id' => 4,
                'brand' => 'Bear Brand',
                'description' => 'Bear Brand powdered milk drink',
                'cost_price' => 120.00,
                'selling_price' => 155.00,
                'unit' => 'pouch',
                'reorder_level' => 20,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 35
            ],
            [
                'product_code' => 'PRD013',
                'barcode' => '4801234567902',
                'product_name' => 'Maggi Magic Sarap 8g (Pack of 12)',
                'category_id' => 3,
                'supplier_id' => 4,
                'brand' => 'Maggi',
                'description' => 'Maggi all-in-one seasoning',
                'cost_price' => 42.00,
                'selling_price' => 55.00,
                'unit' => 'pack',
                'reorder_level' => 25,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 80
            ],
            // Pepsi-Cola Products (Supplier 5)
            [
                'product_code' => 'PRD014',
                'barcode' => '4801234567903',
                'product_name' => 'Pepsi 1.5L',
                'category_id' => 1,
                'supplier_id' => 5,
                'brand' => 'Pepsi',
                'description' => 'Pepsi soft drink 1.5L bottle',
                'cost_price' => 42.00,
                'selling_price' => 62.00,
                'unit' => 'bottle',
                'reorder_level' => 10,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 50
            ],
            [
                'product_code' => 'PRD015',
                'barcode' => '4801234567904',
                'product_name' => 'Mountain Dew 1.5L',
                'category_id' => 1,
                'supplier_id' => 5,
                'brand' => 'Mountain Dew',
                'description' => 'Mountain Dew soft drink 1.5L bottle',
                'cost_price' => 42.00,
                'selling_price' => 62.00,
                'unit' => 'bottle',
                'reorder_level' => 10,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 45
            ],
            // Household (Category 5)
            [
                'product_code' => 'PRD016',
                'barcode' => '4801234567905',
                'product_name' => 'Joy Dishwashing Liquid 250ml',
                'category_id' => 5,
                'supplier_id' => 3, // Assigned to SMC for sample
                'brand' => 'Joy',
                'description' => 'Joy lemon dishwashing liquid',
                'cost_price' => 35.00,
                'selling_price' => 48.00,
                'unit' => 'bottle',
                'reorder_level' => 15,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 30
            ],
            [
                'product_code' => 'PRD017',
                'barcode' => '4801234567906',
                'product_name' => 'Surf Powder Detergent 2.2kg',
                'category_id' => 5,
                'supplier_id' => 3,
                'brand' => 'Surf',
                'description' => 'Surf detergent powder blossom fresh',
                'cost_price' => 185.00,
                'selling_price' => 225.00,
                'unit' => 'pouch',
                'reorder_level' => 10,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 20
            ],
            // Personal Care (Category 6)
            [
                'product_code' => 'PRD018',
                'barcode' => '4801234567907',
                'product_name' => 'Pantry’s Shampoo 180ml',
                'category_id' => 6,
                'supplier_id' => 2, // URC
                'brand' => 'Pantene',
                'description' => 'Pantene hair fall control shampoo',
                'cost_price' => 95.00,
                'selling_price' => 125.00,
                'unit' => 'bottle',
                'reorder_level' => 12,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 25
            ],
            [
                'product_code' => 'PRD019',
                'barcode' => '4801234567908',
                'product_name' => 'Safeguard White Soap 130g',
                'category_id' => 6,
                'supplier_id' => 2,
                'brand' => 'Safeguard',
                'description' => 'Safeguard bar soap pure white',
                'cost_price' => 32.00,
                'selling_price' => 45.00,
                'unit' => 'bar',
                'reorder_level' => 30,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 60
            ],
            // Frozen Goods (Category 7)
            [
                'product_code' => 'PRD020',
                'barcode' => '4801234567909',
                'product_name' => 'Purefoods Tender Juicy Hotdog 1kg',
                'category_id' => 7,
                'supplier_id' => 6,
                'brand' => 'Purefoods',
                'description' => 'Classic tender juicy hotdog',
                'cost_price' => 165.00,
                'selling_price' => 195.00,
                'unit' => 'pack',
                'reorder_level' => 10,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 5 // Low Stock Alert
            ],
            [
                'product_code' => 'PRD021',
                'barcode' => '4801234567910',
                'product_name' => 'CDO Skinless Longganisa 250g',
                'category_id' => 7,
                'supplier_id' => 7,
                'brand' => 'CDO',
                'description' => 'Filipino style skinless longganisa',
                'cost_price' => 45.00,
                'selling_price' => 65.00,
                'unit' => 'pack',
                'reorder_level' => 15,
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 0 // Out of Stock Alert
            ],
            // Household (Category 5) - Additional Products
            [
                'product_code' => 'PRD022',
                'barcode' => '4801234567911',
                'product_name' => 'Breeze Power Machine Liquid 1L',
                'category_id' => 5,
                'supplier_id' => 8,
                'brand' => 'Breeze',
                'description' => 'Liquid detergent for washing machines',
                'cost_price' => 145.00,
                'selling_price' => 185.00,
                'unit' => 'bottle',
                'reorder_level' => 10,
                'has_expiry' => true,
                'expiry_date' => \Carbon\Carbon::now()->subDays(5)->format('Y-m-d'), // Expired Alert
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 20
            ],
            [
                'product_code' => 'PRD023',
                'barcode' => '4801234567912',
                'product_name' => 'Ariel Sunrise Fresh Powder 1.4kg',
                'category_id' => 5,
                'supplier_id' => 9,
                'brand' => 'Ariel',
                'description' => 'Detergent powder sunrise fresh',
                'cost_price' => 165.00,
                'selling_price' => 210.00,
                'unit' => 'pouch',
                'reorder_level' => 10,
                'has_expiry' => true,
                'expiry_date' => \Carbon\Carbon::now()->addDays(5)->format('Y-m-d'), // Near Expiry Alert
                'status' => 'active',
                'created_by' => 1,
                'stock_quantity' => 18
            ],
        ];

        foreach ($products as $productData) {
            $stockQty = $productData['stock_quantity'];
            unset($productData['stock_quantity']);
            
            $product = Product::updateOrCreate(
                ['product_code' => $productData['product_code']],
                $productData
            );
            
            Stock::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'quantity' => $stockQty,
                    'min_quantity' => $productData['reorder_level'],
                    'max_quantity' => $stockQty * 2,
                    'location' => 'Aisle ' . rand(1, 5)
                ]
            );
        }
    }
}