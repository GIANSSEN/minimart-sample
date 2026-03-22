<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'stock'])
            ->where('status', 'active')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'stock'])
            ->find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

   public function search(Request $request)
{
    // PALITAN ITO: $query = $request->input('q');
    $query = $request->input('q'); // ✅ GAMITIN ITO
    
    $products = Product::with(['category', 'stock'])
        ->where('status', 'active')
        ->whereNested(function ($q) use ($query) {
            $q->where('product_name', 'LIKE', "%{$query}%")
              ->orWhere('barcode', 'LIKE', "%{$query}%")
              ->orWhere('product_code', 'LIKE', "%{$query}%");
        })
        ->limit(20)
        ->get();

    return response()->json([
        'success' => true,
        'data' => $products
    ]);
}

    public function getByBarcode($barcode)
    {
        $product = Product::with(['category', 'stock'])
            ->where('barcode', $barcode)
            ->where('status', 'active')
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'product_code' => $product->product_code,
                'barcode' => $product->barcode,
                'product_name' => $product->product_name,
                'price' => $product->selling_price,
                'stock' => $product->stock->quantity ?? 0,
                'unit' => $product->unit
            ]
        ]);
    }

    public function lowStock()
    {
        $products = Product::with(['category', 'stock'])
            ->whereHas('stock', function ($q) {
                $q->whereRaw('quantity <= products.reorder_level');
            })
            ->get();

        return response()->json([
            'success' => true,
            'count' => $products->count(),
            'data' => $products
        ]);
    }
}
