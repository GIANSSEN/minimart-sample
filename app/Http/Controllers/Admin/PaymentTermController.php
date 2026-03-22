<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentTermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PaymentTerm::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('term_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $paymentTerms = $query->latest()->paginate(15);

        return view('Admin.PaymentTerms.index', compact('paymentTerms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'term_name' => 'required|string|max:255|unique:payment_terms',
            'days_due' => 'required|integer|min:0',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $paymentTerm = PaymentTerm::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment term created successfully.',
                'data' => $paymentTerm
            ]);
        }

        return redirect()->route('admin.payment-terms.index')
            ->with('success', 'Payment term created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentTerm $paymentTerm)
    {
        if (request()->ajax()) {
            return response()->json($paymentTerm);
        }
        return redirect()->route('admin.payment-terms.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentTerm $paymentTerm)
    {
        $validator = Validator::make($request->all(), [
            'term_name' => 'required|string|max:255|unique:payment_terms,term_name,' . $paymentTerm->id,
            'days_due' => 'required|integer|min:0',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $paymentTerm->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment term updated successfully.',
                'data' => $paymentTerm
            ]);
        }

        return redirect()->route('admin.payment-terms.index')
            ->with('success', 'Payment term updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentTerm $paymentTerm)
    {
        // Optional: Check if used by suppliers (needs relation)
        
        $paymentTerm->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment term deleted successfully.'
            ]);
        }

        return redirect()->route('admin.payment-terms.index')
            ->with('success', 'Payment term deleted successfully.');
    }
}
