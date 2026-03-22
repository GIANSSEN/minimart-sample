<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockApiController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('product')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stocks
        ]);
    }

    public function lowStock()
    {
        $stocks = Stock::with('product')
            ->whereHas('product', function ($q) {
                $q->whereRaw('stocks.quantity <= products.reorder_level');
            })
            ->get();

        return response()->json([
            'success' => true,
            'count' => $stocks->count(),
            'data' => $stocks
        ]);
    }

    public function transactions(Request $request)
    {
        $query = StockTransaction::with(['product', 'user']);

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        $transactions = $query->latest()->limit(100)->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function productStock($productId)
    {
        $stock = Stock::where('product_id', $productId)
            ->with('product')
            ->first();

        if (!$stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $stock
        ]);
    }
}
