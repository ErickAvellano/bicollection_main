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
    public function edit(Request $request): View
    {
        $user = Auth::user();

        $customer = Customer::where('customer_id', $user->user_id)->first();
        $address = CustomerAddress::where('customer_id', $customer->customer_id)->first();

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
                    $gcashNumber = $decryptedGcashNumber;
                }
            } catch (\Exception $e) {
               
            }
        }

        // Return the view with the $customer data and the GCash number
        return view('profile.myprofile', compact('customer', 'maskedEmail', 'maskedPhone', 'address', 'gcashNumber'));
}
    private function maskEmail($email)
    {
        $parts = explode("@", $email);
        $domain = $parts[1];
        $name = substr($parts[0], 0, 3); 
        return $name . str_repeat('*', strlen($parts[0]) - 4) . '@' . $domain;
    }

    /**
     * Mask the phone number.
     */
    private function maskPhone($phone)
    {
        // Check if a phone number exists and is not empty
        if (!empty($phone) && strlen($phone) > 5) {

            return substr($phone, 0, 2) . str_repeat('*', strlen($phone) - 5) . substr($phone, -3);
        } elseif (!empty($phone)) {
            
            return $phone;
        } else {
            return '';
        }
    }


    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        $user = Auth::user();
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
        
        $customer = $user->customer;

        $field = $request->input('field');
        $value = $request->input('value');

        if ($field === 'email') {
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
            // Validate phone number input 
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
            return response()->json(['success' => false, 'message' => 'Invalid field. Received: ' . $field], 400);
        }

        $customer->save();

        return response()->json(['success' => true, 'message' => 'Field updated successfully.']);
    }

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
        $customer = $user->customer; 

        if ($request->hasFile('profile_picture')) {
            // Generate a unique filename
            $fileName = time() . '.' . $request->profile_picture->extension();

            
            $path = $request->file('profile_picture')->storeAs('profile', $fileName, 'public');

           
            if ($customer->customerImage) {
              
                Storage::disk('public')->delete($customer->customerImage->img_path);

                // Update with the new image path
                $customer->customerImage->update(['img_path' => $path]);
            } else {
              
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
            'gcashNumber' => 'required|string|max:20',
        ]);

        // Encrypt the GCash number
        $encryptedGcashNumber = Crypt::encryptString($request->input('gcashNumber'));

        $customer = Auth::user()->customer;

        CustomerPayment::updateOrCreate(
            ['customer_id' => $customer->customer_id],
            ['account_type' => 'GCash', 'account_number' => $encryptedGcashNumber] 
        );

        return redirect()->back()->with('status', 'GCash number saved successfully!');
    }
    public function changePassword(Request $request)
    {
        $user = Auth::user(); 

        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to be logged in to change your password.');
        }

        // Validate the input 
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed', 
        ], [
            'new_password.required' => 'The new password is required.',
            'new_password.min' => 'The new password must be at least 8 characters.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->input('new_password'));

        // Save the user
        if ($user->save()) {
            return redirect()->back()->with('status', 'Password changed successfully!');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to change password. Please try again.']);
        }
    }


}

