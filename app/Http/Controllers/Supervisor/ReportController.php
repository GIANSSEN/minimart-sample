<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'period' => 'nullable|in:daily,weekly,monthly',
        ]);

        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to', now()->toDateString());
        $period   = $request->input('period', 'daily');

        // Summary stats
        $query = Sale::where('status', 'completed')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        $totalRevenue      = $query->sum('total_amount');
        $totalTransactions = $query->count();
        $avgSaleAmount     = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Top cashier
        $topCashier = Sale::with('cashier')
            ->where('status', 'completed')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->select('user_id', DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->first();

        // Daily sales trend data (for chart)
        $salesTrend = Sale::where('status', 'completed')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Payment method breakdown
        $paymentBreakdown = Sale::where('status', 'completed')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Top 5 products
        $topProducts = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.status', 'completed')
            ->whereDate('sales.created_at', '>=', $dateFrom)
            ->whereDate('sales.created_at', '<=', $dateTo)
            ->select('products.product_name', DB::raw('SUM(sale_items.quantity) as total_qty'), DB::raw('SUM(sale_items.subtotal) as total_revenue'))
            ->groupBy('sale_items.product_id', 'products.product_name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        return view('supervisor.reports.sales', compact(
            'totalRevenue', 'totalTransactions', 'avgSaleAmount',
            'topCashier', 'salesTrend', 'paymentBreakdown', 'topProducts',
            'dateFrom', 'dateTo'
        ));
    }

    public function inventoryReport(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'stock_status' => 'nullable|in:in_stock,low_stock,out_of_stock',
        ]);

        $search     = $request->input('search', '');
        $categoryId = $request->input('category_id', '');
        $stockStatus = $request->input('stock_status', '');

        $query = Product::with(['category', 'stock'])->withoutTrashed();

        if ($search) {
            $query->whereNested(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%");
            });
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($stockStatus) {
            $query->where('inventory_status', $stockStatus);
        }

        $products     = $query->get();
        $categories   = Category::all();

        // Stats
        $totalProducts  = Product::withoutTrashed()->count();
        $lowStockCount  = Product::withoutTrashed()->where('inventory_status', 'low_stock')->count();
        $outOfStock     = Product::withoutTrashed()->where('inventory_status', 'out_of_stock')->count();
        $inStockCount   = Product::withoutTrashed()->where('inventory_status', 'in_stock')->count();

        // Total stock value
        $totalValue = Product::withoutTrashed()
            ->join('stocks', 'products.id', '=', 'stocks.product_id')
            ->select(DB::raw('SUM(stocks.quantity * products.cost_price) as total_value'))
            ->value('total_value') ?? 0;

        return view('supervisor.reports.inventory', compact(
            'products', 'categories', 'totalProducts', 'lowStockCount',
            'outOfStock', 'inStockCount', 'totalValue', 'search', 'categoryId', 'stockStatus'
        ));
    }

    public function profitLoss(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to', now()->toDateString());

        // Revenue from completed sales
        $totalRevenue = Sale::where('status', 'completed')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->sum('total_amount');

        // COGS: sum(sale_items.quantity * products.cost_price)
        $totalCOGS = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.status', 'completed')
            ->whereDate('sales.created_at', '>=', $dateFrom)
            ->whereDate('sales.created_at', '<=', $dateTo)
            ->sum(DB::raw('sale_items.quantity * products.cost_price'));

        $grossProfit  = $totalRevenue - $totalCOGS;
        $netMargin    = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        // Monthly breakdown
        $monthlyData = Sale::where('status', 'completed')
            ->whereDate('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('month')
            ->get();

        // Add COGS per month
        $monthlyCOGS = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.status', 'completed')
            ->whereDate('sales.created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(sales.created_at, '%Y-%m') as month"),
                DB::raw('SUM(sale_items.quantity * products.cost_price) as cogs')
            )
            ->groupBy(DB::raw("DATE_FORMAT(sales.created_at, '%Y-%m')"))
            ->pluck('cogs', 'month');

        return view('supervisor.reports.profit-loss', compact(
            'totalRevenue', 'totalCOGS', 'grossProfit', 'netMargin',
            'monthlyData', 'monthlyCOGS', 'dateFrom', 'dateTo'
        ));
    }
}
