<?php

namespace App\Http\Controllers;
use App\Models\CustomerSupportGuide;
use Illuminate\Http\Request;

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


}
