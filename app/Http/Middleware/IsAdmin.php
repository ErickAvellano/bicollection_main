<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user(); // Get the authenticated user
        
        // Directly check if the user type is 'admin'
        if ($user && $user->type === 'admin') {
            return $next($request);
        }

        // Redirect non-admin users to the standard dashboard
        return redirect()->route('dashboard')->with('error', 'Access denied. Admins only.');
    }
}
