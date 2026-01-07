<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * HTML tags that should be stripped from input
     */
    protected array $except = [
        'password',
        'password_confirmation',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            if (is_string($value) && !in_array($key, $this->except)) {
                // Strip HTML tags for security
                $value = strip_tags($value);

                // Remove potential XSS vectors
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);

                // Trim whitespace
                $value = trim($value);
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
