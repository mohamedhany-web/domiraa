<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::getAllGrouped();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles|max:255|alpha_dash',
            'display_name' => 'required|max:255',
            'description' => 'nullable|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->attach($validated['permissions']);
        }

        // Log activity
        ActivityLog::create([
            'log_name' => 'role',
            'description' => 'إنشاء دور جديد: ' . $role->display_name,
            'subject_type' => Role::class,
            'subject_id' => $role->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'properties' => ['role' => $role->toArray()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.roles.index')
                         ->with('success', 'تم إنشاء الدور بنجاح.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::getAllGrouped();
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Prevent editing system roles
        if ($role->is_system) {
            return back()->with('error', 'لا يمكن تعديل أدوار النظام.');
        }

        $validated = $request->validate([
            'name' => 'required|max:255|alpha_dash|unique:roles,name,' . $role->id,
            'display_name' => 'required|max:255',
            'description' => 'nullable|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $oldData = $role->toArray();

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        // Log activity
        ActivityLog::create([
            'log_name' => 'role',
            'description' => 'تعديل الدور: ' . $role->display_name,
            'subject_type' => Role::class,
            'subject_id' => $role->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'properties' => ['old' => $oldData, 'new' => $role->fresh()->toArray()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.roles.index')
                         ->with('success', 'تم تحديث الدور بنجاح.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Request $request, Role $role)
    {
        // Prevent deleting system roles
        if ($role->is_system) {
            return back()->with('error', 'لا يمكن حذف أدوار النظام.');
        }

        // Prevent deleting role if it has users
        if ($role->users()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف الدور لأنه مرتبط بمستخدمين.');
        }

        $roleName = $role->display_name;

        // Log activity before deletion
        ActivityLog::create([
            'log_name' => 'role',
            'description' => 'حذف الدور: ' . $roleName,
            'subject_type' => Role::class,
            'subject_id' => $role->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
            'properties' => ['deleted_role' => $role->toArray()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $role->delete();

        return redirect()->route('admin.roles.index')
                         ->with('success', 'تم حذف الدور بنجاح.');
    }
}
