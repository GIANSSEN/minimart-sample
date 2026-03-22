<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\UOMController;
use App\Http\Controllers\Admin\VariationController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\LabelController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\PaymentTermController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SupplierReturnController;
use App\Http\Controllers\Cashier\DashboardController as CashierDashboard;
use App\Http\Controllers\Cashier\POSController;
use App\Http\Controllers\Cashier\SalesController as CashierSalesController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboard;
use App\Http\Controllers\Supervisor\SalesController as SupervisorSalesController;
use App\Http\Controllers\Supervisor\CashManagementController;
use App\Http\Controllers\Supervisor\TransactionController;
use App\Http\Controllers\Supervisor\ReturnRefundController;
use App\Http\Controllers\Supervisor\CustomerController;
use App\Http\Controllers\Supervisor\ReportController as SupervisorReportController;

// API Controllers
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\SaleApiController;
use App\Http\Controllers\Api\StockApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\SupplierApiController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Full Access)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin_or_supervisor'])->group(function () {
    
    // ==================== DASHBOARD ====================
    Route::middleware('permission:view-dashboard')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [AdminDashboard::class, 'getStats'])->name('dashboard.stats');
        Route::get('/dashboard/chart-data', [AdminDashboard::class, 'getChartData'])->name('dashboard.chart-data');
        Route::post('/dashboard/refresh', [AdminDashboard::class, 'refreshData'])->name('dashboard.refresh');
    });
    
    Route::middleware(['admin', 'permission:manage-users'])->group(function () {
        // ==================== USER MAINTENANCE ====================
        // User routes - SPECIFIC ROUTES MUST COME BEFORE RESOURCE
        Route::get('/users/stats', [UserController::class, 'stats'])->name('users.stats');
        Route::get('/users/pending', [UserController::class, 'pending'])->name('users.pending');
        Route::post('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
        // BULK ROUTES
        Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
        Route::post('/users/bulk-approve', [UserController::class, 'bulkApprove'])->name('users.bulk-approve');
        // EXPORT ROUTE (ADDED)
        Route::post('/users/export', [UserController::class, 'export'])->name('users.export');
        // User Resource (MUST BE LAST)
        Route::resource('users', UserController::class);
        
        // ==================== ROLES & PERMISSIONS ====================
        Route::resource('roles', RoleController::class);
        Route::post('/roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
        Route::post('/roles/assign-to-user', [RoleController::class, 'assignToUser'])->name('roles.assign');
        Route::post('/roles/remove-from-user', [RoleController::class, 'removeFromUser'])->name('roles.remove');
        Route::get('/roles/{role}/permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions');
        
        // ==================== ACTIVITY LOGS ====================
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{id}/details', [ActivityLogController::class, 'getDetails'])->name('activity-logs.details');
        Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
        Route::get('/activity-logs/export/{format}', [ActivityLogController::class, 'export'])->name('activity-logs.export');
        Route::delete('/activity-logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
        Route::delete('/activity-logs/clear/all', [ActivityLogController::class, 'clearAll'])->name('activity-logs.clear');
    });
    
    Route::middleware('permission:manage-products')->group(function () {
        // ==================== PRODUCT MAINTENANCE ====================
        // Product routes - SPECIFIC ROUTES MUST COME BEFORE RESOURCE
        Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
        Route::get('/products/expiry-monitoring', [ProductController::class, 'expiryMonitoring'])->name('products.expiry');
        Route::get('/products/expiry/export', [ProductController::class, 'exportExpiryReport'])->name('products.expiry.export');
        Route::post('/products/expiry/bulk-update', [ProductController::class, 'bulkUpdateExpiry'])->name('products.expiry.bulk-update');
        Route::post('/products/{product}/mark-expired', [ProductController::class, 'markAsExpired'])->name('products.mark-expired');
        Route::post('/products/{product}/extend-expiry', [ProductController::class, 'extendExpiry'])->name('products.extend-expiry');
        Route::get('/products/export/{format}', [ProductController::class, 'export'])->name('products.export');
        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
        Route::post('/products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');
        Route::get('/products/barcode/{barcode}', [ProductController::class, 'getByBarcode'])->name('products.barcode');
        Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
        Route::post('/products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
        Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        
        // Product Resource (MUST BE LAST)
        Route::resource('products', ProductController::class);
        
        // ==================== CATEGORIES ====================
        Route::resource('categories', CategoryController::class);
        Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        
        // ==================== BRANDS ====================
        Route::get('/brands/export', [BrandController::class, 'export'])->name('brands.export');
        Route::resource('brands', BrandController::class);
        Route::post('/brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status');
    });

    Route::middleware('permission:manage-uom')->group(function () {
        // ==================== UNIT OF MEASUREMENT ====================
        Route::resource('uom', UOMController::class);
        Route::post('/uom/{uom}/toggle-status', [UOMController::class, 'toggleStatus'])->name('uom.toggle-status');
    });

    Route::middleware('permission:manage-variations')->group(function () {
        // ==================== VARIATIONS ====================
        Route::get('/variations/type/{type}', [VariationController::class, 'getByType'])->name('variations.by-type');
        Route::post('/variations/{variation}/toggle-status', [VariationController::class, 'toggleStatus'])->name('variations.toggle-status');
        Route::post('/variations/{variation}/stock', [VariationController::class, 'updateStock'])->name('variations.stock');
        Route::post('/variations/bulk-delete', [VariationController::class, 'bulkDelete'])->name('variations.bulk-delete');
        Route::get('/variations/export', [VariationController::class, 'export'])->name('variations.export');
        Route::resource('variations', VariationController::class);
    });

    Route::middleware('permission:manage-suppliers')->group(function () {
        // ==================== SUPPLIERS ====================
        Route::resource('suppliers', SupplierController::class);
        Route::post('/suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');
        
        // NEW SUBMENUS
        Route::get('/purchase-history', [SupplierController::class, 'purchaseHistory'])->name('purchase-history.index');
        Route::resource('supplier-returns', SupplierReturnController::class);
    });

    Route::middleware('permission:manage-payment-terms')->group(function () {
        Route::resource('payment-terms', PaymentTermController::class);
    });
    
    
    // ==================== INVENTORY MANAGEMENT ====================
    Route::prefix('inventory')->name('inventory.')->controller(InventoryController::class)->middleware('permission:manage-inventory')->group(function () {
        // Page routes
        Route::get('/', 'index')->name('index');
        Route::get('/stock-in', 'stockIn')->name('stock-in');
        Route::get('/stock-out', 'stockOut')->name('stock-out');
        Route::get('/alerts', 'alerts')->name('alerts');
        Route::post('/alerts/restock', 'restockFromAlert')->name('alerts.restock');
        Route::post('/alerts/dispose/{product}', 'disposeProductAlert')->name('alerts.dispose');
        Route::post('/alerts/dispose-expired', 'bulkDisposeExpiredAlerts')->name('alerts.dispose-expired');
        Route::post('/alerts/promote/{product}', 'promoteNearExpiryProduct')->name('alerts.promote');
        Route::post('/alerts/promote-near-expiry', 'bulkPromoteNearExpiry')->name('alerts.promote-near-expiry');
        Route::get('/summary', 'summary')->name('summary');
        Route::get('/history/{id}', 'history')->name('history');
        Route::get('/history', 'allHistory')->name('all-history');
        
        // AJAX/Process routes
        Route::post('/process-stock-in', 'processStockIn')->name('process-stock-in');
        Route::post('/process-stock-out', 'processStockOut')->name('process-stock-out');
        Route::post('/process', 'process')->name('process');
        Route::post('/adjust', 'adjust')->name('adjust');
        Route::post('/bulk-update-expiry', 'bulkUpdateExpiry')->name('bulk-update-expiry');
        Route::get('/product/{id}/stock', 'getProductStock')->name('product-stock');
        Route::get('/export-history', 'exportHistory')->name('export-history');

        // 🆕 NEW ROUTES for transaction details and void
        Route::get('/transaction/{id}', 'getTransaction')->name('transaction');
        Route::post('/transaction/{id}/void', 'voidTransaction')->name('transaction.void');
    });
    
    // ==================== REPORTS ====================
    Route::prefix('reports')->name('reports.')->controller(ReportController::class)->middleware('permission:view-reports')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/sales', 'sales')->name('sales');
        Route::get('/products', 'products')->name('products');
        Route::get('/categories', 'categories')->name('categories');
        Route::get('/brands', 'brands')->name('brands');
        Route::get('/suppliers', 'suppliers')->name('suppliers');
        Route::get('/inventory', 'inventory')->name('inventory');
        Route::get('/profit-loss', 'profitLoss')->name('profit-loss');
        Route::get('/export/{type}/{format}', 'export')->name('export');
        Route::get('/sales/chart-data', 'salesChartData')->name('sales.chart');
        Route::get('/print/{type}', 'print')->name('print');
    });
    
    Route::middleware(['admin', 'permission:manage-system'])->group(function () {
        // ==================== IMPORT ====================
        Route::prefix('import')->name('import.')->controller(ImportController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/products', 'importProducts')->name('products');
            Route::get('/template', 'downloadTemplate')->name('template');
            Route::get('/history', 'history')->name('history');
        });
        
        // ==================== LABELS ====================
        Route::prefix('labels')->name('labels.')->controller(LabelController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/generate', 'generate')->name('generate');
            Route::get('/print/{id}', 'print')->name('print');
            Route::post('/print-multiple', 'printMultiple')->name('print.multiple');
            Route::get('/preview/{id}', 'preview')->name('preview');
        });

        // ==================== TAX RATES ====================
        Route::resource('taxes', TaxController::class)->except(['show']);
        Route::post('/taxes/{tax}/toggle-status', [TaxController::class, 'toggleStatus'])->name('taxes.toggle-status');
        Route::post('/taxes/{tax}/set-default', [TaxController::class, 'setDefault'])->name('taxes.set-default');

        // ==================== SETTINGS ====================
        Route::prefix('settings')->name('settings.')->controller(SettingsController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'update')->name('update');
            Route::post('/clear-cache', 'clearCache')->name('clear-cache');
            Route::post('/test-db', 'testDb')->name('test-db');
        });
    });
});

/*
|--------------------------------------------------------------------------
| SUPERVISOR ROUTES (Senior Cashier)
|--------------------------------------------------------------------------
*/
Route::prefix('supervisor')->name('supervisor.')->middleware(['auth', 'supervisor'])->group(function () {
    
    // DASHBOARD
    Route::get('/dashboard', [SupervisorDashboard::class, 'index'])
        ->middleware('permission:view-dashboard')
        ->name('dashboard');
    
    // SALES MANAGEMENT (Voids & Refunds)
    Route::controller(SupervisorSalesController::class)->prefix('sales')->name('sales.')->middleware('permission:manage-sales')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{sale}', 'show')->name('show');
        Route::post('/{sale}/void', 'void')->name('void');
        Route::post('/{sale}/refund', 'refund')->name('refund');
    });
    
    // CASH MANAGEMENT (Cash Drops & Shift Reports)
    Route::controller(CashManagementController::class)->prefix('cash')->name('cash.')->middleware('permission:manage-sales')->group(function () {
        Route::get('/drops', 'cashDrops')->name('drops');
        Route::get('/drops/create', 'createCashDrop')->name('create-drop');
        Route::post('/drops', 'storeCashDrop')->name('store-drop');
        Route::get('/shift-reports', 'shiftReports')->name('shift-reports');
        Route::post('/shift-report', 'getShiftReport')->name('get-shift-report');
    });

    // TRANSACTIONS
    Route::middleware('permission:manage-sales')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{sale}', [TransactionController::class, 'show'])->name('transactions.show');
    });

    // RETURNS & REFUNDS
    Route::middleware('permission:manage-sales')->group(function () {
        Route::get('/returns', [ReturnRefundController::class, 'index'])->name('returns.index');
        Route::get('/returns/{return}', [ReturnRefundController::class, 'show'])->name('returns.show');
        Route::post('/returns/{return}/process', [ReturnRefundController::class, 'process'])->name('returns.process');
        Route::post('/returns/{return}/cancel', [ReturnRefundController::class, 'cancel'])->name('returns.cancel');
    });

    // CUSTOMERS
    Route::middleware('permission:manage-sales')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });

    // SUPERVISOR REPORTS
    Route::middleware('permission:view-reports')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [SupervisorReportController::class, 'salesReport'])->name('sales');
        Route::get('/inventory', [SupervisorReportController::class, 'inventoryReport'])->name('inventory');
        Route::get('/profit-loss', [SupervisorReportController::class, 'profitLoss'])->name('profit-loss');
    });
});

/*
|--------------------------------------------------------------------------
| CASHIER ROUTES (POS Only)
|--------------------------------------------------------------------------
*/
Route::prefix('cashier')->name('cashier.')->middleware(['auth', 'cashier', 'permission:access-pos'])->group(function () {
    
    // DASHBOARD (redirects to POS)
    Route::get('/dashboard', [CashierDashboard::class, 'index'])->name('dashboard');
    
    // POS SYSTEM
    Route::controller(POSController::class)->prefix('pos')->name('pos.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/sale', 'storeSale')->name('store');
        Route::get('/product/{barcode}', 'getProduct')->name('product');
        Route::get('/search', 'searchProducts')->name('search');
        Route::get('/receipt/{id}', 'printReceipt')->name('receipt');
        Route::post('/void/{sale}', 'voidSale')->name('void');
        Route::get('/history', 'history')->name('history');
        Route::get('/summary', 'summary')->name('summary');
    });
    
    // SALES HISTORY
    Route::controller(CashierSalesController::class)->prefix('sales')->name('sales.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{sale}', 'show')->name('show');
        Route::post('/{sale}/void', 'void')->name('void');
        Route::get('/{sale}/receipt', 'receipt')->name('receipt');
        Route::get('/export/{format}', 'export')->name('export');
        Route::get('/today', 'today')->name('today');
    });
});

/*
|--------------------------------------------------------------------------
| API ROUTES (AJAX Requests)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->name('api.')->middleware('auth')->group(function () {
    
    // ==================== USER API ====================
    Route::get('/users/{id}', [UserController::class, 'apiShow'])
        ->middleware('permission:manage-users')
        ->name('users.show');
    
    // ==================== PRODUCT API ====================
    Route::prefix('products')->name('products.')->controller(ProductApiController::class)->group(function () {
        Route::get('/search', 'search')->name('search');
        Route::get('/barcode/{barcode}', 'getByBarcode')->name('barcode');
        Route::get('/low-stock', 'lowStock')->name('low-stock');
        Route::get('/category/{category}', 'byCategory')->name('category');
        Route::get('/recent', 'recent')->name('recent');
        Route::get('/all', 'all')->name('all');
        Route::get('/{id}', 'show')->name('show');
    });
    
    // ==================== SALES API ====================
    Route::prefix('sales')->name('sales.')->controller(SaleApiController::class)->group(function () {
        Route::get('/today', 'today')->name('today');
        Route::get('/weekly', 'weekly')->name('weekly');
        Route::get('/monthly', 'monthly')->name('monthly');
        Route::get('/chart-data', 'chartData')->name('chart');
        Route::get('/summary', 'summary')->name('summary');
        Route::get('/recent', 'recent')->name('recent');
    });
    
    // ==================== STOCK API ====================
    Route::prefix('stock')->name('stock.')->controller(StockApiController::class)->group(function () {
        Route::get('/low-stock', 'lowStock')->name('low-stock');
        Route::get('/transactions', 'transactions')->name('transactions');
        Route::get('/product/{product}', 'productStock')->name('product');
        Route::get('/movements', 'movements')->name('movements');
        Route::get('/summary', 'summary')->name('summary');
    });
    
    // ==================== DASHBOARD API ====================
    Route::prefix('dashboard')->name('dashboard.')->controller(DashboardApiController::class)->group(function () {
        Route::get('/stats', 'stats')->name('stats');
        Route::get('/weekly-sales', 'weeklySales')->name('weekly-sales');
        Route::get('/top-products', 'topProducts')->name('top-products');
        Route::get('/recent-transactions', 'recentTransactions')->name('recent-transactions');
    });
    
    // ==================== CATEGORY API ====================
    Route::prefix('categories')->name('categories.')->controller(CategoryApiController::class)->group(function () {
        Route::get('/all', 'all')->name('all');
        Route::get('/{category}/products', 'products')->name('products');
    });
    
    // ==================== SUPPLIER API ====================
    Route::prefix('suppliers')->name('suppliers.')->controller(SupplierApiController::class)->group(function () {
        Route::get('/all', 'all')->name('all');
        Route::get('/{supplier}/products', 'products')->name('products');
    });
    
    // ==================== VARIATION API ====================
    Route::prefix('variations')->name('variations.')->group(function () {
        Route::get('/type/{type}', [VariationController::class, 'getByType'])->name('by-type');
    });
    
    // ==================== ROLES API ====================
    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:manage-users')
        ->name('roles.index');
    Route::get('/roles/{role}/permissions', [RoleController::class, 'getPermissions'])
        ->middleware('permission:manage-users')
        ->name('roles.permissions');
    
    // ==================== TAXES API ====================
    Route::get('/taxes', [TaxController::class, 'index'])
        ->middleware('permission:manage-system')
        ->name('taxes.index');
    Route::get('/taxes/{tax}', [TaxController::class, 'show'])
        ->middleware('permission:manage-system')
        ->name('taxes.show');
});

/*
|--------------------------------------------------------------------------
| TEST ROUTES (For development only)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/test', function () {
        return view('test');
    });
}

/*
|--------------------------------------------------------------------------
| FALLBACK ROUTE
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isSupervisor()) {
            return redirect()->route('supervisor.dashboard');
        }

        if ($user->isCashier()) {
            return redirect()->route('cashier.pos.index');
        }
    }

    return redirect()->route('login');
});
