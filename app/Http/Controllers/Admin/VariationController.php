<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariation;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VariationController extends Controller
{
    /**
     * Display a listing of variations.
     */
    public function index(Request $request)
    {
        $query = ProductVariation::with('product'); // eager load product

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereNested(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('value', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Product filter
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }


        $perPage = $request->input('per_page', 15);
        $variations = $query->latest()->paginate($perPage);

        $totalVariations = ProductVariation::count();
        // For filter dropdown
        $products = Product::active()->orderBy('product_name')->get(['id', 'product_name', 'product_code']);

        return view('Admin.variations.index', compact(
            'variations',
            'totalVariations',
            'products'
        ));
    }

    /**
     * Show form for creating variation.
     */
    public function create()
    {
        $products = Product::active()->orderBy('product_name')->get(['id', 'product_name', 'product_code']);
        return view('Admin.variations.create', compact('products'));
    }

    /**
     * Store a newly created variation.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'nullable|exists:products,id',
            'name' => 'required|string|max:255',
            'value' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:100',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000'
        ], [
            'name.required' => 'The variation name is required.',
            'sku.unique' => 'This SKU is already in use.',
            'cost_price.numeric' => 'The cost price must be a valid number.',
            'selling_price.numeric' => 'The selling price must be a valid number.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image must not exceed 2MB.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'product_id' => $request->product_id,
                'name' => $request->name,
                'value' => $request->value,
                'sku' => $request->sku,
                'cost_price' => $request->cost_price ?? 0,
                'selling_price' => $request->selling_price ?? 0,
                'description' => $request->description
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/variations'), $imageName);
                $data['image'] = 'uploads/variations/' . $imageName;
            }

            $variation = ProductVariation::create($data);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'create_variation',
                'action_type' => 'CREATE',
                'model_type' => 'ProductVariation',
                'model_id' => $variation->id,
                'description' => "Created variation: {$variation->name}" . 
                                 ($variation->value ? " - {$variation->value}" : ""),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('admin.variations.index')
                ->with('success', 'Variation created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating variation: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified variation.
     */
    public function show(ProductVariation $variation)
    {
        $variation->load('product');
        return view('Admin.variations.show', compact('variation'));
    }

    /**
     * Show form for editing variation.
     */
    public function edit(ProductVariation $variation)
    {
        $products = Product::active()->orderBy('product_name')->get(['id', 'product_name', 'product_code']);
        return view('Admin.variations.edit', compact('variation', 'products'));
    }

    /**
     * Update the specified variation.
     */
    public function update(Request $request, ProductVariation $variation)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'nullable|exists:products,id',
            'name' => 'required|string|max:255',
            'value' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:100',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
            'remove_image' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'product_id' => $request->product_id,
                'name' => $request->name,
                'value' => $request->value,
                'sku' => $request->sku,
                'cost_price' => $request->cost_price ?? 0,
                'selling_price' => $request->selling_price ?? 0,
                'description' => $request->description
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($variation->image && file_exists(public_path($variation->image))) {
                    unlink(public_path($variation->image));
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/variations'), $imageName);
                $data['image'] = 'uploads/variations/' . $imageName;
            }

            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image) {
                if ($variation->image && file_exists(public_path($variation->image))) {
                    unlink(public_path($variation->image));
                }
                $data['image'] = null;
            }

            $variation->update($data);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update_variation',
                'action_type' => 'UPDATE',
                'model_type' => 'ProductVariation',
                'model_id' => $variation->id,
                'description' => "Updated variation: {$variation->name}",
                'old_values' => json_encode($variation->getOriginal()),
                'new_values' => json_encode($variation->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('admin.variations.index')
                ->with('success', 'Variation updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating variation: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified variation.
     */
    public function destroy(Request $request, ProductVariation $variation)
    {
        try {
            // Check if variation is used in sales (if relation exists)
            if (method_exists($variation, 'saleItems') && $variation->saleItems()->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete variation that has sales records.');
            }

            $name = $variation->name . ($variation->value ? " - {$variation->value}" : "");
            
            // Delete image
            if ($variation->image && file_exists(public_path($variation->image))) {
                unlink(public_path($variation->image));
            }
            
            $variation->delete();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete_variation',
                'action_type' => 'DELETE',
                'model_type' => 'ProductVariation',
                'model_id' => $variation->id,
                'description' => "Deleted variation: {$name}",
                'old_values' => json_encode($variation->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('admin.variations.index')
                ->with('success', 'Variation deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting variation: ' . $e->getMessage());
        }
    }


    /**
     * Get variations by type (API)
     */
    public function getByType($type)
    {
        $variations = ProductVariation::where('name', $type)
            ->orderBy('value')            ->get(['id', 'name', 'value', 'sku', 'selling_price']);

        return response()->json([
            'success' => true,
            'data' => $variations
        ]);
    }

    /**
     * Bulk delete variations
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:product_variations,id'
        ]);

        try {
            DB::beginTransaction();

            $count = 0;
            foreach ($request->input('ids') as $id) {
                $variation = ProductVariation::find($id);
                if ($variation) {
                    if (method_exists($variation, 'saleItems') && $variation->saleItems()->exists()) {
                        continue;
                    }
                    
                    if ($variation->image && file_exists(public_path($variation->image))) {
                        unlink(public_path($variation->image));
                    }
                    
                    $variation->delete();
                    $count++;
                }
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'bulk_delete_variations',
                'action_type' => 'DELETE',
                'description' => "Bulk deleted {$count} variations",
                'ip_address' => $request->ip()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$count} variations deleted successfully."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting variations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export variations to CSV
     */
    public function export(Request $request)
    {
        $query = ProductVariation::with('product');


        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $variations = $query->get();

        $filename = 'variations-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($variations) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'Product', 'Name', 'Value', 'SKU', 
                'Cost Price', 'Selling Price'
            ]);

            foreach ($variations as $variation) {
                fputcsv($file, [
                    $variation->id,
                    $variation->product->product_name ?? 'N/A',
                    $variation->name,
                    $variation->value,
                    $variation->sku,
                    $variation->cost_price,
                    $variation->selling_price
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
