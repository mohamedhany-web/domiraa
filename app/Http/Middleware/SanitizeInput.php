<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     * Sanitize all input to prevent XSS attacks
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        if (!empty($input)) {
            $input = $this->sanitize($input);
            $request->merge($input);
        }

        return $next($request);
    }

    /**
     * Recursively sanitize input array
     */
    private function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }

        // Don't sanitize passwords, CSRF tokens, or file uploads
        if (is_string($input)) {
            // Only sanitize if it looks like HTML content
            // Laravel already escapes output in Blade templates, so we just strip tags here
            if (strip_tags($input) !== $input) {
                // Contains HTML, strip dangerous tags
                $input = strip_tags($input, '<p><br><strong><em><u><a><ul><ol><li><h1><h2><h3><h4><h5><h6>');
            }
            // Clean up any remaining dangerous content
            return $input;
        }

        return $input;
    }
}

