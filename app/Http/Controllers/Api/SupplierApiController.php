<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;

class SupplierApiController extends Controller
{
    /**
     * Get all suppliers
     */
    public function all()
    {
        $suppliers = Supplier::select('id', 'supplier_code', 'supplier_name', 'contact_person', 'email', 'phone', 'status')
            ->where('status', 'active')
            ->orderBy('supplier_name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $suppliers,
            'count' => $suppliers->count()
        ]);
    }

    /**
     * Get products by supplier
     */
    public function products($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        
        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found'
            ], 404);
        }

        $products = Product::with('stock', 'category')
            ->where('supplier_id', $supplierId)
            ->where('status', 'active')
            ->select('id', 'product_code', 'barcode', 'product_name', 'cost_price', 'selling_price', 'unit', 'category_id')
            ->get();

        return response()->json([
            'success' => true,
            'supplier' => [
                'id' => $supplier->id,
                'name' => $supplier->supplier_name,
                'code' => $supplier->supplier_code
            ],
            'products' => $products,
            'count' => $products->count()
        ]);
    }

    /**
     * Get supplier details
     */
    public function show($id)
    {
        $supplier = Supplier::withCount('products')->find($id);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $supplier->id,
                'code' => $supplier->supplier_code,
                'name' => $supplier->supplier_name,
                'contact_person' => $supplier->contact_person,
                'email' => $supplier->email,
                'phone' => $supplier->phone,
                'mobile' => $supplier->mobile,
                'address' => $supplier->address,
                'tax_id' => $supplier->tax_id,
                'payment_terms' => $supplier->payment_terms,
                'status' => $supplier->status,
                'products_count' => $supplier->products_count,
                'created_at' => $supplier->created_at
            ]
        ]);
    }

    /**
     * Get supplier stats
     */
    public function stats()
    {
        $stats = [
            'total_suppliers' => Supplier::count(),
            'active_suppliers' => Supplier::where('status', 'active')->count(),
            'inactive_suppliers' => Supplier::where('status', 'inactive')->count(),
            'suppliers_with_products' => Supplier::has('products')->count(),
            'top_suppliers' => Supplier::withCount('products')
                ->orderBy('products_count', 'desc')
                ->limit(5)
                ->get(['id', 'supplier_name', 'products_count'])
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Search suppliers
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $suppliers = Supplier::where('supplier_name', 'like', "%{$query}%")
            ->orWhere('supplier_code', 'like', "%{$query}%")
            ->orWhere('contact_person', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->select('id', 'supplier_code', 'supplier_name', 'contact_person', 'email', 'phone', 'status')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $suppliers,
            'count' => $suppliers->count()
        ]);
    }

    /**
     * Get active suppliers (for dropdowns)
     */
    public function getActive()
    {
        $suppliers = Supplier::where('status', 'active')
            ->select('id', 'supplier_code', 'supplier_name')
            ->orderBy('supplier_name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $suppliers
        ]);
    }
}
