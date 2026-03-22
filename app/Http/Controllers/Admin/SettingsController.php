<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        $settings = Setting::allAsArray();

        // Metadata for selects
        $timezones = \DateTimeZone::listIdentifiers();
        $dateFormats = [
            'Y-m-d' => date('Y-m-d'),
            'd/m/Y' => date('d/m/Y'),
            'm/d/Y' => date('m/d/Y'),
            'M d, Y' => date('M d, Y'),
            'jS F Y' => date('jS F Y'),
        ];
        $timeFormats = [
            'H:i'    => date('H:i') . ' (24h)',
            'H:i:s'  => date('H:i:s') . ' (24h with sec)',
            'h:i A'  => date('h:i A') . ' (12h)',
            'h:i:s A' => date('h:i:s A') . ' (12h with sec)',
        ];

        // Collect system information
        $systemInfo = $this->getSystemInfo();

        return view('Admin.Settings.index', compact('settings', 'systemInfo', 'timezones', 'dateFormats', 'timeFormats'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $group = $request->input('active_tab', 'general');

        $rules = [
            'active_tab' => 'required|string',
        ];

        // Dynamic validation based on group
        switch ($group) {
            case 'general':
                $rules += [
                    'store_name'    => 'required|string|max:100',
                    'store_email'   => 'nullable|email|max:150',
                    'store_phone'   => 'nullable|string|max:30',
                    'store_address' => 'nullable|string|max:255',
                    'store_website' => 'nullable|url|max:200',
                    'store_logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];
                break;
            case 'localization':
                $rules += [
                    'timezone'    => 'required|string',
                    'date_format' => 'required|string',
                    'time_format' => 'required|string',
                ];
                break;
            case 'currency':
                $rules += [
                    'currency_symbol'  => 'required|string|max:10',
                    'currency_code'    => 'required|string|max:10',
                    'decimal_places'   => 'required|integer|min:0|max:4',
                    'tax_mode'         => 'required|in:inclusive,exclusive',
                    'default_tax_rate' => 'required|numeric|min:0|max:100',
                ];
                break;
            case 'receipt':
                $rules += [
                    'receipt_header' => 'nullable|string|max:200',
                    'receipt_footer' => 'nullable|string|max:300',
                ];
                break;
            case 'inventory':
                $rules += [
                    'low_stock_threshold' => 'required|integer|min:1|max:9999',
                    'near_expiry_days'    => 'required|integer|min:1|max:365',
                ];
                break;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', $group);
        }

        // Handle Store Logo Upload
        if ($request->hasFile('store_logo')) {
            $file = $request->file('store_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            
            // Delete old logo if exists
            $oldLogo = Setting::get('store_logo');
            if ($oldLogo && file_exists(public_path('uploads/settings/' . $oldLogo))) {
                @unlink(public_path('uploads/settings/' . $oldLogo));
            }

            Setting::set('store_logo', $filename, 'general', 'image');
        }

        // Save other settings in the group
        $inputData = $request->except(['_token', 'active_tab', 'store_logo']);
        
        foreach ($inputData as $key => $value) {
            // Check if key exists in settings table for this group or in general
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                Setting::set($key, $value, $setting->group, $setting->type);
            }
        }

        // Handle checkboxes/boolean specifically if they are in the current group
        if ($group === 'receipt') {
            Setting::set('receipt_show_tax',     $request->has('receipt_show_tax') ? '1' : '0', 'receipt', 'boolean');
            Setting::set('receipt_show_barcode', $request->has('receipt_show_barcode') ? '1' : '0', 'receipt', 'boolean');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', ucfirst($group) . ' settings saved successfully!')
            ->with('active_tab', $group);
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            Setting::set('last_cache_cleared', now()->format('Y-m-d H:i:s'));

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully!',
                'time'    => now()->format('M d, Y h:i A'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test database connection.
     */
    public function testDb()
    {
        try {
            $start = microtime(true);
            DB::select('SELECT 1');
            $elapsed = round((microtime(true) - $start) * 1000, 2);

            return response()->json([
                'success'       => true,
                'message'       => 'Database connection successful!',
                'response_time' => $elapsed . ' ms',
                'database'      => DB::getDatabaseName(),
                'driver'        => DB::getDriverName(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Gather system information.
     */
    private function getSystemInfo(): array
    {
        $diskFree  = function_exists('disk_free_space')  ? disk_free_space('/')  : null;
        $diskTotal = function_exists('disk_total_space') ? disk_total_space('/') : null;

        $diskUsedPct = ($diskTotal && $diskFree)
            ? round((($diskTotal - $diskFree) / $diskTotal) * 100, 1)
            : null;

        // Safe stats (avoid slow queries)
        try { $totalProducts = Product::count(); } catch (\Exception $e) { $totalProducts = 0; }
        try { $totalUsers    = User::count();    } catch (\Exception $e) { $totalUsers    = 0; }
        try { $totalSales    = Sale::count();    } catch (\Exception $e) { $totalSales    = 0; }
        try { $lowStock      = Stock::whereRaw('quantity <= min_quantity')->count(); } catch (\Exception $e) { $lowStock = 0; }

        return [
            'php_version'      => PHP_VERSION,
            'laravel_version'  => app()->version(),
            'db_driver'        => DB::getDriverName(),
            'db_name'          => DB::getDatabaseName(),
            'server_software'  => $_SERVER['SERVER_SOFTWARE'] ?? php_uname('s') . ' ' . php_uname('r'),
            'server_name'      => php_uname('n'),
            'disk_free'        => $diskFree  ? $this->formatBytes($diskFree)  : 'N/A',
            'disk_total'       => $diskTotal ? $this->formatBytes($diskTotal) : 'N/A',
            'disk_used_pct'    => $diskUsedPct,
            'total_products'   => $totalProducts,
            'total_users'      => $totalUsers,
            'total_sales'      => $totalSales,
            'low_stock_count'  => $lowStock,
            'app_env'          => app()->environment(),
            'app_debug'        => config('app.debug') ? 'Enabled' : 'Disabled',
            'last_cache_clear' => Setting::get('last_cache_cleared', 'Never'),
            'timezone'         => config('app.timezone'),
        ];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return round($bytes / 1048576, 2) . ' MB';
        return round($bytes / 1024, 2) . ' KB';
    }
}
