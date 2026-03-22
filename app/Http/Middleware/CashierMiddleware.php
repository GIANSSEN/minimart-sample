<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isSupervisor() && !$user->isCashier()) {
            abort(403, 'Unauthorized access. Cashier only.');
        }

        return $next($request);
    }
}
