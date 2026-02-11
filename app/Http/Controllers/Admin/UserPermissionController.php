<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserPermissionController extends Controller
{
    /**
     * Display a listing of users with their permissions.
     */
    public function index(Request $request)
    {
        $query = User::with(['roleModel', 'directPermissions']);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }
        
        $users = $query->latest()->paginate(15);
        
        return view('admin.users-permissions.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::getAllGrouped();
        
        return view('admin.users-permissions.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,owner,tenant',
            'role_id' => 'nullable|exists:roles,id',
            'account_status' => 'required|in:active,pending,suspended',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        
        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'role_id' => $validated['role_id'],
            'account_status' => $validated['account_status'],
            'password' => Hash::make($validated['password']),
        ]);
        
        // Attach direct permissions if provided
        if (!empty($validated['permissions'])) {
            foreach ($validated['permissions'] as $permissionId) {
                $user->directPermissions()->attach($permissionId, ['granted' => true]);
            }
        }
        
        // Log activity
        ActivityLog::create([
            'log_name' => 'user',
            'description' => 'إنشاء مستخدم جديد: ' . $user->name,
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'properties' => ['user' => $user->only(['id', 'name', 'email', 'role'])],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->route('admin.users-permissions.index')
                         ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['roleModel.permissions', 'directPermissions']);
        
        // Get recent activity logs separately (not as relation to avoid collection issues)
        $recentActivityLogs = ActivityLog::where('causer_type', User::class)
                                         ->where('causer_id', $user->id)
                                         ->latest()
                                         ->take(10)
                                         ->get();
        
        // Collect all effective permissions with their details
        $allPermissionNames = $user->getAllPermissions();
        $allPermissions = Permission::whereIn('name', $allPermissionNames)->get();
        
        // Get role permissions
        $rolePermissions = $user->roleModel ? $user->roleModel->permissions : collect([]);
        
        // Debug: if no permissions found, check if there's an issue
        if ($allPermissions->isEmpty() && !empty($allPermissionNames)) {
            // Try to get permissions by name directly
            $allPermissions = Permission::all()->filter(function ($perm) use ($allPermissionNames) {
                return in_array($perm->name, $allPermissionNames);
            });
        }
        
        return view('admin.users-permissions.show', compact('user', 'allPermissions', 'rolePermissions', 'recentActivityLogs'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load(['roleModel.permissions', 'directPermissions']);
        $roles = Role::all();
        $permissions = Permission::getAllGrouped();
        
        // Get user's direct permissions with their grant status
        $userDirectPermissions = [];
        foreach ($user->directPermissions as $permission) {
            $userDirectPermissions[$permission->id] = (bool) $permission->pivot->granted;
        }
        
        // Get role permissions IDs for display
        $rolePermissionIds = [];
        if ($user->roleModel) {
            $rolePermissionIds = $user->roleModel->permissions->pluck('id')->toArray();
        }
        
        // Get all effective permissions (for display)
        $effectivePermissions = $user->getAllPermissions();
        
        return view('admin.users-permissions.edit', compact(
            'user', 
            'roles', 
            'permissions', 
            'userDirectPermissions',
            'rolePermissionIds',
            'effectivePermissions'
        ));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,owner,tenant',
            'role_id' => 'nullable|exists:roles,id',
            'account_status' => 'required|in:active,pending,suspended',
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'denied_permissions' => 'nullable|array',
            'denied_permissions.*' => 'exists:permissions,id',
        ]);
        
        // Don't allow changing super admin's role or status
        if ($user->isSuperAdmin()) {
            unset($validated['role'], $validated['account_status']);
        }
        
        $oldData = $user->only(['name', 'email', 'role', 'role_id', 'account_status']);
        
        // Update user data
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        
        if (!$user->isSuperAdmin()) {
            $user->role = $validated['role'];
            $user->account_status = $validated['account_status'];
        }
        
        $user->role_id = $validated['role_id'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        // Update direct permissions - clear old ones first
        $user->directPermissions()->detach();
        
        // Prepare sync data
        $syncData = [];
        if (!empty($validated['permissions'])) {
            foreach ($validated['permissions'] as $permissionId) {
                $syncData[$permissionId] = ['granted' => true];
            }
        }
        
        if (!empty($validated['denied_permissions'])) {
            foreach ($validated['denied_permissions'] as $permissionId) {
                $syncData[$permissionId] = ['granted' => false];
            }
        }
        
        // Sync all permissions at once
        if (!empty($syncData)) {
            $user->directPermissions()->sync($syncData);
        }
        
        // Clear cached relationships to force reload
        $user->unsetRelation('directPermissions');
        
        // Log activity
        ActivityLog::create([
            'log_name' => 'user',
            'description' => 'تعديل بيانات المستخدم: ' . $user->name,
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'properties' => [
                'old' => $oldData,
                'new' => $user->only(['name', 'email', 'role', 'role_id', 'account_status']),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->route('admin.users-permissions.index')
                         ->with('success', 'تم تحديث المستخدم بنجاح.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user)
    {
        // Prevent deleting super admin
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'لا يمكن حذف المدير الرئيسي.');
        }
        
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الحالي.');
        }
        
        $userName = $user->name;
        
        // Log activity before deletion
        ActivityLog::create([
            'log_name' => 'user',
            'description' => 'حذف المستخدم: ' . $userName,
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'properties' => ['deleted_user' => $user->only(['id', 'name', 'email', 'role'])],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        $user->delete();
        
        return redirect()->route('admin.users-permissions.index')
                         ->with('success', 'تم حذف المستخدم بنجاح.');
    }

    /**
     * Suspend a user account.
     */
    public function suspend(Request $request, User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'لا يمكن إيقاف المدير الرئيسي.');
        }
        
        $request->validate([
            'reason' => 'required|string|max:500',
            'duration' => 'nullable|integer|min:1|max:365',
        ]);
        
        $user->account_status = 'suspended';
        $user->suspension_reason = $request->reason;
        $user->suspended_at = now();
        $user->suspension_ends_at = $request->duration ? now()->addDays($request->duration) : null;
        $user->save();
        
        // Log activity
        ActivityLog::create([
            'log_name' => 'user',
            'description' => 'إيقاف حساب المستخدم: ' . $user->name,
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'properties' => [
                'reason' => $request->reason,
                'duration' => $request->duration,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return back()->with('success', 'تم إيقاف حساب المستخدم.');
    }

    /**
     * Activate a suspended user account.
     */
    public function activate(Request $request, User $user)
    {
        $user->account_status = 'active';
        $user->suspension_reason = null;
        $user->suspended_at = null;
        $user->suspension_ends_at = null;
        $user->save();
        
        // Log activity
        ActivityLog::create([
            'log_name' => 'user',
            'description' => 'تفعيل حساب المستخدم: ' . $user->name,
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return back()->with('success', 'تم تفعيل حساب المستخدم.');
    }

    /**
     * Show activity logs for a specific user.
     */
    public function activityLogs(User $user)
    {
        $activityLogs = ActivityLog::where('causer_type', User::class)
                                   ->where('causer_id', $user->id)
                                   ->latest()
                                   ->paginate(20);
        
        return view('admin.activity-logs.user', compact('user', 'activityLogs'));
    }
}

