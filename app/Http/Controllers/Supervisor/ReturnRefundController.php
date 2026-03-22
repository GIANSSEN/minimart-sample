<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\SalesReturn;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnRefundController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:pending,processed,cancelled',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'search' => 'nullable|string|max:100',
        ]);

        $query = SalesReturn::with(['sale', 'product', 'processor']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->whereHas('sale', function ($q) use ($request) {
                $q->where('receipt_no', 'like', '%' . $request->search . '%');
            });
        }

        $returns = $query->latest()->paginate(20);

        $pendingCount   = SalesReturn::where('status', 'pending')->count();
        $processedCount = SalesReturn::where('status', 'processed')->count();
        $cancelledCount = SalesReturn::where('status', 'cancelled')->count();

        return view('supervisor.returns.index', compact(
            'returns', 'pendingCount', 'processedCount', 'cancelledCount'
        ));
    }

    public function show(SalesReturn $return)
    {
        $return->load(['sale.items.product', 'product', 'processor']);
        return view('supervisor.returns.show', compact('return'));
    }

    public function process(Request $request, SalesReturn $return)
    {
        $request->validate([
            'note' => 'nullable|string|max:500',
        ]);

        if ($return->status !== 'pending') {
            return back()->with('error', 'This return has already been processed or cancelled.');
        }

        $return->status       = 'processed';
        $return->processed_by = Auth::id();
        $return->save();

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'process_return',
            'description' => "Supervisor processed return #{$return->id} for sale #{$return->sale->receipt_no}.",
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('success', 'Return has been processed successfully.');
    }

    public function cancel(Request $request, SalesReturn $return)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if ($return->status !== 'pending') {
            return back()->with('error', 'Only pending returns can be cancelled.');
        }

        $return->status       = 'cancelled';
        $return->processed_by = Auth::id();
        $return->save();

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'cancel_return',
            'description' => "Supervisor cancelled return #{$return->id}. Reason: {$request->reason}",
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('success', 'Return has been cancelled.');
    }
}
