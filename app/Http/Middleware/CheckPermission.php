<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
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
        
        // Check permissions
        foreach ($permissions as $permission) {
            if (!$user->hasPermission($permission)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'ليس لديك صلاحية للقيام بهذا الإجراء',
                        'required_permission' => $permission
                    ], 403);
                }
                
                return redirect()->back()->with('error', 'ليس لديك صلاحية للقيام بهذا الإجراء');
            }
        }

        return $next($request);
    }
}

