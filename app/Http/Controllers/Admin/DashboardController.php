<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\ValueObjects\ActivityItem; // <-- DTO class
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $today = Carbon::today();
        
        // ✅ FIXED: Check and fix products without stock
        $this->ensureAllProductsHaveStock();
        
        // Get actual stock data from stocks table
        $totalStock = Stock::sum('quantity') ?? 0;
        $productsWithStock = Stock::where('quantity', '>', 0)->count();
        $productsOutOfStock = Stock::where('quantity', '<=', 0)->count();
        
        // ✅ FIXED: Better low stock calculation
        $productsLowStock = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->whereRaw('stocks.quantity <= products.reorder_level')
            ->where('stocks.quantity', '>', 0)
            ->count();
        
        // Calculate inventory value correctly
        $inventoryValue = Stock::join('products', 'stocks.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(stocks.quantity * products.selling_price) as total'))
            ->value('total') ?? 0;
        
        // ✅ FIXED: Get stock status for chart - using proper thresholds
        $stockStatus = [
            'In Stock' => Stock::where('quantity', '>', 10)->count(),
            'Low Stock' => Stock::where('quantity', '<=', 10)->where('quantity', '>', 0)->count(),
            'Out of Stock' => Stock::where('quantity', '<=', 0)->count(),
        ];
        
        // Inventory Alerts
        $inventoryAlerts = [
            'expired' => Product::where('has_expiry', true)
                ->where('expiry_date', '<', $today)
                ->count(),
            'near_expiry' => Product::where('has_expiry', true)
                ->where('expiry_date', '>=', $today)
                ->where('expiry_date', '<=', $today->copy()->addDays(30))
                ->count(),
            'low_stock' => $productsLowStock,
            'out_of_stock' => $productsOutOfStock,
            'phase_out' => Product::where('is_phase_out', true)->count(),
            'discontinued' => Product::where('status', 'discontinued')->count(),
        ];
        
        // Calculate business health score
        $businessHealth = $this->calculateBusinessHealth($inventoryAlerts);
        
        $data = [
            // Basic Statistics
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalSuppliers' => Supplier::count(),
            'totalUsers' => User::count(),
            'activeProducts' => Product::where('status', 'active')->count(),
            
            // Sales Data
            'totalSales' => Sale::sum('total_amount') ?? 0,
            'todaySales' => Sale::whereDate('created_at', today())->sum('total_amount') ?? 0,
            'todayTransactions' => Sale::whereDate('created_at', today())->count(),
            'currentMonthSales' => Sale::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount') ?? 0,
            'lastMonthSales' => Sale::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->sum('total_amount') ?? 0,
            'salesGrowth' => $this->calculateSalesGrowth(),
            
            // Inventory Data
            'inventoryValue' => $inventoryValue,
            'inventoryAlerts' => $inventoryAlerts,
            'lowStockCount' => $productsLowStock,
            'stockStatus' => $stockStatus,
            'totalStock' => $totalStock,
            'productsWithStock' => $productsWithStock,
            'productsOutOfStock' => $productsOutOfStock,
            
            // Business Health
            'businessHealth' => $businessHealth,
            
            // Chart Data
            'salesChartData' => $this->getSalesChartData(),
            'salesTrend' => $this->getSalesTrend(),
            
            // Performance Data
            'topProducts' => $this->getTopProducts(),
            'recentSales' => $this->getRecentSales(),
            
            // User Data
            'pendingUsers' => $this->getPendingUsers(),
            'pendingCount' => $this->getPendingCount(),
            
            // Activity Data
            'recentActivities' => $this->getRecentActivities(),
            
            // Category Distribution
            'categoryDistribution' => $this->getCategoryDistribution(),
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Ensure all products have stock records
     */
    private function ensureAllProductsHaveStock()
    {
        $productsWithoutStock = Product::doesntHave('stock')->get();
        
        foreach ($productsWithoutStock as $product) {
            Stock::create([
                'product_id' => $product->id,
                'quantity' => 0,
                'min_quantity' => $product->reorder_level ?? 10,
                'max_quantity' => $product->max_level ?? 1000,
                'location' => $product->shelf_location ?? 'A1'
            ]);
        }
    }

    /**
     * Calculate business health score.
     */
    private function calculateBusinessHealth($inventoryAlerts)
    {
        $score = 100; // Start with perfect score
        
        // Deduct for inventory issues
        $score -= ($inventoryAlerts['low_stock'] * 2);
        $score -= ($inventoryAlerts['out_of_stock'] * 5);
        $score -= ($inventoryAlerts['expired'] * 10);
        $score -= ($inventoryAlerts['near_expiry'] * 3);
        
        // Ensure score stays within 0-100
        return max(0, min(100, $score));
    }

    /**
     * Calculate sales growth percentage.
     */
    private function calculateSalesGrowth(): float
    {
        $currentMonth = Sale::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount') ?? 0;
        $lastMonth = Sale::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_amount') ?? 0;
        
        if ($lastMonth > 0) {
            return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
        } elseif ($currentMonth > 0) {
            return 100;
        }
        
        return 0;
    }

    /**
     * Get sales chart data.
     */
    private function getSalesChartData(): array
    {
        try {
            $weeklySales = Sale::select(
                    DB::raw('DAYNAME(created_at) as day'),
                    DB::raw('SUM(total_amount) as total')
                )
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('day')
                ->orderBy(DB::raw('DAYOFWEEK(created_at)'))
                ->get()
                ->keyBy('day');

            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $chartData = [];
            
            foreach ($days as $day) {
                $chartData[$day] = isset($weeklySales[$day]) ? $weeklySales[$day]->total : 0;
            }

            return $chartData;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get sales trend data.
     */
    private function getSalesTrend(): array
    {
        $salesData = $this->getSalesChartData();
        $trend = [];
        $previousAmount = 0;
        
        foreach ($salesData as $day => $amount) {
            if ($previousAmount > 0) {
                if ($amount > $previousAmount) {
                    $trend[$day] = 'up';
                } elseif ($amount < $previousAmount) {
                    $trend[$day] = 'down';
                } else {
                    $trend[$day] = 'neutral';
                }
            }
            $previousAmount = $amount;
        }

        return $trend;
    }

    /**
     * Get top performing products.
     */
    private function getTopProducts()
    {
        try {
            return SaleItem::select(
                    'product_id',
                    DB::raw('SUM(quantity) as total_sold'),
                    DB::raw('SUM(subtotal) as total_revenue')
                )
                ->with('product')
                ->groupBy('product_id')
                ->orderBy('total_sold', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get recent sales.
     */
    private function getRecentSales()
    {
        try {
            return Sale::with('user')
                ->latest()
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get pending users.
     */
    private function getPendingUsers()
    {
        try {
            return User::where('approval_status', 'pending')
                ->latest()
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get pending users count.
     */
    private function getPendingCount(): int
    {
        try {
            return User::where('approval_status', 'pending')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get recent activities.
     *
     * @return \Illuminate\Support\Collection<int, \App\ValueObjects\ActivityItem>
     */
    private function getRecentActivities()
    {
        $activities = collect();

        try {
            // Recent sales
            $sales = Sale::with('user')
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($sale) {
                    return new ActivityItem(
                        'sale',
                        "New sale: ₱" . number_format($sale->total_amount, 2) . " by " . ($sale->user->name ?? 'Cashier'),
                        $sale->created_at,
                        'fa-shopping-cart',
                        '#f39c12'
                    );
                });
            $activities = $activities->merge($sales);
        } catch (\Exception $e) {}

        try {
            // Recent products
            $products = Product::latest()
                ->take(5)
                ->get()
                ->map(function ($product) {
                    return new ActivityItem(
                        'product',
                        "New product added: {$product->product_name}",
                        $product->created_at,
                        'fa-box',
                        '#3498db'
                    );
                });
            $activities = $activities->merge($products);
        } catch (\Exception $e) {}

        try {
            // Recent users
            $users = User::latest()
                ->take(5)
                ->get()
                ->map(function ($user) {
                    $name = $user->name ?? $user->full_name ?? 'Unknown';
                    return new ActivityItem(
                        'user',
                        "New user registered: {$name}",
                        $user->created_at,
                        'fa-user-plus',
                        '#2ecc71'
                    );
                });
            $activities = $activities->merge($users);
        } catch (\Exception $e) {}

        try {
            // Stock transactions
            $transactions = StockTransaction::with('product', 'user')
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($transaction) {
                    $action = $transaction->type == 'in' ? 'added to' : 'removed from';
                    return new ActivityItem(
                        'stock',
                        "{$transaction->quantity} units {$action} " . ($transaction->product->product_name ?? 'product'),
                        $transaction->created_at,
                        'fa-boxes',
                        '#9b59b6'
                    );
                });
            $activities = $activities->merge($transactions);
        } catch (\Exception $e) {}

        return $activities->sortByDesc('created_at')->take(10);
    }

    /**
     * Get category distribution.
     */
    private function getCategoryDistribution(): array
    {
        try {
            $categories = Category::withCount('products')
                ->having('products_count', '>', 0)
                ->get();
            return $categories->pluck('products_count', 'name')->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get real-time statistics for AJAX updates.
     */
    public function getStats()
    {
        $today = Carbon::today();
        
        $productsLowStock = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->whereRaw('stocks.quantity <= products.reorder_level')
            ->where('stocks.quantity', '>', 0)
            ->count();
        
        $productsOutOfStock = Stock::where('quantity', '<=', 0)->count();
        
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_suppliers' => Supplier::count(),
            'total_users' => User::count(),
            'total_sales' => Sale::sum('total_amount') ?? 0,
            'today_sales' => Sale::whereDate('created_at', $today)->sum('total_amount') ?? 0,
            'today_transactions' => Sale::whereDate('created_at', $today)->count(),
            'inventory_value' => Stock::join('products', 'stocks.product_id', '=', 'products.id')
                ->select(DB::raw('SUM(stocks.quantity * products.selling_price) as total'))
                ->value('total') ?? 0,
            'inventory_alerts' => [
                'expired' => Product::where('has_expiry', true)->where('expiry_date', '<', $today)->count(),
                'near_expiry' => Product::where('has_expiry', true)
                    ->where('expiry_date', '>=', $today)
                    ->where('expiry_date', '<=', $today->copy()->addDays(30))
                    ->count(),
                'low_stock' => $productsLowStock,
                'out_of_stock' => $productsOutOfStock,
            ],
            'business_health' => $this->calculateBusinessHealth([
                'low_stock' => $productsLowStock,
                'out_of_stock' => $productsOutOfStock,
                'expired' => 0,
                'near_expiry' => 0
            ]),
        ];

        return response()->json($stats);
    }

    /**
     * Get chart data based on period.
     */
    public function getChartData(Request $request)
    {
        $period = $request->input('period', 'week');
        
        switch($period) {
            case 'day':
                $data = $this->getHourlySales();
                break;
            case 'week':
                $data = $this->getWeeklySales();
                break;
            case 'month':
                $data = $this->getMonthlySales();
                break;
            case 'year':
                $data = $this->getYearlySales();
                break;
            default:
                $data = $this->getWeeklySales();
        }

        return response()->json($data);
    }

    /**
     * Get hourly sales data for today.
     */
    private function getHourlySales(): array
    {
        try {
            $hours = [];
            $sales = [];
            $today = Carbon::today();
            
            for ($i = 0; $i < 24; $i++) {
                $hours[] = $i . ':00';
                $total = Sale::whereDate('created_at', $today)
                    ->whereTime('created_at', '>=', $today->copy()->setTime($i, 0))
                    ->whereTime('created_at', '<', $today->copy()->setTime($i + 1, 0))
                    ->sum('total_amount');
                $sales[] = $total ?: 0;
            }

            return ['labels' => $hours, 'data' => $sales];
        } catch (\Exception $e) {
            return ['labels' => [], 'data' => []];
        }
    }

    /**
     * Get weekly sales data.
     */
    private function getWeeklySales(): array
    {
        try {
            $days = [];
            $sales = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $days[] = $date->format('D');
                $total = Sale::whereDate('created_at', $date)->sum('total_amount');
                $sales[] = $total ?: 0;
            }

            return ['labels' => $days, 'data' => $sales];
        } catch (\Exception $e) {
            return ['labels' => [], 'data' => []];
        }
    }

    /**
     * Get monthly sales data (weekly breakdown).
     */
    private function getMonthlySales(): array
    {
        try {
            $weeks = [];
            $sales = [];
            
            for ($i = 0; $i < 4; $i++) {
                $startOfWeek = Carbon::now()->startOfMonth()->addWeeks($i);
                $endOfWeek = Carbon::now()->startOfMonth()->addWeeks($i + 1)->subDay();
                
                $weeks[] = 'Week ' . ($i + 1);
                $total = Sale::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_amount');
                $sales[] = $total ?: 0;
            }

            return ['labels' => $weeks, 'data' => $sales];
        } catch (\Exception $e) {
            return ['labels' => [], 'data' => []];
        }
    }

    /**
     * Get yearly sales data (monthly breakdown).
     */
    private function getYearlySales(): array
    {
        try {
            $months = [];
            $sales = [];
            
            for ($i = 1; $i <= 12; $i++) {
                $months[] = Carbon::createFromDate(null, $i, 1)->format('M');
                $total = Sale::whereMonth('created_at', $i)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('total_amount');
                $sales[] = $total ?: 0;
            }

            return ['labels' => $months, 'data' => $sales];
        } catch (\Exception $e) {
            return ['labels' => [], 'data' => []];
        }
    }

    /**
     * Export dashboard data.
     */
    public function exportData(Request $request)
    {
        $format = $request->input('format', 'excel');
        
        return response()->json([
            'success' => true,
            'message' => 'Export started successfully',
            'format' => $format
        ]);
    }

    /**
     * Refresh dashboard data.
     */
    public function refreshData()
    {
        $today = Carbon::today();
        
        $productsLowStock = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->whereRaw('stocks.quantity <= products.reorder_level')
            ->where('stocks.quantity', '>', 0)
            ->count();
        
        $productsOutOfStock = Stock::where('quantity', '<=', 0)->count();
        
        $data = [
            'total_sales' => Sale::sum('total_amount') ?? 0,
            'today_sales' => Sale::whereDate('created_at', $today)->sum('total_amount') ?? 0,
            'today_transactions' => Sale::whereDate('created_at', $today)->count(),
            'total_products' => Product::count(),
            'inventory_alerts' => [
                'expired' => Product::where('has_expiry', true)->where('expiry_date', '<', $today)->count(),
                'near_expiry' => Product::where('has_expiry', true)
                    ->where('expiry_date', '>=', $today)
                    ->where('expiry_date', '<=', $today->copy()->addDays(30))
                    ->count(),
                'low_stock' => $productsLowStock,
                'out_of_stock' => $productsOutOfStock,
            ],
            'business_health' => $this->calculateBusinessHealth([
                'low_stock' => $productsLowStock,
                'out_of_stock' => $productsOutOfStock,
                'expired' => 0,
                'near_expiry' => 0
            ]),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
