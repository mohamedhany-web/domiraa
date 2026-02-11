<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class StorageHelper
{
    /**
     * Get public URL for a file in storage
     * Works correctly on both local and production servers
     * Always returns full domain URL
     * 
     * This method tries multiple approaches to get the correct URL:
     * 1. Check if symlink exists and use asset() helper
     * 2. Use request()->root() (most reliable in production)
     * 3. Use config('app.url')
     * 4. Use env('APP_URL')
     * 5. Fallback to relative path
     */
    public static function url(?string $path): string
    {
        if (!$path) {
            return self::placeholder();
        }

        // If it's already a full URL, return as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Remove leading slash
        $path = ltrim($path, '/');
        
        // Method 1: Use request()->root() (MOST RELIABLE in production)
        $baseUrl = null;
        try {
            if (function_exists('request')) {
                $request = request();
                if ($request) {
                    $baseUrl = $request->root();
                    if ($baseUrl && $baseUrl !== 'http://localhost' && $baseUrl !== 'http://127.0.0.1:8000') {
                        $url = rtrim($baseUrl, '/') . '/storage/' . $path;
                        $url = preg_replace('#([^:])//+#', '$1/', $url);
                        return $url;
                    }
                }
            }
        } catch (\Exception $e) {
            // Continue to next method
        }
        
        // Method 3: Use config('app.url') - works even with cached config
        try {
            $baseUrl = config('app.url');
            if ($baseUrl) {
                $baseUrl = rtrim($baseUrl, '/');
                // Skip localhost in production
                if (!app()->environment('production') || 
                    ($baseUrl !== 'http://localhost' && $baseUrl !== 'http://127.0.0.1:8000')) {
                    $url = $baseUrl . '/storage/' . $path;
                    $url = preg_replace('#([^:])//+#', '$1/', $url);
                    return $url;
                }
            }
        } catch (\Exception $e) {
            // Continue to next method
        }
        
        // Method 3: Try env('APP_URL') directly (only if config not cached)
        try {
            $envUrl = env('APP_URL');
            if ($envUrl) {
                $envUrl = rtrim($envUrl, '/');
                if ($envUrl !== 'http://localhost' && $envUrl !== 'http://127.0.0.1:8000') {
                    $url = $envUrl . '/storage/' . $path;
                    $url = preg_replace('#([^:])//+#', '$1/', $url);
                    return $url;
                }
            }
        } catch (\Exception $e) {
            // Continue to next method
        }
        
        // Method 4: Try to detect from $_SERVER (last resort) - only in web context
        try {
            if (isset($_SERVER['HTTP_HOST']) || isset($_SERVER['SERVER_NAME'])) {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? null;
                if ($host && $host !== 'localhost') {
                    $baseUrl = $protocol . $host;
                    $url = rtrim($baseUrl, '/') . '/storage/' . $path;
                    $url = preg_replace('#([^:])//+#', '$1/', $url);
                    return $url;
                }
            }
        } catch (\Exception $e) {
            // Continue to fallback
        }
        
        // Final fallback: Use relative path (works if symlink exists)
        $url = '/storage/' . $path;
        $url = preg_replace('#//+#', '/', $url);
        
        return $url;
    }
    
    /**
     * Get thumbnail URL
     */
    public static function thumbnailUrl(?string $path): string
    {
        if (!$path) {
            return self::placeholder();
        }

        // Try to find thumbnail path first
        $pathInfo = pathinfo($path);
        $thumbPath = $pathInfo['dirname'] . '/thumbnails/thumb_' . $pathInfo['basename'];
        
        // Check if thumbnail exists, if not use original
        if (Storage::disk('public')->exists($thumbPath)) {
            return self::url($thumbPath);
        }
        
        // Fallback to original image
        return self::url($path);
    }
    
    /**
     * Get placeholder image URL
     * Always returns full domain URL
     */
    public static function placeholder(): string
    {
        $placeholder = 'images/placeholder.jpg';
        
        // Get base URL - same logic as url() method
        $baseUrl = null;
        
        // Try config first (most reliable)
        try {
            $baseUrl = config('app.url');
            if ($baseUrl) {
                $baseUrl = rtrim($baseUrl, '/');
                // Skip localhost in production
                if (app()->environment('production') && 
                    ($baseUrl === 'http://localhost' || $baseUrl === 'http://127.0.0.1:8000')) {
                    $baseUrl = null;
                }
            }
        } catch (\Exception $e) {
            // Ignore
        }
        
        // Try request if available (most reliable in production)
        if (!$baseUrl || ($baseUrl === 'http://localhost' && app()->environment('production'))) {
            try {
                if (function_exists('request') && request()) {
                    $baseUrl = request()->root();
                }
            } catch (\Exception $e) {
                // Ignore
            }
        }
        
        // Try env if config didn't work
        if (!$baseUrl || ($baseUrl === 'http://localhost' && !app()->environment('production'))) {
            $envUrl = env('APP_URL');
            if ($envUrl) {
                $baseUrl = rtrim($envUrl, '/');
            }
        }
        
        // Final fallback
        if (!$baseUrl || ($baseUrl === 'http://localhost' && !app()->environment('production'))) {
            $baseUrl = 'http://127.0.0.1:8000';
        }
        
        $baseUrl = rtrim($baseUrl, '/');
        
        if (file_exists(public_path($placeholder))) {
            $url = $baseUrl . '/' . ltrim($placeholder, '/');
            return preg_replace('#([^:])//+#', '$1/', $url);
        }
        
        // SVG placeholder as fallback
        return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxOCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==';
    }
    
    /**
     * Check if file exists in storage
     */
    public static function exists(?string $path): bool
    {
        if (!$path) {
            return false;
        }
        
        $path = ltrim($path, '/');
        return Storage::disk('public')->exists($path);
    }
}

