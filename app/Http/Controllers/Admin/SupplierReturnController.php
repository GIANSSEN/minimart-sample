<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplierReturn;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SupplierReturn::with(['supplier', 'product']);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%");
            })->orWhere('reason', 'like', "%{$search}%");
        }

        $returns = $query->latest()->paginate(15);
        $suppliers = Supplier::orderBy('supplier_name')->get();
        $products = Product::orderBy('product_name')->get();

        return view('Admin.SupplierReturns.index', compact('returns', 'suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'return_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $supplierReturn = SupplierReturn::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier return recorded successfully.',
                'data' => $supplierReturn
            ]);
        }

        return redirect()->route('admin.supplier-returns.index')->with('success', 'Supplier return recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplierReturn $supplierReturn)
    {
        if (request()->ajax()) {
            return response()->json($supplierReturn->load(['supplier', 'product']));
        }
        return redirect()->route('admin.supplier-returns.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupplierReturn $supplierReturn)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'return_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $supplierReturn->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier return updated successfully.',
                'data' => $supplierReturn
            ]);
        }

        return redirect()->route('admin.supplier-returns.index')->with('success', 'Supplier return updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierReturn $supplierReturn)
    {
        $supplierReturn->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Supplier return deleted successfully.']);
        }

        return redirect()->route('admin.supplier-returns.index')->with('success', 'Supplier return deleted successfully.');
    }
}
