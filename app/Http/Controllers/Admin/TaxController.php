<?php
// app/Http/Controllers/Admin/TaxController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxRate;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaxController extends Controller
{
    /**
     * Display a listing of tax rates.
     */
    public function index(Request $request)
    {
        $query = TaxRate::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereNested(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('tax_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = $request->input('per_page', 15);
        $taxRates = $query->paginate($perPage)->withQueryString();

        // Calculate stats
        $stats = [
            'total' => TaxRate::count(),
            'active' => TaxRate::where('status', 'active')->count(),
            'inclusive' => TaxRate::where('type', 'inclusive')->count(),
            'exclusive' => TaxRate::where('type', 'exclusive')->count(),
            'default' => TaxRate::where('is_default', true)->count()
        ];

        $statuses = ['active', 'inactive'];
        $types = ['inclusive', 'exclusive'];

        return view('Admin.Taxes.index', compact('taxRates', 'stats', 'statuses', 'types'));
    }

    /**
     * Show form for creating new tax rate.
     */
    public function create()
    {
        // We've moved to modals on the index page, so redirect back.
        return redirect()->route('admin.taxes.index');
    }

    /**
     * Store a newly created tax rate.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_code' => 'required|string|max:20|unique:tax_rates',
            'name' => 'required|string|max:100',
            'rate' => 'required|numeric|min:0|max:100',
            'type' => 'required|in:inclusive,exclusive',
            'description' => 'nullable|string',
            'is_default' => 'sometimes|boolean',
            'status' => 'required|in:active,inactive',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after:effective_from',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // If this is set as default, remove default from others
            if ($request->has('is_default') && $request->is_default) {
                TaxRate::where('is_default', true)->update(['is_default' => false]);
            }

            $taxRate = TaxRate::create([
                'tax_code' => $request->tax_code,
                'name' => $request->name,
                'rate' => $request->rate,
                'type' => $request->type,
                'description' => $request->description,
                'is_default' => $request->has('is_default') ? true : false,
                'status' => $request->status,
                'effective_from' => $request->effective_from,
                'effective_to' => $request->effective_to,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'create_tax_rate',
                'action_type' => 'CREATE',
                'model_type' => 'TaxRate',
                'model_id' => $taxRate->id,
                'description' => "Created tax rate: {$taxRate->name} ({$taxRate->tax_code})",
                'new_values' => json_encode($taxRate->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tax rate created successfully.',
                    'data'    => $taxRate
                ]);
            }

            return redirect()->route('admin.taxes.index')
                ->with('success', 'Tax rate created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating tax rate: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error creating tax rate: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show form for editing tax rate.
     */
    public function edit(TaxRate $tax)
    {
        return view('Admin.Taxes.edit', compact('tax'));
    }

    /**
     * Update the specified tax rate.
     */
    public function update(Request $request, TaxRate $tax)
    {
        $validator = Validator::make($request->all(), [
            'tax_code' => 'required|string|max:20|unique:tax_rates,tax_code,' . $tax->id,
            'name' => 'required|string|max:100',
            'rate' => 'required|numeric|min:0|max:100',
            'type' => 'required|in:inclusive,exclusive',
            'description' => 'nullable|string',
            'is_default' => 'sometimes|boolean',
            'status' => 'required|in:active,inactive',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after:effective_from',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $oldData = $tax->toArray();

            // If this is set as default, remove default from others
            if ($request->has('is_default') && $request->is_default && !$tax->is_default) {
                TaxRate::where('is_default', true)->update(['is_default' => false]);
            }

            $tax->update([
                'tax_code' => $request->tax_code,
                'name' => $request->name,
                'rate' => $request->rate,
                'type' => $request->type,
                'description' => $request->description,
                'is_default' => $request->has('is_default') ? true : false,
                'status' => $request->status,
                'effective_from' => $request->effective_from,
                'effective_to' => $request->effective_to,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update_tax_rate',
                'action_type' => 'UPDATE',
                'model_type' => 'TaxRate',
                'model_id' => $tax->id,
                'description' => "Updated tax rate: {$tax->name} ({$tax->tax_code})",
                'old_values' => json_encode($oldData),
                'new_values' => json_encode($tax->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            return redirect()->route('admin.taxes.index')
                ->with('success', 'Tax rate updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating tax rate: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified tax rate.
     */
    public function destroy(TaxRate $tax)
    {
        try {
            DB::beginTransaction();

            // Check if tax rate is used by any products
            if ($tax->products_count > 0) {
                return back()->with('error', 'Cannot delete tax rate that is assigned to products.');
            }

            // Prevent deletion of default tax rate
            if ($tax->is_default) {
                return back()->with('error', 'Cannot delete default tax rate. Set another tax rate as default first.');
            }

            $taxName = $tax->name;
            $taxData = $tax->toArray();
            
            $tax->delete();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete_tax_rate',
                'action_type' => 'DELETE',
                'model_type' => 'TaxRate',
                'model_id' => $tax->id,
                'description' => "Deleted tax rate: {$taxName}",
                'old_values' => json_encode($taxData),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            DB::commit();

            return redirect()->route('admin.taxes.index')
                ->with('success', 'Tax rate deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting tax rate: ' . $e->getMessage());
        }
    }

    /**
     * Toggle tax rate status.
     */
    public function toggleStatus(Request $request, TaxRate $tax)
    {
        // Prevent deactivating default tax rate
        if ($tax->is_default && $tax->status === 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot deactivate default tax rate.'
            ], 400);
        }

        $tax->status = $tax->status === 'active' ? 'inactive' : 'active';
        $tax->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'toggle_tax_status',
            'action_type' => 'UPDATE',
            'model_type' => 'TaxRate',
            'model_id' => $tax->id,
            'description' => "Changed tax rate status to {$tax->status} for: {$tax->name}",
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tax rate status updated successfully.',
            'new_status' => $tax->status
        ]);
    }

    /**
     * Set tax rate as default.
     */
    public function setDefault(Request $request, TaxRate $tax)
    {
        DB::beginTransaction();
        try {
            // Remove default from all others
            TaxRate::where('is_default', true)->update(['is_default' => false]);
            
            // Set this as default
            $tax->is_default = true;
            $tax->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'set_default_tax',
                'action_type' => 'UPDATE',
                'model_type' => 'TaxRate',
                'model_id' => $tax->id,
                'description' => "Set tax rate as default: {$tax->name}",
                'ip_address' => $request->ip()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Default tax rate updated successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error setting default tax rate: ' . $e->getMessage()
            ], 500);
        }
    }
}
