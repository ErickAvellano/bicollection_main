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
        $guideId = $request->input('id');

        // Fetch guide based on id or title
        if ($guideId) {
            $guide = CustomerSupportGuide::find($guideId);
        } else {
            $guide = CustomerSupportGuide::where('guide_title', 'LIKE', "%$query%")->first();
        }

        // Check if a guide exists
        if (!$guide) {
            return redirect()->back()->with('error', 'No guide found for your search.');
        }

        // Return the single guide to the view
        return view('customersupport.search-results', compact('guide'));
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
