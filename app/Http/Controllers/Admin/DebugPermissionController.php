<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DebugPermissionController extends Controller
{
    /**
     * Debug user permissions
     */
    public function debug(Request $request, $userId = null)
    {
        $userId = $userId ?? $request->get('user_id', auth()->id());
        $user = User::with(['roleModel.permissions', 'directPermissions'])->find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $permissionName = $request->get('permission', 'dashboard.view');
        
        $data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'role_id' => $user->role_id,
                'is_super_admin' => $user->isSuperAdmin(),
            ],
            'role' => $user->roleModel ? [
                'id' => $user->roleModel->id,
                'name' => $user->roleModel->name,
                'display_name' => $user->roleModel->display_name,
                'permissions_count' => $user->roleModel->permissions->count(),
                'permissions' => $user->roleModel->permissions->pluck('name')->toArray(),
            ] : null,
            'direct_permissions' => [
                'granted' => $user->directPermissions->where('pivot.granted', true)->pluck('name')->toArray(),
                'denied' => $user->directPermissions->where('pivot.granted', false)->pluck('name')->toArray(),
                'total' => $user->directPermissions->count(),
            ],
            'all_permissions' => $user->getAllPermissions(),
            'has_permission_check' => [
                'permission' => $permissionName,
                'has_permission' => $user->hasPermission($permissionName),
            ],
        ];
        
        return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

