<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\InventoryLog;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class InventoryController extends Controller
{
    /**
     * Display inventory management page
     */
    public function index()
    {
        // Get products with their stock information
        $products = Product::with(['category', 'supplier', 'stock'])
            ->orderBy('product_name')
            ->paginate(15);
        
        // Calculate statistics using the stocks table    
        $stats = [
            'total_products' => Product::count(),
            'total_stock' => Stock::sum('quantity') ?? 0,
            'low_stock' => Stock::whereRaw('quantity <= min_quantity')->where('quantity', '>', 0)->count(),
            'out_of_stock' => Stock::where('quantity', '<=', 0)->count(),
            'expired' => Product::where('has_expiry', true)
                ->where('expiry_date', '<', Carbon::today())
                ->count(),
            'near_expiry' => Product::where('has_expiry', true)
                ->where('expiry_date', '>', Carbon::today())
                ->where('expiry_date', '<=', Carbon::today()->addDays(30))
                ->count(),
            'total_value' => Stock::join('products', 'stocks.product_id', '=', 'products.id')
                ->select(DB::raw('SUM(stocks.quantity * products.selling_price) as total'))
                ->value('total') ?? 0
        ];
        
        $recentTransactions = StockTransaction::with(['product', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $lowStockProducts = Product::with('stock')
            ->whereHas('stock', function ($q) {
                $q->whereRaw('quantity <= min_quantity');
            })
            ->take(10)
            ->get();
        
        return view('Admin.Inventory.index', compact('products', 'stats', 'recentTransactions', 'lowStockProducts'));
    }

    /**
     * Display stock in page with transactions
     */
    public function stockIn(Request $request)
    {
        $query = StockTransaction::with('product', 'user')
            ->where('type', 'in');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
        }

        $transactions = $query->latest()->paginate(20);
        $products = Product::where('status', 'active')->orderBy('product_name')->get();
        $suppliers = Supplier::orderBy('supplier_name')->get();

        return view('Admin.Inventory.stock-in', compact('transactions', 'products', 'suppliers'));
    }

    /**
     * Display stock out page with transactions
     */
    public function stockOut(Request $request)
    {
        $query = StockTransaction::with('product', 'user')
            ->where('type', 'out');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
        }

        $transactions = $query->latest()->paginate(20);
        $products = Product::where('status', 'active')
            ->whereHas('stock', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->orderBy('product_name')
            ->get();

        return view('Admin.Inventory.stock-out', compact('transactions', 'products'));
    }

    /**
     * Process stock in transaction (AJAX)
     */
    public function processStockIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0',
            'received_date' => 'required|date',
            'received_by' => 'required|string|max:255',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'reason' => 'required|in:purchase,return,adjustment',
            'location' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->input('product_id'));
            
            // Get or create stock
            /** @var \App\Models\Stock $stock */
            $stock = Stock::firstOrCreate(
                ['product_id' => $request->input('product_id')],
                [
                    'quantity' => 0,
                    'min_quantity' => $product->reorder_level ?? 10,
                    'max_quantity' => $product->max_level ?? 1000,
                    'location' => $product->shelf_location ?? 'A1'
                ]
            );

            $oldQuantity = $stock->quantity;
            $newQuantity = $oldQuantity + $request->input('quantity');

            // Create transaction
            StockTransaction::create([
                'stock_id' => $stock->id,
                'product_id' => $request->input('product_id'),
                'supplier_id' => $request->input('supplier_id'),
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity' => $request->input('quantity'),
                'unit_cost' => $request->input('unit_cost'),
                'total_cost' => $request->input('quantity') * $request->input('unit_cost'),
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'received_date' => $request->input('received_date'),
                'received_by' => $request->input('received_by'),
                'reference' => $request->input('reference'),
                'location' => $request->input('location'),
                'reason' => $request->input('reason'),
                'notes' => $request->input('notes')
            ]);

            // Update stock quantity
            $stock->quantity = $newQuantity;
            $stock->save();

            // Update product expiry if provided
            if ($request->filled('expiry_date')) {
                $product->has_expiry = true;
                $product->expiry_date = Carbon::parse($request->input('expiry_date'));
                $product->save();
            }

            DB::commit();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'stock_in',
                'description' => "Added {$request->input('quantity')} units to {$product->product_name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Stock in recorded successfully.',
                'new_quantity' => $newQuantity
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process stock out transaction (AJAX)
     */
    public function processStockOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|in:sale,damage,expired,adjustment,return,pullout,sample,staff_consumption,others',
            'released_by' => 'required|string|max:255',
            'authorized_by' => 'required|string|max:255',
            'date_out' => 'required|date',
            'time_out' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->input('product_id'));
            $stock = Stock::where('product_id', $request->input('product_id'))->first();

            if (!$stock || $stock->quantity < $request->input('quantity')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock available. Current stock: ' . ($stock->quantity ?? 0)
                ], 400);
            }

            $oldQuantity = $stock->quantity;
            $newQuantity = $oldQuantity - $request->input('quantity');

            // Create transaction
            StockTransaction::create([
                'stock_id' => $stock->id,
                'product_id' => $request->input('product_id'),
                'user_id' => Auth::id(),
                'type' => 'out',
                'quantity' => $request->input('quantity'),
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'reason' => $request->input('reason'),
                'released_by' => $request->input('released_by'),
                'authorized_by' => $request->input('authorized_by'),
                'received_date' => $request->input('date_out'),
                'transaction_time' => $request->input('time_out'),
                'unit_price' => $request->input('unit_price'),
                'total_value' => $request->input('quantity') * $request->input('unit_price'),
                'reference' => $request->input('reference'),
                'notes' => $request->input('notes')
            ]);

            // Update stock quantity
            $stock->quantity = $newQuantity;
            $stock->save();

            // Update product inventory status
            if (method_exists($product, 'updateInventoryStatus')) {
                $product->updateInventoryStatus();
            }

            DB::commit();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'stock_out',
                'description' => "Removed {$request->input('quantity')} units from {$product->product_name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Stock out recorded successfully.',
                'new_quantity' => $newQuantity
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show inventory alerts
     */
    public function alerts(Request $request)
    {
        $allowedTypes = ['all', 'low', 'out', 'expired', 'near'];
        $allowedPerPage = [15, 25, 50, 100];

        $type = $request->input('type', 'low');
        if (!in_array($type, $allowedTypes, true)) {
            $type = 'low';
        }

        $perPage = (int) $request->input('per_page', 15);
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 15;
        }

        $today = Carbon::today();
        $nearExpiryLimit = $today->copy()->addDays(30);

        $lowStock = Stock::with('product')
            ->whereHas('product')
            ->where('quantity', '>', 0)
            ->whereNotNull('min_quantity')
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->orderBy('quantity')
            ->orderBy('updated_at', 'asc')
            ->paginate($perPage, ['*'], 'low_page')
            ->withQueryString();

        $outOfStock = Stock::with('product')
            ->whereHas('product')
            ->where('quantity', '<=', 0)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage, ['*'], 'out_page')
            ->withQueryString();

        $expiredProducts = Product::with('stock')
            ->where('has_expiry', true)
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', $today)
            ->orderBy('expiry_date')
            ->paginate($perPage, ['*'], 'expired_page')
            ->withQueryString();

        $nearExpiry = Product::with('stock')
            ->where('has_expiry', true)
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', $today)
            ->whereDate('expiry_date', '<=', $nearExpiryLimit)
            ->orderBy('expiry_date')
            ->paginate($perPage, ['*'], 'near_page')
            ->withQueryString();

        $stats = [
            'low_stock' => $lowStock->total(),
            'out_of_stock' => $outOfStock->total(),
            'expired' => $expiredProducts->total(),
            'near_expiry' => $nearExpiry->total(),
        ];

        $suppliers = Supplier::orderBy('supplier_name')
            ->get(['id', 'supplier_name']);

        return view('Admin.Inventory.alerts', compact(
            'type',
            'perPage',
            'lowStock',
            'outOfStock',
            'expiredProducts',
            'nearExpiry',
            'stats',
            'suppliers'
        ));
    }

    /**
     * Quick restock from inventory alerts page.
     */
    public function restockFromAlert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:1000000',
            'unit_cost' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($request->input('product_id'));
            $quantity = (int) $request->input('quantity');
            $unitCost = $request->filled('unit_cost')
                ? (float) $request->input('unit_cost')
                : (float) ($product->cost_price ?? 0);

            /** @var \App\Models\Stock $stock */
            $stock = Stock::firstOrCreate(
                ['product_id' => $product->id],
                [
                    'quantity' => 0,
                    'min_quantity' => $product->reorder_level ?? 10,
                    'max_quantity' => $product->max_level ?? 1000,
                    'location' => $product->shelf_location ?? 'A1',
                ]
            );

            $oldQuantity = (int) $stock->quantity;
            $newQuantity = $oldQuantity + $quantity;
            $actorName = Auth::user() ? Auth::user()->name : 'System';

            StockTransaction::create([
                'stock_id' => $stock->id,
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'total_cost' => $quantity * $unitCost,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'received_date' => Carbon::today()->toDateString(),
                'received_by' => $actorName,
                'reference' => $request->input('reference'),
                'location' => $stock->location ?? $product->shelf_location,
                'reason' => 'restock',
                'notes' => $request->input('notes'),
            ]);

            $stock->quantity = $newQuantity;
            $stock->save();

            if (method_exists($product, 'updateInventoryStatus')) {
                $product->setRelation('stock', $stock);
                $product->updateInventoryStatus();
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'restock_alert_item',
                'description' => "Restocked {$quantity} units for {$product->product_name} via inventory alerts",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully.',
                'new_quantity' => $newQuantity,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error processing restock: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dispose one product from alerts page (stock out as expired).
     */
    public function disposeProductAlert(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $stock = Stock::where('product_id', $product->id)->first();
        if (!$stock || (int) $stock->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No available stock to dispose.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            $quantity = (int) $stock->quantity;
            $actorName = Auth::user() ? Auth::user()->name : 'System';

            StockTransaction::create([
                'stock_id' => $stock->id,
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'out',
                'quantity' => $quantity,
                'previous_quantity' => $quantity,
                'new_quantity' => 0,
                'reason' => 'expired',
                'released_by' => $actorName,
                'authorized_by' => $actorName,
                'received_date' => Carbon::today()->toDateString(),
                'transaction_time' => Carbon::now()->format('H:i:s'),
                'unit_price' => (float) ($product->selling_price ?? 0),
                'total_value' => $quantity * (float) ($product->selling_price ?? 0),
                'reference' => 'ALERT-DISP-' . now()->format('YmdHis') . '-' . $product->id,
                'notes' => $request->input('notes') ?: 'Disposed from inventory alerts.',
            ]);

            $stock->quantity = 0;
            $stock->save();

            if (method_exists($product, 'updateInventoryStatus')) {
                $product->setRelation('stock', $stock);
                $product->updateInventoryStatus();
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'dispose_alert_item',
                'description' => "Disposed {$quantity} units of {$product->product_name} via inventory alerts",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product disposed successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error disposing product: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dispose all expired products that still have stock.
     */
    public function bulkDisposeExpiredAlerts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $products = Product::with('stock')
            ->where('has_expiry', true)
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<', Carbon::today())
            ->whereHas('stock', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->get();

        if ($products->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No expired items with stock to dispose.',
                'disposed_count' => 0,
            ]);
        }

        DB::beginTransaction();

        try {
            $actorName = Auth::user() ? Auth::user()->name : 'System';
            $timestamp = now()->format('YmdHis');
            $disposedCount = 0;

            foreach ($products as $index => $product) {
                $stock = $product->stock;
                if (!$stock || (int) $stock->quantity <= 0) {
                    continue;
                }

                $quantity = (int) $stock->quantity;

                StockTransaction::create([
                    'stock_id' => $stock->id,
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => $quantity,
                    'previous_quantity' => $quantity,
                    'new_quantity' => 0,
                    'reason' => 'expired',
                    'released_by' => $actorName,
                    'authorized_by' => $actorName,
                    'received_date' => Carbon::today()->toDateString(),
                    'transaction_time' => Carbon::now()->format('H:i:s'),
                    'unit_price' => (float) ($product->selling_price ?? 0),
                    'total_value' => $quantity * (float) ($product->selling_price ?? 0),
                    'reference' => "ALERT-BULK-DISP-{$timestamp}-{$index}",
                    'notes' => $request->input('notes') ?: 'Bulk disposed from inventory alerts.',
                ]);

                $stock->quantity = 0;
                $stock->save();

                if (method_exists($product, 'updateInventoryStatus')) {
                    $product->setRelation('stock', $stock);
                    $product->updateInventoryStatus();
                }

                $disposedCount++;
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'bulk_dispose_expired_alerts',
                'description' => "Bulk disposed {$disposedCount} expired products from inventory alerts",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Disposed {$disposedCount} expired products successfully.",
                'disposed_count' => $disposedCount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error during bulk dispose: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark one near-expiry product for sale by applying discount.
     */
    public function promoteNearExpiryProduct(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $isNearExpiry = $product->has_expiry
            && !empty($product->expiry_date)
            && Carbon::parse($product->expiry_date)->between(Carbon::today(), Carbon::today()->copy()->addDays(30));

        if (!$isNearExpiry) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not currently in the near-expiry window.',
            ], 400);
        }

        $newDiscount = $request->filled('discount_percent')
            ? round((float) $request->input('discount_percent'), 2)
            : max((float) ($product->discount_percent ?? 0), 10.00);

        $product->discount_percent = $newDiscount;
        if ($product->status !== 'active') {
            $product->status = 'active';
        }
        $product->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'promote_near_expiry_product',
            'description' => "Marked {$product->product_name} for sale with {$newDiscount}% discount",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product marked for sale successfully.',
            'discount_percent' => $newDiscount,
        ]);
    }

    /**
     * Mark all near-expiry products for sale by applying discount.
     */
    public function bulkPromoteNearExpiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $newDiscount = $request->filled('discount_percent')
            ? round((float) $request->input('discount_percent'), 2)
            : 10.00;

        $today = Carbon::today();
        $limitDate = $today->copy()->addDays(30);

        $updated = Product::where('has_expiry', true)
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '>=', $today)
            ->whereDate('expiry_date', '<=', $limitDate)
            ->update([
                'discount_percent' => $newDiscount,
                'status' => 'active',
            ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'bulk_promote_near_expiry',
            'description' => "Marked {$updated} near-expiry products for sale with {$newDiscount}% discount",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Marked {$updated} near-expiry products for sale.",
            'updated_count' => $updated,
            'discount_percent' => $newDiscount,
        ]);
    }

    /**
     * Get inventory summary
     */
    public function summary(Request $request)
    {
        $query = Product::with('category', 'stock');

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->input('supplier'));
        }

        $products = $query->paginate(20);
        $categories = Category::all();
        $suppliers = Supplier::all();

        $byCategory = Product::select(
                'category_id', 
                DB::raw('COUNT(*) as count'), 
                DB::raw('SUM(stocks.quantity) as total_stock')
            )
            ->join('stocks', 'products.id', '=', 'stocks.product_id')
            ->with('category')
            ->groupBy('category_id')
            ->get();

        $bySupplier = Product::select(
                'supplier_id', 
                DB::raw('COUNT(*) as count'), 
                DB::raw('SUM(stocks.quantity) as total_stock')
            )
            ->join('stocks', 'products.id', '=', 'stocks.product_id')
            ->with('supplier')
            ->groupBy('supplier_id')
            ->get();

        $totalValue = Stock::join('products', 'stocks.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(stocks.quantity * products.selling_price) as total'))
            ->first()->total ?? 0;

        $totalCost = Stock::join('products', 'stocks.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(stocks.quantity * products.cost_price) as total'))
            ->first()->total ?? 0;

        $summary = [
            'by_category' => $byCategory,
            'by_supplier' => $bySupplier,
            'total_value' => $totalValue,
            'total_cost' => $totalCost,
            'total_stock' => Stock::sum('quantity') ?? 0,
            'total_products' => Product::count()
        ];

        return view('Admin.Inventory.summary', compact('products', 'categories', 'suppliers', 'summary'));
    }

    /**
     * Show inventory history for a specific product
     */
    public function history($id)
    {
        try {
            $product = Product::with('stock')->findOrFail($id);
            
            $transactions = StockTransaction::with('user')
                ->where('product_id', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
            
            return view('Admin.Inventory.history', compact('product', 'transactions'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.inventory.index')
                ->with('error', 'Error loading history: ' . $e->getMessage());
        }
    }

    /**
     * Show all stock transactions (global history)
     */
    public function allHistory(Request $request)
    {
        $query = StockTransaction::with(['product', 'user']);
        
        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);
        $products = Product::orderBy('product_name')->get();
        
        return view('Admin.Inventory.history', compact('transactions', 'products'));
    }

    /**
     * Get product stock info for AJAX
     */
    public function getProductStock($id)
    {
        try {
            $product = Product::with('stock')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'id' => $product->id,
                'product_name' => $product->product_name,
                'product_code' => $product->product_code,
                'current_stock' => $product->stock->quantity ?? 0,
                'min_quantity' => $product->stock->min_quantity ?? $product->reorder_level ?? 10,
                'max_quantity' => $product->stock->max_quantity ?? $product->max_level ?? 1000,
                'location' => $product->stock->location ?? $product->shelf_location ?? 'A1',
                'unit' => $product->unit,
                'selling_price' => $product->selling_price,
                'cost_price' => $product->cost_price,
                'expiry_date' => $product->expiry_date ? Carbon::parse($product->expiry_date)->format('Y-m-d') : null,
                'has_expiry' => $product->has_expiry
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Product not found'
            ], 404);
        }
    }

    /**
     * Process stock in/out via quick form (legacy method for backward compatibility)
     */
    public function process(Request $request)
    {
        if ($request->input('type') === 'in') {
            return $this->processStockIn($request);
        } else {
            return $this->processStockOut($request);
        }
    }

    /**
     * Export stock history
     */
    public function exportHistory(Request $request)
    {
        $format = $request->input('format', 'excel');
        
        $query = StockTransaction::with(['product', 'user']);
        
        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->input('date_from')));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->input('date_to')));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->get();
        
        if ($format === 'excel') {
            $filename = 'stock_history_' . now()->format('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];
            
            $callback = function () use ($transactions) {
                $file = fopen('php://output', 'w');
                
                fputcsv($file, [
                    'Date',
                    'Time',
                    'Product Code',
                    'Product Name',
                    'Type',
                    'Quantity',
                    'Previous Stock',
                    'New Stock',
                    'Reason',
                    'Reference',
                    'Notes',
                    'User'
                ]);
                
                foreach ($transactions as $t) {
                    fputcsv($file, [
                        $t->created_at ? $t->created_at->format('Y-m-d') : 'N/A',
                        $t->created_at ? $t->created_at->format('H:i:s') : 'N/A',
                        $t->product->product_code ?? 'N/A',
                        $t->product->product_name ?? 'N/A',
                        ucfirst($t->type ?? 'N/A'),
                        $t->quantity ?? 0,
                        $t->previous_quantity ?? 0,
                        $t->new_quantity ?? 0,
                        ucfirst(str_replace('_', ' ', $t->reason ?? 'unknown')),
                        $t->reference ?? 'N/A',
                        $t->notes ?? 'N/A',
                        $t->user->full_name ?? $t->user->name ?? 'System'
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }
        
        return back()->with('info', 'Export feature for PDF coming soon!');
    }

    /**
     * Adjust stock (bulk update)
     */
    public function adjust(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'new_quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->input('product_id'));
            $stock = Stock::where('product_id', $request->input('product_id'))->first();

            if (!$stock) {
                $stock = Stock::create([
                    'product_id' => $product->id,
                    'quantity' => 0,
                    'min_quantity' => $product->reorder_level ?? 10,
                    'max_quantity' => $product->max_level ?? 1000,
                    'location' => $product->shelf_location ?? 'A1'
                ]);
            }

            $oldQuantity = $stock->quantity;
            $newQuantity = $request->input('new_quantity');

            // Determine type based on quantity change
            $type = $newQuantity > $oldQuantity ? 'in' : 'out';
            $change = abs($newQuantity - $oldQuantity);

            if ($change > 0) {
                // Create transaction
                StockTransaction::create([
                    'stock_id' => $stock->id,
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'quantity' => $change,
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'reason' => 'adjustment',
                    'notes' => 'Manual adjustment: ' . ($request->input('notes') ?? $request->input('reason'))
                ]);
            }

            // Update stock
            $stock->quantity = $newQuantity;
            $stock->save();

            DB::commit();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'adjust_stock',
                'description' => "Adjusted stock for {$product->product_name} from {$oldQuantity} to {$newQuantity}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

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
     * Bulk update expiry dates
     */
    public function bulkUpdateExpiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'expiry_date' => 'required|date'
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
                'description' => "Bulk updated expiry dates for " . count($productIds) . " products",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->back()->with('success', 'Expiry dates updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating expiry dates: ' . $e->getMessage());
        }
    }

    // ==================== NEW METHODS FOR TRANSACTION DETAILS & VOID ====================

    /**
     * Get transaction details for the modal (AJAX)
     */
    public function getTransaction($id)
    {
        try {
            $transaction = StockTransaction::with(['product', 'user'])->find($id);

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'transaction' => [
                    'id' => $transaction->id,
                    'created_at' => $transaction->created_at->toDateTimeString(),
                    'product' => $transaction->product ? [
                        'product_name' => $transaction->product->product_name,
                        'product_code' => $transaction->product->product_code,
                    ] : null,
                    'quantity' => $transaction->quantity,
                    'unit_cost' => $transaction->unit_cost,
                    'reference' => $transaction->reference,
                    'user' => $transaction->user ? [
                        'name' => $transaction->user->name ?? $transaction->user->full_name,
                    ] : null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching transaction details.'
            ], 500);
        }
    }

    /**
     * Void a stock in/out transaction (only within 24 hours)
     */
    public function voidTransaction(Request $request, $id)
    {
        try {
            $transaction = StockTransaction::with('product.stock')->find($id);

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found.'
                ], 404);
            }

            // Check if transaction is older than 24 hours
            if ($transaction->created_at->diffInHours(now()) >= 24) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot void transactions older than 24 hours.'
                ], 403);
            }

            DB::beginTransaction();

            $product = $transaction->product;
            $stock = $product->stock;

            if (!$stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product stock record not found.'
                ], 404);
            }

            // Reverse the stock effect based on transaction type
            if ($transaction->type === 'in') {
                // Stock in: remove the quantity
                if ($stock->quantity < $transaction->quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot void: insufficient stock to reverse (current stock lower than transaction quantity).'
                    ], 400);
                }
                $stock->decrement('quantity', $transaction->quantity);
            } else {
                // Stock out: add back the quantity
                $stock->increment('quantity', $transaction->quantity);
            }

            // Delete the transaction
            $transaction->delete();

            // Log the void action
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'void_transaction',
                'description' => "Voided {$transaction->type} transaction #{$transaction->id} for {$product->product_name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction voided successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error voiding transaction: ' . $e->getMessage()
            ], 500);
        }
    }
}
