<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Ensure you are importing the correct User model

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user(); // Auth::user() returns the authenticated user

        if ($user && $user->isAdmin()) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }
}
