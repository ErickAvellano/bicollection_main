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
        $query = $request->input('query');

        // Search only in the guide_title column
        $results = CustomerSupportGuide::where('guide_title', 'LIKE', "%$query%")->get();

        // Return the search results to the view
        return view('customersupport.search-results', compact('results', 'query'));
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
