<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'customer_type' => 'nullable|in:regular,senior,pwd,pregnant,employee',
            'status' => 'nullable|in:active,inactive',
        ]);

        $query = Customer::query();

        if ($request->filled('search')) {
            $query->whereNested(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(20);

        $totalCount    = Customer::count();
        $regularCount  = Customer::where('customer_type', 'regular')->count();
        $seniorCount   = Customer::whereIn('customer_type', ['senior', 'pwd'])->count();
        $activeCount   = Customer::where('status', 'active')->count();

        return view('supervisor.customers.index', compact(
            'customers', 'totalCount', 'regularCount', 'seniorCount', 'activeCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:customers,email|max:255',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
            'customer_type' => 'required|in:regular,senior,pwd,pregnant,employee',
            'status'        => 'required|in:active,inactive',
        ]);

        $customer = Customer::create($validated);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'create_customer',
            'description' => "Supervisor created customer: {$customer->name}",
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('success', "Customer '{$customer->name}' has been added successfully.");
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:customers,email,' . $customer->id . '|max:255',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
            'customer_type' => 'required|in:regular,senior,pwd,pregnant,employee',
            'status'        => 'required|in:active,inactive',
        ]);

        $customer->update($validated);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'update_customer',
            'description' => "Supervisor updated customer: {$customer->name}",
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('success', "Customer '{$customer->name}' has been updated successfully.");
    }

    public function destroy(Request $request, Customer $customer)
    {
        $name = $customer->name;
        $customer->delete();

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'delete_customer',
            'description' => "Supervisor deleted customer: {$name}",
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('success', "Customer '{$name}' has been deleted.");
    }
}
