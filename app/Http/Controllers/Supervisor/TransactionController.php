<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'cashier_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:completed,voided,refunded,pending_void,pending_refund,cancelled',
            'payment_method' => 'nullable|in:cash,gcash,card,paymaya',
            'search' => 'nullable|string|max:100',
        ]);

        $query = Sale::with(['cashier', 'items']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('cashier_id')) {
            $query->where('user_id', $request->cashier_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('search')) {
            $query->where('receipt_no', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->latest()->paginate(20);
        $cashiers = User::where('role', 'cashier')->get();

        // Stats
        $totalCount     = Sale::count();
        $completedCount = Sale::where('status', 'completed')->count();
        $voidedCount    = Sale::where('status', 'voided')->count();
        $refundedCount  = Sale::where('status', 'refunded')->count();

        return view('supervisor.transactions.index', compact(
            'transactions', 'cashiers', 'totalCount', 'completedCount', 'voidedCount', 'refundedCount'
        ));
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'cashier']);
        return view('supervisor.transactions.show', compact('sale'));
    }
}
