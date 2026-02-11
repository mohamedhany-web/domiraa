<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('causer', 'subject');
        
        // Apply filters
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id)
                  ->where('causer_type', User::class);
        }
        
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $activityLogs = $query->latest()->paginate(20);
        $users = User::select('id', 'name')->get();
        
        return view('admin.activity-logs.index', compact('activityLogs', 'users'));
    }

    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('causer', 'subject');
        
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Export activity logs to CSV.
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('causer', 'subject');
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $logs = $query->latest()->get();
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="activity_logs_' . now()->format('Y-m-d') . '.csv"',
        ];
        
        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add headers
            fputcsv($file, ['ID', 'التاريخ', 'نوع السجل', 'الوصف', 'المستخدم', 'IP', 'المتصفح']);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->log_name ?? 'عام',
                    $log->description,
                    $log->causer ? $log->causer->name : 'غير معروف',
                    $log->ip_address,
                    $log->user_agent,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear old activity logs.
     */
    public function clear(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30',
        ]);
        
        $count = ActivityLog::where('created_at', '<', now()->subDays($request->days))->delete();
        
        // Log this action
        ActivityLog::create([
            'log_name' => 'system',
            'description' => 'تم حذف ' . $count . ' سجل أنشطة قديم',
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return back()->with('success', 'تم حذف ' . $count . ' سجل قديم.');
    }
}
