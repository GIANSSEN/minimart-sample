<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function stats()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_suppliers' => Supplier::count(),
            'total_users' => User::count(),
            'total_sales' => Sale::sum('total_amount') ?? 0,
            'today_sales' => Sale::whereDate('created_at', today())->sum('total_amount') ?? 0,
            'low_stock' => Product::whereHas('stock', function (\Illuminate\Database\Eloquent\Builder $q) {
                $q->whereRaw('quantity <= products.reorder_level');
            })->count(),
            'recent_transactions' => Sale::with('cashier')->latest()->take(5)->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function weeklySales()
    {
        $weeklySales = Sale::select(
                DB::raw('DAYNAME(created_at) as day'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('day')
            ->orderBy(DB::raw('DAYOFWEEK(created_at)'))
            ->get();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $chartData = [];
        
        foreach ($days as $day) {
            $sale = $weeklySales->firstWhere('day', $day);
            $chartData[$day] = $sale ? (float)$sale->total : 0;
        }

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    public function topProducts()
    {
        $topProducts = SaleItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product->product_name ?? 'Unknown',
                    'total_sold' => $item->total_sold,
                    'total_revenue' => $item->total_revenue
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $topProducts
        ]);
    }

    public function chartData(Request $request)
    {
        $type = $request->input('type', 'weekly');
        
        if ($type === 'monthly') {
            $data = Sale::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('SUM(total_amount) as total')
                )
                ->whereYear('created_at', date('Y'))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        } else {
            $data = Sale::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('DAYNAME(created_at) as day'),
                    DB::raw('SUM(total_amount) as total')
                )
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date', 'day')
                ->orderBy('date')
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
