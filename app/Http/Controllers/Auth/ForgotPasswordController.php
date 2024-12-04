<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\PasswordResetMail;


class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user,email',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'This email is not registered in our system.',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $otp = Str::random(6);
            PasswordReset::create([
                'user_id' => $user->user_id,
                'email' => $user->email,
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);

            // Generate the reset URL
            $resetUrl = url(route('password.reset', ['otp' => $otp], false) . '?email=' . urlencode($user->email));

            // Send the email with the custom reset URL
            Mail::to($user->email)->send(new PasswordResetMail($resetUrl));

            return redirect()->route('password.request')
                ->with('status', 'We have emailed your password reset link!');
        } else {
            return back()->withErrors(['email' => 'The email address is not registered.']);
        }
    }

    public function showResetForm(Request $request , $otp)
    {
        $email = $request->query('email');
        // Pass the OTP and email to the view
        return view('auth.reset-password')->with([
            'otp' => $otp,
            'email' => $email,
        ]);
    }

    public function reset(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:user,email',
            'otp' => 'required|string|max:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $passwordReset = PasswordReset::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($passwordReset) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->password = Hash::make($request->password);
                $user->save();

                // Delete the password reset record
                $passwordReset->delete();

                // Return a JSON response for AJAX
                return response()->json([
                    'success' => true,
                    'message' => 'Password has been reset successfully.',
                ]);
            }
        }
        // Return an error response for AJAX
        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired OTP.',
        ]);
    }

}
