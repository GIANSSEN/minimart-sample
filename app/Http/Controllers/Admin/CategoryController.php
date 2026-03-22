<?php
// app/Http/Controllers/Admin/CategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(12);
        
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:categories',
            'slug' => 'nullable|string|unique:categories',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                if (!file_exists(public_path('uploads/categories'))) {
                    mkdir(public_path('uploads/categories'), 0777, true);
                }
                
                $image->move(public_path('uploads/categories'), $imageName);
                $imagePath = 'uploads/categories/' . $imageName;
            }

            // Generate slug if not provided
            $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->category_name);

            $category = Category::create([
                'category_name' => $request->category_name,
                'slug' => $slug,
                'description' => $request->description,
                'image' => $imagePath,
                'icon' => $request->icon ?? 'fa-folder',
                'color' => $request->color ?? '#667eea',
                'created_by' => Auth::id()
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'create_category',
                'action_type' => 'CREATE',
                'model_type' => 'Category',
                'model_id' => $category->id,
                'description' => "Created category: {$category->category_name}",
                'new_values' => json_encode($category->toArray()),
                'ip_address' => $request->ip()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category created successfully.',
                    'category' => $category
                ]);
            }

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error creating category: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Category $category)
    {
        $category->load('products');
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return redirect()->route('admin.categories.show', ['category' => $category->id, 'edit' => 1]);
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $category->id,
            'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $oldData = $category->toArray();
            
            // Handle image upload
            $imagePath = $category->image;
            if ($request->hasFile('image')) {
                // Delete old image
                if ($category->image && file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                if (!file_exists(public_path('uploads/categories'))) {
                    mkdir(public_path('uploads/categories'), 0777, true);
                }
                
                $image->move(public_path('uploads/categories'), $imageName);
                $imagePath = 'uploads/categories/' . $imageName;
            }

            // Generate slug if not provided
            $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->category_name);

            $category->update([
                'category_name' => $request->category_name,
                'slug' => $slug,
                'description' => $request->description,
                'image' => $imagePath,
                'icon' => $request->icon ?? $category->icon,
                'color' => $request->color ?? $category->color,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update_category',
                'action_type' => 'UPDATE',
                'model_type' => 'Category',
                'model_id' => $category->id,
                'description' => "Updated category: {$category->category_name}",
                'old_values' => json_encode($oldData),
                'new_values' => json_encode($category->toArray()),
                'ip_address' => $request->ip()
            ]);

            return redirect()->route('admin.categories.show', $category->id)
                ->with('success', 'Category updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating category: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Category $category)
    {
        try {
            // Check if category has products
            if ($category->products()->count() > 0) {
                return back()->with('error', 'Cannot delete category that has products.');
            }

            $categoryName = $category->category_name;
            $categoryData = $category->toArray();
            
            // Delete image
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }
            
            $category->delete();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete_category',
                'action_type' => 'DELETE',
                'model_type' => 'Category',
                'model_id' => $category->id,
                'description' => "Deleted category: {$categoryName}",
                'old_values' => json_encode($categoryData),
                'ip_address' => request()->ip()
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }


}
