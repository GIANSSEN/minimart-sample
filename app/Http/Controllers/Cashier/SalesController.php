<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::with('cashier')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);
        
        $todaySales = Sale::whereDate('created_at', today())
            ->where('user_id', Auth::id())
            ->sum('total_amount');
        
        $todayCount = Sale::whereDate('created_at', today())
            ->where('user_id', Auth::id())
            ->count();
        
        return view('cashier.sales.index', compact('sales', 'todaySales', 'todayCount'));
    }

    public function show(Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        $sale->load('items.product', 'cashier');
        return view('cashier.sales.show', compact('sale'));
    }

    public function void(Request $request, Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($sale->status !== 'completed') {
            return response()->json(['error' => 'Sale cannot be voided'], 400);
        }

        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Update sale status
            $sale->status = 'voided';
            $sale->voided_by = Auth::id();
            $sale->voided_at = now();
            $sale->void_reason = $request->reason;
            $sale->save();

            // Return items to stock
            foreach ($sale->items as $item) {
                $stock = $item->product->stock;
                if ($stock) {
                    $stock->quantity += $item->quantity;
                    $stock->save();
                }
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'void_sale',
                'description' => "Voided sale #{$sale->receipt_no}. Reason: {$request->reason}",
                'ip_address' => $request->ip()
            ]);

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function receipt(Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        $sale->load('items.product', 'cashier');
        return view('cashier.sales.receipt', compact('sale'));
    }
}
