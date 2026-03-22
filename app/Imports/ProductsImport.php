<?php
// COMMENT OUT MUNA ANG BUONG FILE HANGGAT HINDI PA NA-IINSTALL ANG EXCEL
// namespace App\Imports;

// use App\Models\Product;
// use App\Models\Category;
// use App\Models\Supplier;
// use App\Models\Stock;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;

// class ProductsImport implements ToCollection, WithHeadingRow
// {
//     public function collection(Collection $rows)
//     {
//         DB::beginTransaction();

//         try {
//             foreach ($rows as $row) {
//                 // Skip empty rows
//                 if (empty($row['product_name']) || empty($row['category_name'])) {
//                     continue;
//                 }

//                 // Find or create category
//                 $category = Category::firstOrCreate(
//                     ['category_name' => trim($row['category_name'])],
//                     [
//                         'description' => 'Imported category',
//                         'status' => 'active',
//                         'created_by' => Auth::id()
//                     ]
//                 );

//                 // Find or create supplier
//                 $supplier = null;
//                 if (!empty($row['supplier_name'])) {
//                     $supplier = Supplier::firstOrCreate(
//                         ['supplier_name' => trim($row['supplier_name'])],
//                         [
//                             'supplier_code' => 'SUP-IMP' . rand(1000, 9999),
//                             'status' => 'active',
//                             'created_by' => Auth::id()
//                         ]
//                     );
//                 }

//                 // Check if product already exists
//                 $product = Product::where('product_code', $row['product_code'])
//                     ->orWhere('barcode', $row['barcode'] ?? '')
//                     ->first();

//                 if ($product) {
//                     // Update existing product
//                     $product->update([
//                         'product_name' => $row['product_name'],
//                         'description' => $row['description'] ?? $product->description,
//                         'category_id' => $category->id,
//                         'supplier_id' => $supplier->id ?? $product->supplier_id,
//                         'brand' => $row['brand'] ?? $product->brand,
//                         'unit' => $row['unit'] ?? $product->unit,
//                         'cost_price' => $row['cost_price'] ?? $product->cost_price,
//                         'selling_price' => $row['selling_price'] ?? $product->selling_price,
//                         'wholesale_price' => $row['wholesale_price'] ?? $product->wholesale_price,
//                         'tax_rate' => $row['tax_rate'] ?? $product->tax_rate,
//                         'reorder_level' => $row['reorder_level'] ?? $product->reorder_level,
//                         'status' => $row['status'] ?? $product->status,
//                     ]);
//                 } else {
//                     // Create new product
//                     $product = Product::create([
//                         'product_code' => $row['product_code'],
//                         'barcode' => $row['barcode'] ?? null,
//                         'product_name' => $row['product_name'],
//                         'description' => $row['description'] ?? null,
//                         'category_id' => $category->id,
//                         'supplier_id' => $supplier->id ?? null,
//                         'brand' => $row['brand'] ?? null,
//                         'unit' => $row['unit'] ?? 'pcs',
//                         'cost_price' => $row['cost_price'] ?? 0,
//                         'selling_price' => $row['selling_price'] ?? 0,
//                         'wholesale_price' => $row['wholesale_price'] ?? null,
//                         'tax_rate' => $row['tax_rate'] ?? 12,
//                         'reorder_level' => $row['reorder_level'] ?? 10,
//                         'status' => $row['status'] ?? 'active',
//                         'created_by' => Auth::id()
//                     ]);
//                 }

//                 // Update stock
//                 $stock = Stock::firstOrNew(['product_id' => $product->id]);
//                 $stock->quantity = ($stock->quantity ?? 0) + ($row['stock_quantity'] ?? 0);
//                 $stock->min_quantity = $row['reorder_level'] ?? $product->reorder_level;
//                 $stock->max_quantity = ($stock->quantity ?? 0) * 2;
//                 $stock->save();
//             }

//             DB::commit();
//             Log::info('Products imported successfully');

//         } catch (\Exception $e) {
//             DB::rollBack();
//             Log::error('Import failed: ' . $e->getMessage());
//             throw $e;
//         }
//     }

//     public function headingRow(): int
//     {
//         return 1;
//     }
// }