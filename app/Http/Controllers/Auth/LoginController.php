<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function jsonLogin(Request $request)
    {
        // Validate the email domain first
        if (!str_ends_with($request->email, '@gmail.com') && !str_ends_with($request->email, '@bicol-u.edu.ph')) {
            return response()->json([
                'success' => false,
                'errorField' => 'email',
                'reason' => 'invalid_domain',  // Invalid email domain
            ]);
        }

        // Check if the email exists in the database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'errorField' => 'email',
                'reason' => 'not_found',  // Email not found in database
            ]);
        }

        // Attempt to log the user in
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'user' => $user->username,
                'redirect' => route('dashboard'),  // Return the dashboard URL
            ]);
        } else {
            // If credentials are incorrect
            return response()->json([
                'success' => false,
                'errorField' => 'password',  // Incorrect password
                'reason' => 'incorrect_password',
            ]);
        }
    }
}
