<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $permissions = Permission::getAllGrouped();
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        $groups = Permission::distinct()->pluck('group')->filter()->toArray();
        return view('admin.permissions.create', compact('groups'));
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions|max:255|alpha_dash',
            'display_name' => 'required|max:255',
            'group' => 'nullable|max:100',
            'description' => 'nullable|max:500',
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'group' => $validated['group'] ?? $this->extractGroupFromName($validated['name']),
            'description' => $validated['description'] ?? null,
            'guard_name' => 'web',
        ]);

        // Log activity
        ActivityLog::create([
            'log_name' => 'permission',
            'description' => 'إنشاء صلاحية جديدة: ' . $permission->display_name,
            'subject_type' => Permission::class,
            'subject_id' => $permission->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'properties' => ['permission' => $permission->toArray()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'تم إنشاء الصلاحية بنجاح.');
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission)
    {
        $groups = Permission::distinct()->pluck('group')->filter()->toArray();
        return view('admin.permissions.edit', compact('permission', 'groups'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|alpha_dash|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|max:255',
            'group' => 'nullable|max:100',
            'description' => 'nullable|max:500',
        ]);

        $oldData = $permission->toArray();

        $permission->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'group' => $validated['group'] ?? $this->extractGroupFromName($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        // Log activity
        ActivityLog::create([
            'log_name' => 'permission',
            'description' => 'تعديل الصلاحية: ' . $permission->display_name,
            'subject_type' => Permission::class,
            'subject_id' => $permission->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'properties' => ['old' => $oldData, 'new' => $permission->fresh()->toArray()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'تم تحديث الصلاحية بنجاح.');
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Request $request, Permission $permission)
    {
        // Check if permission is used by any role
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف الصلاحية لأنها مرتبطة بأدوار.');
        }

        $permissionName = $permission->display_name;

        // Log activity before deletion
        ActivityLog::create([
            'log_name' => 'permission',
            'description' => 'حذف الصلاحية: ' . $permissionName,
            'subject_type' => Permission::class,
            'subject_id' => $permission->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'properties' => ['deleted_permission' => $permission->toArray()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $permission->delete();

        return redirect()->route('admin.permissions.index')
                         ->with('success', 'تم حذف الصلاحية بنجاح.');
    }

    /**
     * Extract group name from permission name (e.g., users.create -> users)
     */
    private function extractGroupFromName(string $name): ?string
    {
        $parts = explode('.', $name);
        return count($parts) > 1 ? $parts[0] : null;
    }
}
