<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $action = null): Response
    {
        $response = $next($request);

        // Only log successful requests
        if ($response->isSuccessful() || $response->isRedirection()) {
            $user = $request->user();
            
            if ($user) {
                $action = $action ?? $this->determineAction($request);
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => $action,
                    'description' => $this->getDescription($request, $action),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        }

        return $response;
    }

    private function determineAction(Request $request): string
    {
        $method = $request->method();
        $routeName = $request->route()?->getName() ?? '';

        if (str_contains($routeName, 'store') || $method === 'POST') {
            return 'create';
        }

        if (str_contains($routeName, 'update') || $method === 'PUT' || $method === 'PATCH') {
            return 'update';
        }

        if (str_contains($routeName, 'destroy') || $method === 'DELETE') {
            return 'delete';
        }

        return 'view';
    }

    private function getDescription(Request $request, string $action): string
    {
        $routeName = $request->route()?->getName() ?? $request->path();
        
        $actions = [
            'create' => 'إنشاء',
            'update' => 'تحديث',
            'delete' => 'حذف',
            'view' => 'عرض',
        ];

        return ($actions[$action] ?? $action) . ' - ' . $routeName;
    }
}

