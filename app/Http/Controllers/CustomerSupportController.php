<?php

namespace App\Http\Controllers;
use App\Models\CustomerSupportGuide;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class CustomerSupportController extends Controller
{
    public function landing()
    {
        return view('customersupport.landing');
    }
    public function search(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('query');

        // Search for guides based on the query
        $guide = CustomerSupportGuide::where('guide_title', 'LIKE', "%$query%")
                    ->orWhere('category', 'LIKE', "%$query%") // Optional: Search by category too
                    ->first(); // Get the first matching guide (or null if not found)

        // Check if there are any matches
        if (!$guide) {
            // Redirect back to the customersupport.support route with an error message
            return redirect()->route('customer.support')
                             ->with('error', 'Guide not found! We couldn\'t find a guide matching your search.');
        }


        // Return the results to the view
        return view('customersupport.search-results', compact('guide', 'query'));
    }


    public function autocomplete(Request $request)
    {
        // Get the search query
        $query = $request->input('query');

        // Search for matching guide titles
        $results = CustomerSupportGuide::where('guide_title', 'LIKE', "%$query%")
            ->limit(10) // Limit the number of suggestions
            ->get(['guide_title']); // Only fetch the guide_title field

        // Return the results as JSON
        return response()->json($results);
    }
    public function getSuggestions()
    {
        // Fetch 5 titles from the CustomerSupportGuide table
        $suggestions = CustomerSupportGuide::select('guide_title')->limit(3)->get();

        // Return as JSON
        return response()->json($suggestions);
    }
    public function create()
    {
        $user = Auth::user();
         // Check if the user is not an admins
        if ($user->type !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to access this page.');
        }
        return view('customersupport.store-guide');
    }

    public function store(Request $request)
    {
        // Validation rules
        $validationRules = [
            'guide_title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ];

        // Add validation for each step
        for ($i = 1; $i <= 10; $i++) {
            $validationRules["step_{$i}"] = 'required|string|max:255'; // Step title
            $validationRules["step_{$i}_description"] = 'nullable|string'; // Step description
            $validationRules["step_{$i}_has_image"] = 'nullable|file|image|max:2048'; // Image (optional)
        }

        $request->validate($validationRules);

        // Prepare base data
        $data = [
            'guide_title' => $request->guide_title,
            'category' => $request->category,
        ];

        // Create a temporary guide to get its ID for image naming
        $guide = CustomerSupportGuide::create($data);

        // Loop through steps to process titles, descriptions, and images
        for ($i = 1; $i <= 10; $i++) {
            $stepTitle = $request->input("step_{$i}");
            $stepDescription = $request->input("step_{$i}_description");
            $hasImage = 0; // Default to no image

            // Check if an image is uploaded
            if ($request->hasFile("step_{$i}_has_image")) {
                $imageFile = $request->file("step_{$i}_has_image");

                // Generate the file name using guide_id and step number
                $fileName = "{$guide->guide_id}_step_{$i}.jpg";

                // Save the image in the storage path
                $imageFile->storeAs('guide-images', $fileName, 'public');

                $hasImage = 1; // Set the flag to 1 if an image is uploaded
            }

            // Update the data array with step information
            $data["step_{$i}"] = $stepTitle;
            $data["step_{$i}_description"] = $stepDescription;
            $data["step_{$i}_has_image"] = $hasImage;
        }

        // Update the guide with step details
        $guide->update($data);

        return redirect()->route('customer-support-guide.create')->with('success', 'Guide created successfully!');
    }



}
