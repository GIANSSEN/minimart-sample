<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Brand;
use App\Models\UnitOfMeasurement;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of products with search and filters.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier', 'stock']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->search($search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Filter by supplier
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->input('supplier'));
        }

        // Filter by brand (string field)
        if ($request->filled('brand')) {
            $query->where('brand', 'LIKE', '%' . $request->input('brand') . '%');
        }



        // Filter by stock status (using model scopes)
        if ($request->filled('stock_status')) {
            switch ($request->input('stock_status')) {
                case 'in_stock':
                    $query->inStock();
                    break;
                case 'low_stock':
                    $query->lowStock();
                    break;
                case 'out_of_stock':
                    $query->outOfStock();
                    break;
            }
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = $request->input('per_page', 15);
        $products = $query->paginate($perPage)->withQueryString();
        
        // Get filter data
        $categories = Category::all();
        $suppliers = Supplier::all();
        $brands = Brand::all(); // For brand filter dropdown
        $statuses = [];
        $stockStatuses = [
            'in_stock' => 'In Stock',
            'low_stock' => 'Low Stock',
            'out_of_stock' => 'Out of Stock'
        ];

        // Get inventory alerts for stats
        $alerts = Product::getInventoryAlerts();

        if ($request->ajax()) {
            return view('admin.products.partials.table', compact('products'));
        }

        return view('admin.products.index', compact(
            'products', 
            'categories', 
            'suppliers', 
            'brands',
            'statuses', 
            'stockStatuses',
            'alerts'
        ));
    }

    /**
     * Show form for creating new product.
     */
    public function create()
    {
        $categories = Category::orderBy('category_name')->get();
        $suppliers = Supplier::orderBy('supplier_name')->get();
        $brands = Brand::orderBy('brand_name')->get();
        $uoms = UnitOfMeasurement::orderBy('name')->get();
        
        /** @var \App\Models\Product|null $lastProduct */
        $lastProduct = Product::withTrashed()->latest('id')->first();
        $lastId = $lastProduct ? $lastProduct->id : 0;
        $productCode = 'PRD-' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
        
        return view('admin.products.create', compact('categories', 'suppliers', 'brands', 'uoms', 'productCode'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_code' => 'required|string|unique:products',
            'barcode' => 'nullable|string|unique:products',
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'brand_id' => 'nullable|exists:brands,id',
            'brand' => 'nullable|string|max:100',
            'unit' => 'required|string|max:50',
            'uom_id' => 'nullable|exists:unit_of_measurements,id',
            'product_type' => 'required|in:perishable,non_perishable,equipment',
            'has_expiry' => 'sometimes|boolean',
            'manufacturing_date' => 'nullable|required_if:has_expiry,true|date',
            'expiry_date' => 'nullable|required_if:has_expiry,true|date|after:manufacturing_date',
            'shelf_life_days' => 'nullable|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'reorder_level' => 'required|integer|min:0',
            'reorder_quantity' => 'nullable|integer|min:0',
            'max_level' => 'nullable|integer|min:0',
            'min_level' => 'nullable|integer|min:0',
            'shelf_location' => 'nullable|string|max:50',
            'image' => 'nullable|file|mimetypes:image/jpeg,image/jpg,image/pjpeg,image/pjp,image/png,image/x-png,image/gif,image/webp,image/bmp,image/x-ms-bmp,image/svg+xml,image/heic,image/heif,image/avif,image/jfif,image/tiff',
            'stock_quantity' => 'required|integer|min:0',
            'is_phase_out' => 'sometimes|boolean',
            'phase_out_reason' => 'required_if:is_phase_out,true|string|nullable',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Ensure directory exists
                if (!file_exists(public_path('uploads/products'))) {
                    mkdir(public_path('uploads/products'), 0755, true);
                }
                
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $imageName);
                $imagePath = 'uploads/products/' . $imageName;
            }

            // Determine brand name - either from select or manual input
            $brandName = $request->brand;
            if ($request->filled('brand_id')) {
                $brand = Brand::find($request->input('brand_id'));
                $brandName = $brand ? $brand->brand_name : $request->brand;
            }

            /** @var \App\Models\Product $product */
            $product = Product::create([
                'product_code' => $request->product_code,
                'barcode' => $request->barcode,
                'product_name' => $request->product_name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'brand_id' => $request->brand_id,
                'brand' => $brandName,
                'unit' => $request->unit,
                'uom_id' => $request->uom_id,
                'product_type' => $request->product_type,
                'has_expiry' => $request->has('has_expiry') ? true : false,
                'manufacturing_date' => $request->manufacturing_date,
                'expiry_date' => $request->expiry_date,
                'shelf_life_days' => $request->shelf_life_days,
                'cost_price' => $request->cost_price,
                'selling_price' => $request->selling_price,
                'wholesale_price' => $request->wholesale_price,
                'discount_percent' => $request->discount_percent ?? 0,
                'tax_rate' => $request->tax_rate ?? 0,
                'reorder_level' => $request->reorder_level,
                'reorder_quantity' => $request->reorder_quantity ?? $request->reorder_level,
                'max_level' => $request->max_level,
                'min_level' => $request->min_level,
                'shelf_location' => $request->shelf_location,
                'image' => $imagePath,
                'status' => 'active',
                'is_phase_out' => $request->has('is_phase_out') ? true : false,
                'phase_out_reason' => $request->phase_out_reason,
                'created_by' => Auth::id()
            ]);

            // Stock is automatically created by the model's booted method
            // But we need to update the initial quantity
            if ($product->stock) {
                $product->stock->update(['quantity' => $request->stock_quantity]);
            }

            // Create initial stock transaction
            if ($request->stock_quantity > 0 && $product->stock) {
                StockTransaction::create([
                    'stock_id' => $product->stock->id,
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity' => $request->stock_quantity,
                    'previous_quantity' => 0,
                    'new_quantity' => $request->stock_quantity,
                    'reason' => 'initial_stock',
                    'notes' => 'Product creation'
                ]);
            }

            // Update inventory status
            $product->updateInventoryStatus();

            // Enhanced activity log
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'create_product',
                'action_type' => 'CREATE',
                'model_type' => 'Product',
                'model_id' => $product->id,
                'description' => "Created product: {$product->product_name}",
                'new_values' => json_encode($product->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating product: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'supplier', 'stock', 'creator']);

        // Return JSON for AJAX (modal) requests
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(array_merge(
                $product->toArray(),
                [
                    'current_stock'       => $product->current_stock,
                    'stock_status'        => $product->stock_status,
                    'stock_status_label'  => $product->stock_status_label,
                    'stock_status_color'  => $product->stock_status_color,
                    'stock_badge_class'   => $product->stock_badge_class,
                    'expiry_status'       => $product->expiry_status,
                ]
            ));
        }

        // Get stock transactions for full page view
        $transactions = StockTransaction::with('user')
            ->where('product_id', $product->id)
            ->latest()
            ->paginate(20);
        
        return view('admin.products.show', compact('product', 'transactions'));
    }

    /**
     * Show form for editing product.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('category_name')->get();
        $suppliers = Supplier::orderBy('supplier_name')->get();
        $brands = Brand::orderBy('brand_name')->get();
        $uoms = UnitOfMeasurement::orderBy('name')->get();
        $stock = $product->stock;
        
        return view('admin.products.edit', compact('product', 'categories', 'suppliers', 'brands', 'uoms', 'stock'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'product_code' => 'required|string|unique:products,product_code,' . $product->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'brand_id' => 'nullable|exists:brands,id',
            'brand' => 'nullable|string|max:100',
            'unit' => 'required|string|max:50',
            'uom_id' => 'nullable|exists:unit_of_measurements,id',
            'product_type' => 'required|in:perishable,non_perishable,equipment',
            'has_expiry' => 'sometimes|boolean',
            'manufacturing_date' => 'nullable|required_if:has_expiry,true|date',
            'expiry_date' => 'nullable|required_if:has_expiry,true|date|after:manufacturing_date',
            'shelf_life_days' => 'nullable|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'reorder_level' => 'required|integer|min:0',
            'reorder_quantity' => 'nullable|integer|min:0',
            'max_level' => 'nullable|integer|min:0',
            'min_level' => 'nullable|integer|min:0',
            'shelf_location' => 'nullable|string|max:50',
            'image' => 'nullable|file|mimetypes:image/jpeg,image/jpg,image/pjpeg,image/pjp,image/png,image/x-png,image/gif,image/webp,image/bmp,image/x-ms-bmp,image/svg+xml,image/heic,image/heif,image/avif,image/jfif,image/tiff',
            'is_phase_out' => 'sometimes|boolean',
            'phase_out_reason' => 'required_if:is_phase_out,true|string|nullable',
            'remove_image' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $oldData = $product->toArray();

            // Handle image upload
            $imagePath = $product->image;
            
            // Check if remove image is checked
            if ($request->has('remove_image') && $request->remove_image) {
                // Delete old image file
                if ($product->image && file_exists(public_path($product->image))) {
                    unlink(public_path($product->image));
                }
                $imagePath = null;
            }
            
            // Upload new image if provided
            if ($request->hasFile('image')) {
                // Delete old image
                if ($product->image && file_exists(public_path($product->image)) && !$request->remove_image) {
                    unlink(public_path($product->image));
                }
                
                // Ensure directory exists
                if (!file_exists(public_path('uploads/products'))) {
                    mkdir(public_path('uploads/products'), 0755, true);
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $imageName);
                $imagePath = 'uploads/products/' . $imageName;
            }

            // Determine brand name
            $brandName = $request->brand;
            if ($request->filled('brand_id')) {
                $brand = Brand::find($request->input('brand_id'));
                $brandName = $brand ? $brand->brand_name : $request->brand;
            }

            $product->update([
                'product_code' => $request->product_code,
                'barcode' => $request->barcode,
                'product_name' => $request->product_name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'brand_id' => $request->brand_id,
                'brand' => $brandName,
                'unit' => $request->unit,
                'uom_id' => $request->uom_id,
                'product_type' => $request->product_type,
                'has_expiry' => $request->has('has_expiry') ? true : false,
                'manufacturing_date' => $request->manufacturing_date,
                'expiry_date' => $request->expiry_date,
                'shelf_life_days' => $request->shelf_life_days,
                'cost_price' => $request->cost_price,
                'selling_price' => $request->selling_price,
                'wholesale_price' => $request->wholesale_price,
                'discount_percent' => $request->discount_percent ?? 0,
                'tax_rate' => $request->tax_rate ?? 0,
                'reorder_level' => $request->reorder_level,
                'reorder_quantity' => $request->reorder_quantity ?? $request->reorder_level,
                'max_level' => $request->max_level,
                'min_level' => $request->min_level,
                'shelf_location' => $request->shelf_location,
                'image' => $imagePath,
                'status' => 'active',
                'is_phase_out' => $request->has('is_phase_out') ? true : false,
                'phase_out_reason' => $request->phase_out_reason,
            ]);

            // Stock is automatically updated by the model's booted method when reorder_level or max_level changes
            // Update inventory status
            $product->updateInventoryStatus();

            // Enhanced activity log
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update_product',
                'action_type' => 'UPDATE',
                'model_type' => 'Product',
                'model_id' => $product->id,
                'description' => "Updated product: {$product->product_name}",
                'old_values' => json_encode($oldData),
                'new_values' => json_encode($product->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            // Return JSON for AJAX (modal) requests
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully.',
                ]);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating product: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error updating product: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Check if product has transactions
            if ($product->stockTransactions()->count() > 0) {
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete product with transaction history.'
                    ], 422);
                }
                return back()->with('error', 'Cannot delete product with transaction history.');
            }

            $productName = $product->product_name;
            $productData = $product->toArray();
            
            // Delete image if exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            
            // Delete stock and transactions
            if ($product->stock) {
                $product->stock->transactions()->delete();
                $product->stock->delete();
            }
            
            $product->delete();

            // Enhanced activity log
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete_product',
                'action_type' => 'DELETE',
                'model_type' => 'Product',
                'model_id' => $product->id,
                'description' => "Deleted product: {$productName}",
                'old_values' => json_encode($productData),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            DB::commit();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted successfully.'
                ]);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting product: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete products.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        try {
            DB::beginTransaction();

            $count = 0;
            foreach ($request->input('ids') as $id) {
                $product = Product::find($id);
                if ($product) {
                    // Check if product has transactions
                    if ($product->stockTransactions()->count() > 0) {
                        continue; // Skip products with transactions
                    }
                    
                    // Delete image
                    if ($product->image && file_exists(public_path($product->image))) {
                        unlink(public_path($product->image));
                    }
                    
                    // Delete stock and transactions
                    if ($product->stock) {
                        $product->stock->transactions()->delete();
                        $product->stock->delete();
                    }
                    
                    $product->delete();
                    $count++;
                }
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'bulk_delete_products',
                'action_type' => 'DELETE',
                'description' => "Bulk deleted {$count} products",
                'ip_address' => $request->ip()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$count} products deleted successfully."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export products to CSV.
     */
    public function export($format = 'csv')
    {
        $products = Product::with(['category', 'supplier', 'stock'])->get();
        
        $filename = 'products-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Product Code',
                'Barcode',
                'Product Name',
                'Category',
                'Supplier',
                'Brand',
                'Unit',
                'Cost Price',
                'Selling Price',
                'Stock',
                'Reorder Level'
            ]);

            // Data
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->product_code,
                    $product->barcode,
                    $product->product_name,
                    $product->category->category_name ?? 'N/A',
                    $product->supplier->supplier_name ?? 'N/A',
                    $product->brand ?? 'N/A',
                    $product->unit,
                    $product->cost_price,
                    $product->selling_price,
                    $product->stock->quantity ?? 0,
                    $product->reorder_level
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get product by barcode (for POS)
     */
    public function getByBarcode($barcode)
    {
        /** @var \App\Models\Product|null $product */
        $product = Product::with('stock')
            ->where('barcode', $barcode)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $stockQty = $product->stock->quantity ?? 0;

        return response()->json([
            'success' => true,
            'id' => $product->id,
            'product_code' => $product->product_code,
            'barcode' => $product->barcode,
            'product_name' => $product->product_name,
            'price' => $product->selling_price,
            'stock' => $stockQty,
            'unit' => $product->unit,
            'in_stock' => $stockQty > 0
        ]);
    }

    /**
     * Duplicate product
     */
    public function duplicate(Product $product)
    {
        try {
            DB::beginTransaction();

            $newProduct = $product->replicate();
            $newProduct->product_code = 'PRD-' . str_pad(Product::withTrashed()->count() + 1, 6, '0', STR_PAD_LEFT);
            $newProduct->created_at = now();
            $newProduct->updated_at = now();
            $newProduct->created_by = Auth::id();
            
            // Handle image copy
            if ($product->image && file_exists(public_path($product->image))) {
                $imageInfo = pathinfo($product->image);
                $newImageName = time() . '_' . uniqid() . '.' . $imageInfo['extension'];
                copy(public_path($product->image), public_path('uploads/products/' . $newImageName));
                $newProduct->image = 'uploads/products/' . $newImageName;
            }
            
            $newProduct->save();

            // Duplicate stock
            if ($product->stock) {
                Stock::create([
                    'product_id' => $newProduct->id,
                    'quantity' => 0, // Start with 0 stock for duplicate
                    'min_quantity' => $product->stock->min_quantity,
                    'max_quantity' => $product->stock->max_quantity,
                    'location' => $product->stock->location
                ]);
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'duplicate_product',
                'action_type' => 'CREATE',
                'model_type' => 'Product',
                'model_id' => $newProduct->id,
                'description' => "Duplicated product from {$product->product_name} to {$newProduct->product_name}",
                'ip_address' => request()->ip()
            ]);

            DB::commit();

            return redirect()->route('admin.products.edit', $newProduct->id)
                ->with('success', 'Product duplicated successfully. You can now edit the new product.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error duplicating product: ' . $e->getMessage());
        }
    }



    /**
     * Display low stock products.
     */
    public function lowStock()
    {
        $products = Product::with(['category', 'stock'])
            ->lowStock()
            ->paginate(20);

        return view('admin.products.low-stock', compact('products'));
    }

    /**
     * Display expiry monitoring.
     */
    public function expiryMonitoring(Request $request)
    {
        $query = Product::with(['category', 'stock'])
            ->where('has_expiry', true);

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status == 'expired') {
                $query->expired();
            } elseif ($status == 'near') {
                $query->nearExpiry(30);
            }
        }

        $products = $query->orderBy('expiry_date')->paginate(20);
        
        $stats = [
            'expired' => Product::expired()->count(),
            'near' => Product::nearExpiry(30)->count(),
            'good' => Product::where('has_expiry', true)
                ->where('expiry_date', '>', Carbon::today()->addDays(30))
                ->count()
        ];

        return view('admin.products.expiry-monitoring', compact('products', 'stats'));
    }

    /**
     * Export expiry report.
     */
    public function exportExpiryReport(Request $request)
    {
        $format = $request->input('format', 'csv');
        
        $query = Product::with(['category', 'stock'])
            ->where('has_expiry', true);

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status == 'expired') {
                $query->expired();
            } elseif ($status == 'near') {
                $query->nearExpiry(30);
            }
        }

        $products = $query->orderBy('expiry_date')->get();

        if ($format === 'csv') {
            $filename = 'expiry-report-' . now()->format('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function () use ($products) {
                $file = fopen('php://output', 'w');
                
                fputcsv($file, [
                    'Product Code',
                    'Product Name',
                    'Category',
                    'Expiry Date',
                    'Days Left',
                    'Stock',
                    'Status'
                ]);

                foreach ($products as $product) {
                    $daysLeft = Carbon::today()->diffInDays(Carbon::parse($product->expiry_date), false);
                    $status = $daysLeft < 0 ? 'Expired' : ($daysLeft <= 30 ? 'Near Expiry' : 'Good');
                    
                    fputcsv($file, [
                        $product->product_code,
                        $product->product_name,
                        $product->category->category_name ?? 'N/A',
                        $product->expiry_date->format('Y-m-d'),
                        $daysLeft,
                        $product->stock->quantity ?? 0,
                        $status
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Unsupported export format.');
    }

    /**
     * Bulk update expiry dates.
     */
    public function bulkUpdateExpiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'expiry_date' => 'required|date|after:today'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $expiryDate = Carbon::parse($request->input('expiry_date'));
            $productIds = $request->input('product_ids');
            
            Product::whereIn('id', $productIds)
                ->update([
                    'has_expiry' => true,
                    'expiry_date' => $expiryDate
                ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'bulk_update_expiry',
                'action_type' => 'UPDATE',
                'description' => "Bulk updated expiry dates for " . count($productIds) . " products",
                'ip_address' => $request->ip()
            ]);

            return redirect()->back()->with('success', 'Expiry dates updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating expiry dates: ' . $e->getMessage());
        }
    }

    /**
     * Mark product as expired.
     */
    public function markAsExpired(Request $request, Product $product)
    {
        $product->update([
            'status' => 'inactive',
            'is_phase_out' => true,
            'phase_out_reason' => 'Expired product'
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'mark_expired',
            'action_type' => 'UPDATE',
            'model_type' => 'Product',
            'model_id' => $product->id,
            'description' => "Marked product as expired: {$product->product_name}",
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product marked as expired.'
        ]);
    }

    /**
     * Extend product expiry.
     */
    public function extendExpiry(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'new_expiry_date' => 'required|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update([
            'expiry_date' => Carbon::parse($request->input('new_expiry_date')),
            'status' => 'active',
            'is_phase_out' => false
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'extend_expiry',
            'action_type' => 'UPDATE',
            'model_type' => 'Product',
            'model_id' => $product->id,
            'description' => "Extended expiry date for: {$product->product_name}",
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Expiry date extended successfully.'
        ]);
    }

    /**
     * Adjust stock
     */
    public function adjustStock(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
            'type' => 'required|in:add,subtract,set',
            'reason' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $stock = $product->stock;
            
            if (!$stock) {
                $stock = Stock::create([
                    'product_id' => $product->id,
                    'quantity' => 0,
                    'min_quantity' => $product->reorder_level ?? 10
                ]);
            }

            $oldQuantity = $stock->quantity;
            
            if ($request->type === 'add') {
                $newQuantity = $oldQuantity + $request->quantity;
                $type = 'in';
            } elseif ($request->type === 'subtract') {
                if ($oldQuantity < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock. Available: ' . $oldQuantity
                    ], 400);
                }
                $newQuantity = $oldQuantity - $request->quantity;
                $type = 'out';
            } else { // set
                $newQuantity = $request->quantity;
                $type = $newQuantity > $oldQuantity ? 'in' : 'out';
            }

            $change = abs($newQuantity - $oldQuantity);

            if ($change > 0) {
                StockTransaction::create([
                    'stock_id' => $stock->id,
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'quantity' => $change,
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'reason' => 'adjustment',
                    'notes' => $request->reason
                ]);
            }

            $stock->update(['quantity' => $newQuantity]);
            $product->updateInventoryStatus();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'adjust_stock',
                'action_type' => 'UPDATE',
                'model_type' => 'Product',
                'model_id' => $product->id,
                'description' => "Adjusted stock for {$product->product_name}: {$oldQuantity} → {$newQuantity}",
                'ip_address' => $request->ip()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock adjusted successfully.',
                'new_quantity' => $newQuantity
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error adjusting stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get inventory alerts summary (for dashboard)
     */
    public function getAlerts()
    {
        $alerts = Product::getInventoryAlerts();
        
        return response()->json([
            'success' => true,
            'data' => $alerts
        ]);
    }
}
