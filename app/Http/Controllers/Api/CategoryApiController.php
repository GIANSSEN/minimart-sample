<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    /**
     * Get all categories
     */
    public function all()
    {
        $categories = Category::select('id', 'category_name', 'description', 'status')
            ->where('status', 'active')
            ->orderBy('category_name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'count' => $categories->count()
        ]);
    }

    /**
     * Get products by category
     */
    public function products($categoryId)
    {
        $category = Category::find($categoryId);
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $products = Product::with('stock')
            ->where('category_id', $categoryId)
            ->where('status', 'active')
            ->select('id', 'product_code', 'barcode', 'product_name', 'selling_price', 'unit')
            ->get();

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->category_name
            ],
            'products' => $products,
            'count' => $products->count()
        ]);
    }

    /**
     * Get category details
     */
    public function show($id)
    {
        $category = Category::withCount('products')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $category->id,
                'name' => $category->category_name,
                'description' => $category->description,
                'status' => $category->status,
                'products_count' => $category->products_count,
                'created_at' => $category->created_at
            ]
        ]);
    }

    /**
     * Get category stats
     */
    public function stats()
    {
        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::where('status', 'active')->count(),
            'inactive_categories' => Category::where('status', 'inactive')->count(),
            'categories_with_products' => Category::has('products')->count(),
            'top_categories' => Category::withCount('products')
                ->orderBy('products_count', 'desc')
                ->limit(5)
                ->get(['id', 'category_name', 'products_count'])
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Search categories
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $categories = Category::where('category_name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->select('id', 'category_name', 'description', 'status')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'count' => $categories->count()
        ]);
    }
}
