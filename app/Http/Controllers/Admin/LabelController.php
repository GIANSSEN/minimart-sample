<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        // ✅ GET ALL ACTIVE PRODUCTS FOR LABELS
        $products = Product::with(['category', 'stock'])
            ->where('status', 'active')
            ->orderBy('product_name')
            ->get();
        
        return view('admin.labels.index', compact('products'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'label_size' => 'required|in:small,medium,large',
            'show_price' => 'boolean',
            'show_barcode' => 'boolean'
        ]);

        $products = Product::whereIn('id', $request->products)
            ->with(['category', 'stock'])
            ->get();

        return view('admin.labels.print', compact('products', 'request'));
    }

    public function print($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.labels.single', compact('product'));
    }

    public function printMultiple(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $request->products)->get();
        return view('admin.labels.print', compact('products'));
    }

    public function preview($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.labels.preview', compact('product'));
    }
}
