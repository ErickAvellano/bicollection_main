<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EnsureUserIsMerchant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user(); // This tells Intelephense $user is a User model instance
    
        if ($user && $user->isMerchant()) {
            return $next($request); // Allow access
        }

        // If not a merchant, redirect to dashboard or show error
        return redirect()->route('dashboard')->with('error', 'You do not have access to this section.');
    }
}
