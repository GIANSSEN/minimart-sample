<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$permissions)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (empty($permissions)) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user->isAdmin()) {
            return $next($request);
        }

        if (!$user->hasAnyPermission($permissions)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
