<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\CustomerImage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Crypt;
use App\Models\CustomerPayment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;




class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve the associated customer record
        $customer = Customer::where('customer_id', $user->user_id)->first();
        $address = CustomerAddress::where('customer_id', $customer->customer_id)->first();

        // Debugging: Directly return a response if $customer is null
        if (!$customer) {
            return response()->json(['error' => 'Customer not found.'], 404);
        }

        // Retrieve the customer payment record
        $customerPayment = CustomerPayment::where('customer_id', $customer->customer_id)->first();

        // Mask email and phone number
        $maskedEmail = $this->maskEmail($customer->email);
        $maskedPhone = $this->maskPhone($customer->contact_number);

        // Decrypt the GCash number if it exists
        $gcashNumber = null;
        if ($customerPayment && $customerPayment->account_type === 'GCash') {
            // Decrypt the account number
            try {
                $decryptedGcashNumber = Crypt::decryptString($customerPayment->account_number);
                // Mask the GCash number to show only the first 2 and last 3 digits
                if (strlen($decryptedGcashNumber) > 5) {
                    $gcashNumber = substr($decryptedGcashNumber, 0, 2) . str_repeat('*', strlen($decryptedGcashNumber) - 5) . substr($decryptedGcashNumber, -3);
                } else {
                    $gcashNumber = $decryptedGcashNumber; // If too short, just display it as is
                }
            } catch (\Exception $e) {
                Log::error('Decryption error:', ['error' => $e->getMessage()]);
            }
        }

        // Return the view with the $customer data and the GCash number
        return view('profile.myprofile', compact('customer', 'maskedEmail', 'maskedPhone', 'address', 'gcashNumber'));
}
    private function maskEmail($email)
    {
        $parts = explode("@", $email);
        $domain = $parts[1];
        $name = substr($parts[0], 0, 3); // Show the first 4 characters
        return $name . str_repeat('*', strlen($parts[0]) - 4) . '@' . $domain;
    }

    /**
     * Mask the phone number.
     */
    private function maskPhone($phone)
    {
        // Check if a phone number exists and is not empty
        if (!empty($phone) && strlen($phone) > 5) {
            // Mask the phone number if it's valid and has more than 5 characters
            return substr($phone, 0, 2) . str_repeat('*', strlen($phone) - 5) . substr($phone, -3);
        } elseif (!empty($phone)) {
            // If the phone number is less than 6 characters, return it without masking
            return $phone;
        } else {
            // If no phone number is found, return an empty string or null
            return '';
        }
    }


    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user exists
        if (!$user) {
            return Redirect::route('profile.edit')->withErrors(['User not found.']);
        }

        // Retrieve the related customer
        $customer = $user->customer;

        // Check if the customer exists
        if (!$customer) {
            return Redirect::route('profile.edit')->withErrors(['Customer not found.']);
        }

        // Update customer attributes (excluding email and contact number)
        $customer->username = $request->input('username');
        $customer->first_name = $request->input('first_name');
        $customer->last_name = $request->input('last_name');
        $customer->gender = $request->input('gender');

        // Save the changes to the customer
        $customer->save();

        // Update the username in the user table
        $user->username = $customer->username;
        $user->save();


        // Redirect back to the edit page with a success message
        return Redirect::route('profile.edit')->with('status', 'Profile updated successfully.');
    }
    public function updateField(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Retrieve the related customer
        $customer = $user->customer;

        // Get the field and new value from the request
        $field = $request->input('field');
        $value = $request->input('value');

        // Validate the field input
        if ($field === 'email') {
            // Validate email input
            $validator = Validator::make($request->all(), [
                'value' => 'required|email|unique:customer,email,' . $customer->id
            ]);

            // If validation fails, return errors
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            // Update the email field
            $customer->email = $value;

        } elseif ($field === 'contact_number') {
            // Validate phone number input (must be 10 digits)
            $validator = Validator::make($request->all(), [
                'value' => 'required|regex:/^[0-9]{11}$/'
            ]);

            // If validation fails, return errors
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            // Update the phone number field
            $customer->contact_number = $value;

        } else {
            // If the field is neither email nor contact_number, return an error
            return response()->json(['success' => false, 'message' => 'Invalid field. Received: ' . $field], 400);
        }

        // Save the updated customer profile
        $customer->save();

        // Return a success response
        return response()->json(['success' => true, 'message' => 'Field updated successfully.']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        DB::transaction(function () use ($user, $request) {
            if ($user->customerImage) {
                Storage::disk('public')->delete($user->customerImage->img_path);
            }

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        });

        return Redirect::to('/');
    }

    /**
     * Upload and update the user's profile picture.
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $customer = $user->customer; // Assuming you have this relationship

        if ($request->hasFile('profile_picture')) {
            // Generate a unique filename
            $fileName = time() . '.' . $request->profile_picture->extension();

            // Store the image in the profile directory under public disk
            $path = $request->file('profile_picture')->storeAs('profile', $fileName, 'public');

            // Save the image path in the database
            if ($customer->customerImage) {
                // If an image already exists, delete the old one
                Storage::disk('public')->delete($customer->customerImage->img_path);

                // Update with the new image path
                $customer->customerImage->update(['img_path' => $path]);
            } else {
                // Create a new record if no image exists
                CustomerImage::create([
                    'customer_id' => $customer->customer_id,
                    'img_path' => $path,
                ]);
            }

            return back()->with('status', 'Profile picture updated successfully.');
        }

        return back()->with('error', 'Please upload a valid image.');
    }
    public function showAddresses()
    {
         // Get the authenticated user
        $customer = Auth::user()->customer;

        // Retrieve the first address for this customer, or null if none exists
        $address = $customer->addresses()->first();

        // Pass the $address to the view
        return view('profile.myprofile', compact('customer', 'address'));
    }
    public function saveGcashNumber(Request $request)
    {
        // Validate the GCash number
        $request->validate([
            'gcashNumber' => 'required|string|max:20', // Adjust max length as necessary
        ]);

        // Encrypt the GCash number
        $encryptedGcashNumber = Crypt::encryptString($request->input('gcashNumber'));

        // Retrieve the authenticated customer
        $customer = Auth::user()->customer;

        // Update or create the customer payment record
        CustomerPayment::updateOrCreate(
            ['customer_id' => $customer->customer_id], // Find by customer_id
            ['account_type' => 'GCash', 'account_number' => $encryptedGcashNumber] // Store encrypted GCash number
        );

        return redirect()->back()->with('status', 'GCash number saved successfully!');
    }
    public function changePassword(Request $request)
    {
        // Ensure the user is authenticated
        $user = Auth::user(); // Get the currently authenticated user

        // Check if the user is null
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to be logged in to change your password.');
        }

        // Validate the input with custom messages
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed', // Require confirmation
        ], [
            'new_password.required' => 'The new password is required.',
            'new_password.min' => 'The new password must be at least 8 characters.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ]);

        // Check if the current password matches the authenticated user's password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update the user's password
        $user->password = Hash::make($request->input('new_password'));

        // Save the user
        if ($user->save()) {
            return redirect()->back()->with('status', 'Password changed successfully!');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to change password. Please try again.']);
        }
    }


}

