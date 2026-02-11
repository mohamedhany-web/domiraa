<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAnyPermission
{
    /**
     * Handle an incoming request.
     * User needs to have at least ONE of the specified permissions.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'غير مصرح لك بالوصول'], 403);
            }
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // Check if user is suspended
        if ($request->user()->isSuspended()) {
            auth()->logout();
            if ($request->expectsJson()) {
                return response()->json(['message' => 'تم إيقاف حسابك'], 403);
            }
            return redirect()->route('login')->with('error', 'تم إيقاف حسابك. يرجى التواصل مع الدعم.');
        }

        // Super admin bypasses all permission checks
        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }

        // Load user permissions relationships for better performance
        $user = $request->user();
        $user->loadMissing(['roleModel.permissions', 'directPermissions']);
        
        // Check if user has any of the permissions
        if ($user->hasAnyPermission($permissions)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'ليس لديك صلاحية للقيام بهذا الإجراء',
                'required_permissions' => $permissions
            ], 403);
        }

        return redirect()->back()->with('error', 'ليس لديك صلاحية للقيام بهذا الإجراء');
    }
}

