<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;

class CustomerAddressController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'customer_id' => 'required|integer|exists:customer,customer_id',
            'house_street' => 'required|string|max:255',
            'selectedRegion' => 'required|string|max:100',
            'selectedProvince' => 'required|string|max:100',
            'selectedCity' => 'required|string|max:100',
            'selectedBarangay' => 'required|string|max:100',
            'postalcode' => 'required|string|max:20',
        ]);

        // Find the customer
        $customer = Customer::find($validatedData['customer_id']);

        if (!$customer) {
            return redirect()->back()->withErrors('Customer not found.');
        }

        // Update or create the address
        $customer->addresses()->updateOrCreate(
            ['customer_id' => $customer->customer_id], // condition
            [
                'house_street' => $validatedData['house_street'],
                'region' => $validatedData['selectedRegion'],
                'province' => $validatedData['selectedProvince'],
                'city' => $validatedData['selectedCity'],
                'barangay' => $validatedData['selectedBarangay'],
                'postalcode' => $validatedData['postalcode'],
            ]
        );

        return redirect()->back()->with('status', 'Address saved successfully!');
    }

}
