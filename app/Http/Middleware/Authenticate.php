<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            // If the request expects a JSON response, return a JSON error message
            return response()->json(['error' => 'No Authorization'], 401)->send();
        }
    
        // For non-JSON requests, you might want to return a simple text response
        return abort(401, 'No Authorization');
    }
}
