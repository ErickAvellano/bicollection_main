<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Add this line to import the DB facade
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\OtpVerificationMail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:user'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        // Generate a random username
        $username = User::generateRandomUsername();

        return User::create([
            'email' => $data['email'],
            'username' => $username,  // Assign the generated username
            'password' => Hash::make($data['password']),
            'type' => 'customer', // You can customize this as needed
        ]);
    }

    public function register(Request $request)
    {
        // Check if the email already exists in the users table
        $existingUser = User::where('email', $request->input('email'))->first();

        if ($existingUser) {
            // If the email exists, check if the user is already verified
            if ($existingUser->email_verified) {
                return redirect()->route('login')->withErrors([
                    'email' => 'This email is already registered. Please log in.',
                ]);
            } else {
                // If not verified, redirect to verification page
                return redirect()->route('verification.notice')
                                 ->with('status', 'This email is already registered but not verified. Please verify your account.');
            }
        }

        // Validate and create the new user if email does not exist
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $otp = Str::random(6);
        EmailVerification::create([
            'user_id' => $user->user_id, // Ensure this is set correctly
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10)
        ]);

        // Send verification email
        Mail::to($user->email)->send(new OtpVerificationMail($otp));

        return redirect()->route('verification.notice');
    }

    public function showVerificationForm()
    {
        return view('auth.verify-email');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|max:6',
        ]);

        $verification = EmailVerification::where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($verification) {
            Log::info('Verification found:', ['user_id' => $verification->user_id]);

            $user = User::find($verification->user_id);

            if ($user) {
                Log::info('User found:', ['user' => $user]);

                // Mark the email as verified
                $user->email_verified = 1;
                $user->save();

                // Check if the user is a customer or a merchant
                if ($user->isCustomer()) {
                    // Insert the verified user data into the customer table
                    DB::table('customer')->updateOrInsert(
                        ['user_id' => $user->user_id],
                        [
                            'customer_id' => $user->user_id,
                            'user_id' => $user->user_id,
                            'username' => $user->username,
                            'first_name' => '', // Replace with actual data if available
                            'last_name' => '',  // Replace with actual data if available
                            'email' => $user->email,
                            'contact_number' => null,  // Replace with actual data if available
                            'gender' => null,          // Replace with actual data if available
                            'date_of_birth' => null,   // Replace with actual data if available
                            'user_type' => $user->type,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                // If the user is a merchant, insert or update merchant data
                if ($user->isMerchant()) {
                    DB::table('merchant')->updateOrInsert(
                        ['user_id' => $user->user_id],
                        [
                            'merchant_id' => $user->user_id,
                            'user_id' => $user->user_id,
                            'username' => $user->username,
                            'firstname' => '', // Replace with actual data if available
                            'lastname' => '',  // Replace with actual data if available
                            'email' => $user->email,
                            'contact_number' => null, // Replace with actual data if available
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                // Delete the OTP after successful verification
                $verification->delete();

                return redirect()->route('login')->with('status', 'Email verified successfully.');
            } else {
                Log::warning('User not found for the provided user_id.');
                return back()->withErrors(['otp' => 'User not found for the provided OTP.']);
            }
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }
}
