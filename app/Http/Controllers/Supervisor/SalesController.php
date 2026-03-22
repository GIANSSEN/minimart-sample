<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('cashier');

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by cashier
        if ($request->filled('cashier_id')) {
            $query->where('user_id', $request->cashier_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'pending_void') {
                $query->whereIn('status', ['pending_void', 'voided']);
            } elseif ($request->status === 'pending_refund') {
                $query->whereIn('status', ['pending_refund', 'refunded']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $sales = $query->latest()->paginate(20);
        $cashiers = User::where('role', 'cashier')->get();

        return view('supervisor.sales.index', compact('sales', 'cashiers'));
    }

    public function show(Sale $sale)
    {
        $sale->load('items.product', 'cashier');
        return view('supervisor.sales.show', compact('sale'));
    }

    public function void(Request $request, Sale $sale)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if (in_array($sale->status, ['voided', 'refunded'])) {
            return back()->with('error', 'This sale cannot be voided anymore.');
        }

        $sale->status = 'voided';
        $sale->voided_by = Auth::id();
        $sale->voided_at = now();
        $sale->void_reason = $request->reason;
        $sale->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'supervisor_void_sale',
            'description' => "Supervisor voided sale #{$sale->receipt_no}. Reason: {$request->reason}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Sale has been voided successfully.');
    }

    public function refund(Request $request, Sale $sale)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if (in_array($sale->status, ['refunded', 'voided'])) {
            return back()->with('error', 'This sale cannot be refunded anymore.');
        }

        $sale->status = 'refunded';
        $sale->void_reason = 'Refund reason: ' . $request->reason;
        $sale->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'supervisor_refund_sale',
            'description' => "Supervisor refunded sale #{$sale->receipt_no}. Reason: {$request->reason}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Sale has been refunded successfully.');
    }
}
