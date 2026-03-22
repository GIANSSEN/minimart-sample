<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\ActivityLog;
use App\Models\Product; // ✅ Import Product for total count
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereNested(function ($q) use ($search) {
                $q->where('supplier_name', 'like', "%{$search}%")
                  ->orWhere('supplier_code', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ✅ Eager load product count using withCount (avoids N+1 queries)
        $query->withCount('products');

        // Get paginated results
        $perPage = $request->input('per_page', 15);
        $suppliers = $query->latest()->paginate($perPage);

        // ✅ Calculate active suppliers count (for the stats card)
        $activeCount = Supplier::where('status', 'active')->count();

        // Get total products count for stats
        $totalProducts = Product::count(); // using imported Product model

        return view('Admin.Supplier.index', compact('suppliers', 'activeCount', 'totalProducts'));
    }

    /**
     * Show form for creating new supplier.
     */
    public function create()
    {
        return view('Admin.Supplier.create');
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_code' => 'required|string|max:50|unique:suppliers',
            'supplier_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $supplier = Supplier::create([
            'supplier_code' => $request->supplier_code,
            'supplier_name' => $request->supplier_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'notes' => $request->notes,
            'status' => 'active',
            'created_by' => Auth::id()
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create_supplier',
            'description' => "Created supplier: {$supplier->supplier_name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier created successfully.',
                'supplier' => $supplier
            ]);
        }

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('products.category', 'products.stock');
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($supplier);
        }

        $products = $supplier->products()->paginate(10);
        
        return view('Admin.Supplier.show', compact('supplier', 'products'));
    }

    /**
     * Show form for editing supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('Admin.Supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'supplier_code' => 'required|string|max:50|unique:suppliers,supplier_code,' . $supplier->id,
            'supplier_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $supplier->update([
            'supplier_code' => $request->supplier_code,
            'supplier_name' => $request->supplier_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'notes' => $request->notes,
            'status' => 'active'
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_supplier',
            'description' => "Updated supplier: {$supplier->supplier_name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier updated successfully.',
                'supplier' => $supplier
            ]);
        }

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy(Request $request, Supplier $supplier)
    {
        // Check if supplier has products
        if ($supplier->products()->count() > 0) {
            return back()->with('error', 'Cannot delete supplier with associated products.');
        }

        $supplierName = $supplier->supplier_name;
        $supplier->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_supplier',
            'description' => "Deleted supplier: {$supplierName}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier deleted successfully.'
            ]);
        }

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }

    /**
     * Toggle supplier status.
     */
    public function toggleStatus(Request $request, Supplier $supplier)
    {
        $supplier->status = $supplier->status === 'active' ? 'inactive' : 'active';
        $supplier->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'toggle_supplier_status',
            'description' => "Changed supplier status to {$supplier->status} for: {$supplier->supplier_name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier status updated successfully.',
            'new_status' => $supplier->status
        ]);
    }
    /**
     * Display purchase history (Stock In transactions).
     */
    public function purchaseHistory(Request $request)
    {
        $query = \App\Models\StockTransaction::with(['product.supplier', 'user'])
            ->where('type', 'in');

        // Apply search filter (product name, code, or supplier name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereNested(function ($q) use ($search) {
                $q->whereHas('product', function ($pq) use ($search) {
                    $pq->where('product_name', 'like', "%{$search}%")
                      ->orWhere('product_code', 'like', "%{$search}%");
                })->orWhereHas('product.supplier', function ($sq) use ($search) {
                    $sq->where('supplier_name', 'like', "%{$search}%");
                });
            });
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }

        // Build total stats query (cloning the current query without pagination)
        $statsQuery = clone $query;
        $totalPurchases = $statsQuery->count();
        $totalItemsReceived = $statsQuery->sum('quantity');
        
        // Use raw query for total value calculation to be more efficient
        $totalValue = $statsQuery->get()->sum(function ($t) {
            return $t->quantity * ($t->unit_cost ?? 0);
        });

        $transactions = $query->latest()->paginate(20);
        $suppliers = Supplier::orderBy('supplier_name')->get();

        return view('Admin.Supplier.purchase_history', compact(
            'transactions', 
            'suppliers', 
            'totalPurchases', 
            'totalItemsReceived', 
            'totalValue'
        ));
    }
}
