<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\Sale;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashManagementController extends Controller
{
    public function cashDrops()
    {
        $cashDrops = StockTransaction::where('transaction_type', 'cash_drop')
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('supervisor.cash.drops', compact('cashDrops'));
    }

    public function createCashDrop()
    {
        $cashiers = User::where('role', 'cashier')->get();
        return view('supervisor.cash.create-drop', compact('cashiers'));
    }

    public function storeCashDrop(Request $request)
    {
        $request->validate([
            'cashier_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'reference' => 'nullable|string|max:50',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $cashDrop = StockTransaction::create([
                'transaction_no' => 'CD-' . date('Ymd') . '-' . str_pad(StockTransaction::where('transaction_type', 'cash_drop')->count() + 1, 4, '0', STR_PAD_LEFT),
                'user_id' => $request->cashier_id,
                'transaction_type' => 'cash_drop',
                'amount' => $request->amount,
                'reference_no' => $request->reference,
                'notes' => $request->notes,
                'created_by' => Auth::id()
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'cash_drop',
                'description' => "Cash drop of ₱{$request->amount} from cashier",
                'ip_address' => $request->ip()
            ]);

            DB::commit();

            return redirect()->route('supervisor.cash.drops')
                ->with('success', 'Cash drop recorded successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error recording cash drop: ' . $e->getMessage());
        }
    }

    public function shiftReports()
    {
        $cashiers = User::where('role', 'cashier')->get();
        return view('supervisor.cash.shift-reports', compact('cashiers'));
    }

    public function getShiftReport(Request $request)
    {
        $request->validate([
            'cashier_id' => 'required|exists:users,id',
            'date' => 'required|date'
        ]);

        $sales = Sale::where('cashier_id', $request->cashier_id)
            ->whereDate('created_at', $request->date)
            ->get();

        $summary = [
            'total_sales' => $sales->sum('total_amount'),
            'transaction_count' => $sales->count(),
            'cash_sales' => $sales->where('payment_method', 'cash')->sum('total_amount'),
            'gcash_sales' => $sales->where('payment_method', 'gcash')->sum('total_amount'),
            'voided_count' => $sales->where('status', 'voided')->count(),
            'refunded_count' => $sales->where('status', 'refunded')->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $summary,
            'sales' => $sales
        ]);
    }
}
