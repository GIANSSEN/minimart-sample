<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,gcash,paymaya',
            'amount_tendered' => 'required|numeric',
            'customer_name' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal += $item['quantity'] * $product->selling_price;
            }

            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;
            $change = $request->amount_tendered - $total;

            $receiptNo = 'RCT-' . date('Ymd') . '-' . str_pad(Sale::count() + 1, 6, '0', STR_PAD_LEFT);

            $sale = Sale::create([
                'receipt_no' => $receiptNo,
                'customer_name' => $request->customer_name,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'amount_tendered' => $request->amount_tendered,
                'change_amount' => $change,
                'cashier_id' => Auth::id()
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
                    'subtotal' => $item['quantity'] * $product->selling_price
                ]);

                $stock = Stock::where('product_id', $item['product_id'])->first();
                if ($stock) {
                    $stock->quantity -= $item['quantity'];
                    $stock->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully',
                'data' => [
                    'receipt_no' => $receiptNo,
                    'total' => $total,
                    'change' => $change
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function today()
    {
        $sales = Sale::whereDate('created_at', today())
            ->with('cashier')
            ->get();

        return response()->json([
            'success' => true,
            'total' => $sales->sum('total_amount'),
            'count' => $sales->count(),
            'data' => $sales
        ]);
    }
}
