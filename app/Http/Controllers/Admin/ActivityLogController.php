<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user'); // ✅ Use 'user' not 'causer'

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereNested(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('log_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id); // ✅ Use 'user_id' not 'causer_id'
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Pagination
        $perPage = $request->input('per_page', 20);
        $logs = $query->latest()->paginate($perPage);

        // Stats for cards
        $totalLogs = ActivityLog::count();
        $todayLogs = ActivityLog::whereDate('created_at', today())->count();
        $weekLogs = ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $activeUsers = User::where('status', 'active')->count();

        // For filter dropdown
        $usersList = User::orderBy('full_name')->get();

        return view('admin.activity-logs.index', compact(
            'logs',
            'totalLogs',
            'todayLogs',
            'weekLogs',
            'activeUsers',
            'usersList'
        ));
    }

    /**
     * Display the specified activity log.
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id); // ✅ Use 'user'

        // Decode JSON data if stored in properties
        $oldData = $log->properties['old'] ?? null;
        $newData = $log->properties['attributes'] ?? null;

        return view('admin.activity-logs.show', compact('log', 'oldData', 'newData'));
    }

    /**
     * Get activity log details as JSON (for modal).
     */
    public function getDetails($id)
    {
        try {
            $log = ActivityLog::with('user')->findOrFail($id);
            
            return response()->json([
                'id' => $log->id,
                'description' => $log->description,
                'log_name' => $log->log_name,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'created_at' => $log->created_at ? $log->created_at->format('M d, Y h:i:s A') : 'N/A',
                'updated_at' => $log->updated_at ? $log->updated_at->format('M d, Y h:i:s A') : 'N/A',
                'subject_type' => $log->subject_type,
                'subject_id' => $log->subject_id,
                'user' => $log->user ? [
                    'id' => $log->user->id,
                    'full_name' => $log->user->full_name,
                    'email' => $log->user->email,
                    'role' => $log->user->role,
                    'avatar_url' => $log->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($log->user->full_name) . '&background=667eea&color=fff'
                ] : null,
                'properties' => $log->properties
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Activity log not found'], 404);
        }
    }

    /**
     * Export activity logs as CSV.
     */
    public function export($format = 'csv')
    {
        $logs = ActivityLog::with('user')->latest()->get(); // ✅ Use 'user'

        $filename = 'activity-logs-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'User', 'Action', 'Description', 'IP Address', 'User Agent']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : 'N/A',
                    $log->user ? $log->user->full_name : 'System', // ✅ Use 'user'
                    $log->log_name ?? 'N/A',
                    $log->description,
                    $log->ip_address ?? 'N/A',
                    $log->user_agent ?? 'N/A'
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Remove the specified activity log.
     */
    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'Activity log deleted successfully.');
    }

    /**
     * Clear all activity logs.
     */
    public function clearAll()
    {
        ActivityLog::truncate();

        ActivityLog::create([
            'user_id' => Auth::id(), // ✅ Use 'user_id'
            'log_name' => 'clear_logs',
            'description' => 'Cleared all activity logs',
            'ip_address' => request()->ip()
        ]);

        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'All activity logs cleared successfully.');
    }
}
