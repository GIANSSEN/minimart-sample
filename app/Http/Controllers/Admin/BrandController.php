<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of brands.
     */
    public function index(Request $request)
    {
        $query = Brand::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereNested(function ($q) use ($search) {
                $q->where('brand_name', 'like', "%{$search}%")
                  ->orWhere('brand_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }



        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = $request->input('per_page', 15);
        $brands = $query->paginate($perPage)->withQueryString();

        // Load product count for each brand
        // FIXED: Using withCount to avoid N+1 query
        $brands->loadCount('products');

        // Calculate stats
        $totalProducts = Product::count();

        return view('Admin.Brands.index', compact('brands', 'totalProducts'));
    }

    /**
     * Show form for creating new brand.
     */
    public function create()
    {
        return view('Admin.Brands.create');
    }

    /**
     * Store a newly created brand.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand_code' => 'required|string|unique:brands',
            'brand_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo')) {
                // Ensure directory exists
                if (!file_exists(public_path('uploads/brands'))) {
                    mkdir(public_path('uploads/brands'), 0755, true);
                }
                
                $logo = $request->file('logo');
                $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('uploads/brands'), $logoName);
                $logoPath = 'uploads/brands/' . $logoName;
            }

            // Generate slug from brand name
            $slug = Str::slug($request->brand_name);

            $brand = Brand::create([
                'brand_code' => $request->brand_code,
                'brand_name' => $request->brand_name,
                'slug' => $slug,
                'description' => $request->description,
                'logo' => $logoPath,
                'website' => $request->website,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'create_brand',
                'action_type' => 'CREATE',
                'model_type' => 'Brand',
                'model_id' => $brand->id,
                'description' => "Created brand: {$brand->brand_name}",
                'new_values' => json_encode($brand->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand created successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error creating brand: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified brand.
     */
    public function show(Brand $brand)
    {
        // FIXED: Load products with correct relationship
        $brand->load(['products' => function ($query) {
            $query->with(['category', 'stock'])->take(10);
        }]);
        
        $productCount = $brand->products()->count();
        
        return view('Admin.Brands.show', compact('brand', 'productCount'));
    }

    /**
     * Show form for editing brand.
     */
    public function edit(Brand $brand)
    {
        return view('Admin.Brands.edit', compact('brand'));
    }

    /**
     * Update the specified brand.
     */
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'brand_code' => 'required|string|unique:brands,brand_code,' . $brand->id,
            'brand_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $oldData = $brand->toArray();
            
            // Handle logo upload
            $logoPath = $brand->logo;
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($brand->logo && file_exists(public_path($brand->logo))) {
                    unlink(public_path($brand->logo));
                }
                
                // Ensure directory exists
                if (!file_exists(public_path('uploads/brands'))) {
                    mkdir(public_path('uploads/brands'), 0755, true);
                }
                
                $logo = $request->file('logo');
                $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('uploads/brands'), $logoName);
                $logoPath = 'uploads/brands/' . $logoName;
            }

            // Check if remove logo is checked
            if ($request->has('remove_logo') && $request->remove_logo) {
                if ($brand->logo && file_exists(public_path($brand->logo))) {
                    unlink(public_path($brand->logo));
                }
                $logoPath = null;
            }

            // Update slug if name changed
            $slug = $brand->slug;
            if ($brand->brand_name !== $request->brand_name) {
                $slug = Str::slug($request->brand_name);
            }

            $brand->update([
                'brand_code' => $request->brand_code,
                'brand_name' => $request->brand_name,
                'slug' => $slug,
                'description' => $request->description,
                'logo' => $logoPath,
                'website' => $request->website,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update_brand',
                'action_type' => 'UPDATE',
                'model_type' => 'Brand',
                'model_id' => $brand->id,
                'description' => "Updated brand: {$brand->brand_name}",
                'old_values' => json_encode($oldData),
                'new_values' => json_encode($brand->toArray()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating brand: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified brand.
     */
    public function destroy(Brand $brand)
    {
        try {
            // Check if brand has products
            if ($brand->products()->count() > 0) {
                return back()->with('error', 'Cannot delete brand that has products. Update products first.');
            }

            $brandName = $brand->brand_name;
            $brandData = $brand->toArray();
            
            // Delete logo
            if ($brand->logo && file_exists(public_path($brand->logo))) {
                unlink(public_path($brand->logo));
            }
            
            $brand->delete();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete_brand',
                'action_type' => 'DELETE',
                'model_type' => 'Brand',
                'model_id' => $brand->id,
                'description' => "Deleted brand: {$brandName}",
                'old_values' => json_encode($brandData),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand deleted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting brand: ' . $e->getMessage());
        }
    }



    /**
     * Export brands to CSV.
     */
    public function export()
    {
        $brands = Brand::withCount('products')->get();
        
        $filename = 'brands-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($brands) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Brand Code',
                'Brand Name',
                'Description',
                'Website',
                'Products',
                'Created'
            ]);

            foreach ($brands as $brand) {
                fputcsv($file, [
                    $brand->brand_code ?? 'N/A',
                    $brand->brand_name,
                    $brand->description ?? '',
                    $brand->website ?? '',
                    $brand->products_count,
                    $brand->created_at->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getBrands()
    {
        $brands = Brand::orderBy('brand_name')
            ->get(['id', 'brand_name', 'brand_code']);

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }
}
