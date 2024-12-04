<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MerchantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            Log::error('No authenticated user found.');
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        // Log the user's attributes
        Log::info('Authenticated user details', ['user' => $user]);

        if ($user->type === 'merchant') {
            return $next($request);
        }

        Log::warning('Unauthorized access attempt', ['user_id' => $user->user_id, 'user_type' => $user->type ?? 'unknown']);
        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }
}
