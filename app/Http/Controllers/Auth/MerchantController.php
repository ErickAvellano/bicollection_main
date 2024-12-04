<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shop;
use App\Models\ShopDesign;
use App\Models\EmailVerification;
use App\Models\Application; // Add this import for the Application model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Merchant;
use App\Models\MerchantMop;
class MerchantController extends Controller
{
    // Method to handle the first step of registration
    // Method to handle the first step of registration
    public function register(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'shopname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Log::info('Validation passed.', $validated);

        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Create a new user (merchant)
            $user = new User();
            $user->email = $validated['email'];
            $user->username = $validated['shopname'];
            $user->password = Hash::make($validated['password']);
            $user->type = 'merchant';
            $user->email_verified = false;

            if (!$user->save()) {
                throw new \Exception('Failed to create the user.');
            }

            Log::info('User created successfully.', ['user_id' => $user->user_id]);

            // Create a new merchant entry (Do not update merchant_id)
            $merchant = new Merchant();
            $merchant->merchant_id = $user->user_id;
            $merchant->user_id = $user->user_id; // Link to the created user
            $merchant->username = $validated['shopname'];
            $merchant->email = $validated['email'];
            $merchant->firstname = ''; // Set to blank initially
            $merchant->lastname = ''; // Set to blank initially
            $merchant->contact_number = ''; // Set to blank initially

            if (!$merchant->save()) {
                throw new \Exception('Failed to create the merchant entry.');
            }

            Log::info('Merchant created successfully.', ['merchant_id' => $merchant->merchant_id]);

            // Create a new shop entry (No update to merchant_id)
            $shop = new Shop();
            $shop->merchant_id = $user->user_id; // Use the created merchant ID
            $shop->shop_name = $validated['shopname'];
            $shop->registration_step = 'Step1';
            $shop->terms_accepted = false;
            $shop->verification_status = 'Pending';

            if (!$shop->save()) {
                throw new \Exception('Failed to create the shop.');
            }

            Log::info('Shop created successfully.', ['shop_id' => $shop->id]);

            // Generate a random OTP
            $otp = Str::random(6);

            // Save OTP for email verification
            $emailVerification = new EmailVerification();
            $emailVerification->user_id = $user->user_id;
            $emailVerification->otp = $otp;
            $emailVerification->expires_at = Carbon::now()->addMinutes(10);

            if (!$emailVerification->save()) {
                throw new \Exception('Failed to create email verification entry.');
            }

            Log::info('Email verification record created successfully.');

            // Send verification email
            Mail::send('emails.verification', ['otp' => $otp], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Email Verification Code');
            });

            Log::info('Verification email sent to ' . $user->email);

            // Commit the transaction
            DB::commit();

            return redirect()->route('verification.notice')
                            ->with('success', 'Registration successful. Please verify your email.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();

            Log::error('Registration failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Registration failed. Please try again later.']);
        }
    }


    // Method to handle the second step of registration
    public function handleSecondStep(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'dti_cert' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'business_permit' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'shop_street' => 'required|string|max:255',
            'selectedProvince' => 'required|string|max:100',
            'selectedCity' => 'required|string|max:100',
            'barangay' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'about_store' => 'required|string|max:500',
            'categories' => 'required|array|min:1',
        ]);

        // Check for the user and their shop
        $user = Auth::user();
        $shop = Shop::where('merchant_id', $user->user_id)->first();

        if ($shop) {
            // Handle file uploads
            if ($request->hasFile('dti_cert')) {
                $dtiCertPath = $request->file('dti_cert')->store('permits/dti');
            }

            if ($request->hasFile('business_permit')) {
                $businessPermitPath = $request->file('business_permit')->store('permits/business');
            }

            // Update the shop with the new data
            $shop->update([
                'shop_street' => $validated['shop_street'],
                'province' => $validated['selectedProvince'],
                'city' => $validated['selectedCity'],
                'barangay' => $validated['barangay'],
                'postal_code' => $validated['postal_code'],
                'description' => $validated['about_store'],
                'dti_cert_path' => $dtiCertPath ?? null,
                'business_permit_path' => $businessPermitPath ?? null,
                'categories' => $validated['categories'],  // Assuming categories is a JSON or array field
                'registration_step' => 'Step2',
            ]);
            // Create a new application entry
            Application::create([
                'merchant_id' => $user->user_id,
                'shop_id' => $shop->shop_id,
                'shop_name' => $shop->shop_name,
                'dti_cert_path' => $dtiCertPath ?? null, // Store the DTI file path
                'mayors_permit_path' => $businessPermitPath ?? null, // Store the Business Permit file path
                'about_store' => $validated['about_store'],
                'categories' => json_encode($validated['categories']), // Store categories as JSON string
                'shop_street' => $validated['shop_street'],
                'province' => $validated['selectedProvince'],
                'city' => $validated['selectedCity'],
                'barangay' => $validated['barangay'],
                'postal_code' => $validated['postal_code'],
            ]);


            return redirect()->route('merchant.thirdstep');  // Redirect to the third step
        }

        return back()->withErrors(['error' => 'Shop not found']);
    }

    // Handle the third step submission
    public function handleThirdStep(Request $request)
    {
        // Validate that the terms checkbox is checked
        $validated = $request->validate([
            'termsCheckbox' => 'accepted',
        ], [
            'termsCheckbox.accepted' => 'You must agree to the terms and conditions.',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Retrieve the user's shop
        $shop = Shop::where('merchant_id', $user->user_id)->first();

        if ($shop) {
            // Update the shop's registration step and terms acceptance
            $shop->update([
                'registration_step' => 'Completed',
                'terms_accepted' => true,
            ]);

            // Redirect to a success page or dashboard
            return redirect()->route('mystore')->with('success', 'Registration completed successfully!');
        }

        // Return an error if no shop found
        return back()->withErrors(['error' => 'Failed to complete the registration process.']);
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
                $user->email_verified = true;
                $user->save();

                // Insert the verified user data into the merchant table if needed
                DB::table('merchant')->updateOrInsert(
                    ['user_id' => $user->user_id],
                    [
                        'user_id' => $user->user_id,
                        'shop_name' => $user->username,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Delete the OTP after successful verification
                $verification->delete();

                return redirect()->route('login')->with('status', 'Email verified successfully.');
            } else {
                Log::warning('User not found for the provided user_id.');
                return back()->withErrors(['otp' => 'User not found for the provided OTP.']);
            }
        }

        Log::warning('Invalid or expired OTP: ' . $request->otp);
        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }
    public function showStore()
    {
        $user = Auth::user(); // Get the authenticated user

        // Find the shop related to the merchant user
        $shop = $user->shop; // Assuming there's a relationship between user and shop

        $merchant = $user->merchant;

        $merchantMop = MerchantMop::where('merchant_id', $merchant->merchant_id)->first();

        // Check if the shop exists
        if (!$shop) {
            return redirect()->back()->with('error', 'Shop not found.');
        }

        // Get the shop ID
        $shopId = $shop->shop_id;

        // Check if a shop design exists for this shop
        $shopDesign = ShopDesign::where('shop_id', $shopId)->first();

        // If no shop design exists, create a new one
        if (!$shopDesign) {
            $shopDesign = new ShopDesign();
            $shopDesign->shop_id = $shopId; // Set the shop_id
            $shopDesign->featuredProduct = ''; // Initialize if needed
            $shopDesign->save(); // Save the new shop design
        }

        // Get the shop design ID
        $shopDesignId = $shopDesign->shop_design_id;

        $display1 = $shopDesign->display1;
        $display2 = $shopDesign->display2;

        // Fetch all products related to the shop
        $products = Product::where('merchant_id', $user->user_id)->with('images', 'variations')->get();

        // Fetch featured products based on IDs
        $featuredProductIds = explode(',', $shopDesign->featuredProduct); // Convert comma-separated string to array
        $featuredProducts = Product::whereIn('product_id', $featuredProductIds)->get(); // Fetch featured products

        // Pass the necessary data to the view
        return view('merchant.mystore', compact('shop','merchant','merchantMop', 'shopId', 'shopDesignId', 'products', 'featuredProducts', 'featuredProductIds',  'display1', 'display2'));
    }
    public function updateDisplayImage(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',  // Ensure it's a valid image
            'displayPosition' => 'required|in:1,2'  // Ensure the display position is either 1 or 2
        ]);

        // Upload the new image and store the path
        $imagePath = $request->file('image')->store('merchant/ads', 'public');

        // Find the shop design entry associated with the logged-in user's shop
        $shopDesign = ShopDesign::where('shop_id', Auth::user()->shop->shop_id)->first();

        // If we're updating display1 (first image slot)
        if ($request->displayPosition == '1') {
            // Check if there is already an image in display1
            if ($shopDesign->display1 && Storage::exists($shopDesign->display1)) {
                // Delete the old image from storage
                Storage::delete($shopDesign->display1);
            }
            // Save the new image path to the database
            $shopDesign->display1 = $imagePath;
        }
        // If we're updating display2 (second image slot)
        elseif ($request->displayPosition == '2') {
            // Check if there is already an image in display2
            if ($shopDesign->display2 && Storage::exists($shopDesign->display2)) {
                // Delete the old image from storage
                Storage::delete($shopDesign->display2);
            }
            // Save the new image path to the database
            $shopDesign->display2 = $imagePath;
        }

        // Save the updated shop design record to the database
        $shopDesign->save();

        // Return a response or redirect (optional)
        return redirect()->back()->with('success', 'Display image updated successfully');
    }


    public function updateBackground(Request $request)
    {
        $user = Auth::user();
        $shop = $user->shop; // Assuming there's a relationship between user and shop

        // Validate the uploaded file
        $request->validate([
            'coverphoto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Allow only images and limit the size
        ]);

        if ($request->hasFile('coverphoto')) {
            // Delete the old cover photo if it exists
            if ($shop->coverphotopath && Storage::exists($shop->coverphotopath)) {
                Storage::delete($shop->coverphotopath);
            }

            // Store the new cover photo in the 'merchant/coverphoto' directory
            $coverPhotoPath = $request->file('coverphoto')->store('merchant/coverphoto');

            // Update the shop's cover photo path in the database (remove 'public/' if not needed)
            $shop->coverphotopath = $coverPhotoPath;
            $shop->save();
        }

        return redirect()->route('mystore')->with('success', 'Cover photo updated successfully.');
    }


    public function secondStep()
    {
        return view('merchant.secondstep');
    }
    // Display the third step form
    public function thirdStep()
    {
        return view('merchant.thirdstep');
    }
    public function customize()
    {
        return view('merchant.customize');
    }

    public function updateContactMop(Request $request)
    {
        // Validate the request data
        $request->validate([
            'contact_number' => 'required|string|max:20',
            'mop' => 'required|string',
            'gcash_qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update the merchant's contact number and MOP
        $shop = Auth::user()->shop;// Assuming the shop is linked to the authenticated user
        $shop->contact_number = $request->input('contact_number');
        $shop->mop = $request->input('mop');

        // Handle the GCash QR code file upload, if provided
        if ($request->hasFile('gcash_qr_code')) {
            $qrCodePath = $request->file('gcash_qr_code')->store('gcash_qr_codes', 'public');
            $shop->gcash_qr_code = $qrCodePath;
        }

        // Save the updates
        $shop->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    public function myProfile()
    {
        // Get the authenticated merchant
        $merchant = Auth::user();



        // Fetch the related shop for the merchant
        $shop = Shop::where('merchant_id', $merchant->user_id)->first();

        $merchantInfo = Merchant::where('merchant_id', $merchant->user_id)->first();

        // Fetch GCash and COD details from merchant_mop table
        $merchantMop = MerchantMop::where('merchant_id', $merchant->user_id)
            ->where('account_type', 'GCash')
            ->first();

        $codMop = MerchantMop::where('merchant_id', $merchant->user_id)
            ->where('account_type', 'COD')
            ->first();

        return view('merchant.myprofile', compact('merchant','merchantInfo', 'shop', 'merchantMop', 'codMop'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        $merchant = Merchant::where('user_id', $user->user_id)->first();
        $shop = Shop::where('merchant_id', $merchant->merchant_id ?? null)->first();

        Log::info('Merchant data:', ['merchant' => $merchant]);
        Log::info('Shop data:', ['shop' => $shop]);

        return view('profile.edit', compact('merchant', 'shop'));
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Merchant|null $merchant */
        $merchant = Merchant::where('user_id', $user->user_id)->first();

        if (!$merchant) {
            return redirect()->route('merchant.myProfile')->with('error', 'Merchant not found.');
        }

        $shop = $merchant->shops()->first();

        if (!$shop) {
            return redirect()->route('merchant.myProfile')->with('error', 'Shop not found.');
        }

        // Validate the incoming request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'description' => 'required|string|max:255', // For the Shop model
        ]);

        // Update merchant's first and last names
        $merchant->firstname = $request->input('first_name');
        $merchant->lastname = $request->input('last_name');
        $merchantSaved = $merchant->save();

        // Update the shop's description
        $shop->description = $request->input('description');
        $shopSaved = $shop->save();

        // Check if both Merchant and Shop updates were successful
        if ($merchantSaved && $shopSaved) {
            return redirect()->route('merchant.myProfile')->with('success', 'Profile updated successfully.');
        } else {
            return redirect()->route('merchant.myProfile')->with('error', 'Failed to update profile.');
        }
    }

    public function updateContactNumber(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve the merchant using the user_id of the authenticated user
        $merchant = Merchant::where('user_id', $user->user_id)->first();
        if (!$merchant) {
            return response()->json(['status' => 'error', 'message' => 'No merchant profile found.'], 404);
        }

        // Validate the contact number
        $request->validate([
            'contact_number' => 'required|string|max:20',
        ]);

        // Update the contact number
        $merchant->contact_number = $request->input('contact_number');
        if ($merchant->save()) {
            return response()->json(['status' => 'success', 'message' => 'Contact number updated successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update contact number.'], 500);
        }
    }
    public function updateEmail(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Retrieve the merchant using the user_id of the authenticated user
        $merchant = Merchant::where('user_id', $user->user_id)->first();
        if (!$merchant) {
            return response()->json(['status' => 'error', 'message' => 'No merchant profile found.'], 404);
        }

        // Validate the email
        $request->validate([
            'email' => 'required|email|max:255|unique:merchants,email,' . $merchant->merchant_id . ',merchant_id',
        ]);

        // Update the email in both User and Merchant models if needed
        $merchant->email = $request->input('email');
        $user->email = $request->input('email');

        $merchantSaved = $merchant->save();
        $userSaved = $user->save();

        if ($merchantSaved && $userSaved) {
            return response()->json(['status' => 'success', 'message' => 'Email updated successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to update email.'], 500);
        }
    }



    public function saveGcashDetails(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'gcash_account_name' => 'required|string|max:255',
            'gcash_number' => 'required|digits:11',
            'gcash_qr_code' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle QR code upload and save the file path
        $qrCodePath = null;
        if ($request->hasFile('gcash_qr_code')) {
            $qrCodePath = $request->file('gcash_qr_code')->store('merchant/merchant_mop', 'public');
        }

        // Get the authenticated merchant
        $merchant = Auth::user()->merchant;

        // Update or create GCash entry in the merchant_mop table
        MerchantMop::updateOrCreate(
            ['merchant_id' => $merchant->merchant_id, 'account_type' => 'GCash'],
            [
                'account_name' => $validated['gcash_account_name'],
                'account_number' => $validated['gcash_number'],
                'gcash_qr_code' => $qrCodePath, // Save file path
                'description' => 'GCash Payment',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'GCash details saved successfully!');
    }
    public function enableCod(Request $request)
    {
        try {
            $user = Auth::user(); // Get the authenticated user
            $merchantId = $user->user_id; // Get the merchant_id
            $action = $request->input('action');

            if ($action === 'enable') {
                // Check if a COD entry already exists
                $merchantMop = MerchantMop::where('merchant_id', $merchantId)
                    ->where('account_type', 'COD')
                    ->first();

                if ($merchantMop) {
                    // If COD entry exists, update it
                    $merchantMop->update([
                        'account_name' => $request->input('gcash_account_name'),
                        'gcash_number' => $request->input('gcash_number'),
                        'cod_terms_accepted' => 1, // Set terms accepted to 1
                        'updated_at' => now(),
                    ]);
                } else {
                    // Create a new COD entry
                    MerchantMop::create([
                        'merchant_id' => $merchantId,
                        'account_type' => 'COD',
                        'account_number'=> '',
                        'account_name' => $request->input('gcash_account_name'),
                        'gcash_number' => $request->input('gcash_number'),
                        'description' => 'Cash on Delivery Payment',
                        'cod_terms_accepted' => 1, // Set terms accepted to 1
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                return response()->json(['success' => 'COD enabled with GCash details successfully.']);
            }
            return response()->json(['error' => 'Invalid action.'], 400);
            }catch (\Exception $e) {
            Log::error('Error enabling/disabling COD: ' . $e->getMessage());
            return response()->json(['error' => 'Server error. Please try again later.'], 500);
        }

    }
    public function disableCod(Request $request)
    {
        try {
            $user = Auth::user(); // Get the authenticated user
            $merchantId = $user->user_id; // Get the merchant_id

            // Find the COD record
            $merchantMop = MerchantMop::where('merchant_id', $merchantId)
                ->where('account_type', 'COD')
                ->first();

            if ($merchantMop) {
                $merchantMop->delete(); // Delete the COD record
                return response()->json(['success' => 'COD has been disabled successfully.']);
            }

            return response()->json(['error' => 'COD entry not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error disabling COD: ' . $e->getMessage());
            return response()->json(['error' => 'Server error. Please try again later.'], 500);
        }
    }


}
