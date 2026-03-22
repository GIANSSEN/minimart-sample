<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\LabelController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Cashier\DashboardController as CashierDashboard;
use App\Http\Controllers\Cashier\POSController;
use App\Http\Controllers\Cashier\SalesController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboard;
use App\Http\Controllers\Supervisor\SalesController as SupervisorSalesController;
use App\Http\Controllers\Supervisor\CashManagementController;

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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // DASHBOARD
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [AdminDashboard::class, 'getStats'])->name('dashboard.stats');
    
    // USER MANAGEMENT
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
    Route::get('/users/pending', [UserController::class, 'pending'])->name('users.pending');
    Route::post('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
    
    // PRODUCT MANAGEMENT
    Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
    Route::get('/products/export/{format}', [ProductController::class, 'export'])->name('products.export');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::post('/products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');
    Route::get('/products/barcode/{barcode}', [ProductController::class, 'getByBarcode'])->name('products.barcode');
    Route::resource('products', ProductController::class);
    
    // CATEGORY MANAGEMENT
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    
    // SUPPLIER MANAGEMENT
    Route::resource('suppliers', SupplierController::class);
    Route::post('/suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');
    
    // REPORT ROUTES
    Route::prefix('reports')->name('reports.')->controller(ReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/sales', 'sales')->name('sales');
        Route::get('/products', 'products')->name('products');
        Route::get('/categories', 'categories')->name('categories');
        Route::get('/suppliers', 'suppliers')->name('suppliers');
        Route::get('/inventory', 'inventory')->name('inventory');
        Route::get('/profit-loss', 'profitLoss')->name('profit-loss');
        Route::get('/export/{type}/{format}', 'export')->name('export');
        Route::get('/sales/chart-data', 'salesChartData')->name('sales.chart');
        Route::get('/print/{type}', 'print')->name('print');
    });
    
    // IMPORT ROUTES
    Route::prefix('import')->name('import.')->controller(ImportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/products', 'importProducts')->name('products');
        Route::get('/template', 'downloadTemplate')->name('template');
        Route::get('/history', 'history')->name('history');
        Route::get('/export/{format}', 'export')->name('export');
    });
    
    // LABELS ROUTES
    Route::prefix('labels')->name('labels.')->controller(LabelController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/generate', 'generate')->name('generate');
        Route::get('/print/{id}', 'print')->name('print');
        Route::post('/print-multiple', 'printMultiple')->name('print.multiple');
        Route::get('/preview/{id}', 'preview')->name('preview');
    });
    
    // REGISTRATION (Users)
    Route::get('/registration', [UserController::class, 'create'])->name('registration');
    Route::get('/registration/list', [UserController::class, 'index'])->name('registration.list');
});

/*
|--------------------------------------------------------------------------
| SUPERVISOR ROUTES (Senior Cashier)
|--------------------------------------------------------------------------
*/
Route::prefix('supervisor')->name('supervisor.')->middleware(['auth', 'supervisor'])->group(function () {
    
    // DASHBOARD
    Route::get('/dashboard', [SupervisorDashboard::class, 'index'])->name('dashboard');
    
    // SALES MANAGEMENT (Voids & Refunds)
    Route::controller(SupervisorSalesController::class)->prefix('sales')->name('sales.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{sale}', 'show')->name('show');
        Route::post('/{sale}/void', 'void')->name('void');
        Route::post('/{sale}/refund', 'refund')->name('refund');
    });
    
    // CASH MANAGEMENT (Cash Drops & Shift Reports)
    Route::controller(CashManagementController::class)->prefix('cash')->name('cash.')->group(function () {
        Route::get('/drops', 'cashDrops')->name('drops');
        Route::get('/drops/create', 'createCashDrop')->name('create-drop');
        Route::post('/drops', 'storeCashDrop')->name('store-drop');
        Route::get('/shift-reports', 'shiftReports')->name('shift-reports');
        Route::post('/shift-report', 'getShiftReport')->name('get-shift-report');
    });
});

/*
|--------------------------------------------------------------------------
| CASHIER ROUTES (POS Only)
|--------------------------------------------------------------------------
*/
Route::prefix('cashier')->name('cashier.')->middleware(['auth', 'cashier'])->group(function () {
    
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
    Route::controller(SalesController::class)->prefix('sales')->name('sales.')->group(function () {
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
    
    // USER API
    Route::get('/users/{id}', [UserController::class, 'apiShow'])->name('users.show');
    
    // PRODUCT API
    Route::prefix('products')->name('products.')->controller(App\Http\Controllers\Api\ProductApiController::class)->group(function () {
        Route::get('/search', 'search')->name('search');
        Route::get('/barcode/{barcode}', 'getByBarcode')->name('barcode');
        Route::get('/low-stock', 'lowStock')->name('low-stock');
        Route::get('/category/{category}', 'byCategory')->name('category');
        Route::get('/recent', 'recent')->name('recent');
        Route::get('/all', 'all')->name('all');
    });
    
    // SALES API
    Route::prefix('sales')->name('sales.')->controller(App\Http\Controllers\Api\SaleApiController::class)->group(function () {
        Route::get('/today', 'today')->name('today');
        Route::get('/weekly', 'weekly')->name('weekly');
        Route::get('/monthly', 'monthly')->name('monthly');
        Route::get('/chart-data', 'chartData')->name('chart');
        Route::get('/summary', 'summary')->name('summary');
    });
    
    // STOCK API
    Route::prefix('stock')->name('stock.')->controller(App\Http\Controllers\Api\StockApiController::class)->group(function () {
        Route::get('/low-stock', 'lowStock')->name('low-stock');
        Route::get('/transactions', 'transactions')->name('transactions');
        Route::get('/product/{product}', 'productStock')->name('product');
        Route::get('/movements', 'movements')->name('movements');
        Route::get('/summary', 'summary')->name('summary');
    });
    
    // DASHBOARD API
    Route::prefix('dashboard')->name('dashboard.')->controller(App\Http\Controllers\Api\DashboardApiController::class)->group(function () {
        Route::get('/stats', 'stats')->name('stats');
        Route::get('/weekly-sales', 'weeklySales')->name('weekly-sales');
        Route::get('/top-products', 'topProducts')->name('top-products');
        Route::get('/recent-transactions', 'recentTransactions')->name('recent-transactions');
    });
    
    // CATEGORY API
    Route::prefix('categories')->name('categories.')->controller(App\Http\Controllers\Api\CategoryApiController::class)->group(function () {
        Route::get('/all', 'all')->name('all');
        Route::get('/{category}/products', 'products')->name('products');
    });
    
    // SUPPLIER API
    Route::prefix('suppliers')->name('suppliers.')->controller(App\Http\Controllers\Api\SupplierApiController::class)->group(function () {
        Route::get('/all', 'all')->name('all');
        Route::get('/{supplier}/products', 'products')->name('products');
    });
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
