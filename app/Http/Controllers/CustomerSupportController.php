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
        // Validation rules (hardcoded for 10 steps)
        $validationRules = [
            'guide_title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'step_1' => 'nullable|string|max:255',
            'step_1_description' => 'nullable|string',
            'step_1_has_image' => 'nullable|file|image|max:2048',
            'step_2' => 'nullable|string|max:255',
            'step_2_description' => 'nullable|string',
            'step_2_has_image' => 'nullable|file|image|max:2048',
            'step_3' => 'nullable|string|max:255',
            'step_3_description' => 'nullable|string',
            'step_3_has_image' => 'nullable|file|image|max:2048',
            'step_4' => 'nullable|string|max:255',
            'step_4_description' => 'nullable|string',
            'step_4_has_image' => 'nullable|file|image|max:2048',
            'step_5' => 'nullable|string|max:255',
            'step_5_description' => 'nullable|string',
            'step_5_has_image' => 'nullable|file|image|max:2048',
            'step_6' => 'nullable|string|max:255',
            'step_6_description' => 'nullable|string',
            'step_6_has_image' => 'nullable|file|image|max:2048',
            'step_7' => 'nullable|string|max:255',
            'step_7_description' => 'nullable|string',
            'step_7_has_image' => 'nullable|file|image|max:2048',
            'step_8' => 'nullable|string|max:255',
            'step_8_description' => 'nullable|string',
            'step_8_has_image' => 'nullable|file|image|max:2048',
            'step_9' => 'nullable|string|max:255',
            'step_9_description' => 'nullable|string',
            'step_9_has_image' => 'nullable|file|image|max:2048',
            'step_10' => 'nullable|string|max:255',
            'step_10_description' => 'nullable|string',
            'step_10_has_image' => 'nullable|file|image|max:2048',
        ];

        // Validate the request
        $request->validate($validationRules);

        // Prepare base data
        $data = [
            'guide_title' => $request->guide_title,
            'category' => $request->category,
        ];

        // Create a temporary guide to get its ID for image naming
        $guide = CustomerSupportGuide::create($data);

        // Process each step manually
        if ($request->step_1 || $request->step_1_description || $request->hasFile('step_1_has_image')) {
            $hasImage = 0;
            if ($request->hasFile('step_1_has_image')) {
                $imageFile = $request->file('step_1_has_image');
                $fileName = "{$guide->guide_id}_step_1.jpg";
                $imageFile->storeAs('guide-images', $fileName, 'public');
                $hasImage = 1;
            }
            $guide->update([
                'step_1' => $request->step_1,
                'step_1_description' => $request->step_1_description,
                'step_1_has_image' => $hasImage,
            ]);
        }

        if ($request->step_2 || $request->step_2_description || $request->hasFile('step_2_has_image')) {
            $hasImage = 0;
            if ($request->hasFile('step_2_has_image')) {
                $imageFile = $request->file('step_2_has_image');
                $fileName = "{$guide->guide_id}_step_2.jpg";
                $imageFile->storeAs('guide-images', $fileName, 'public');
                $hasImage = 1;
            }
            $guide->update([
                'step_2' => $request->step_2,
                'step_2_description' => $request->step_2_description,
                'step_2_has_image' => $hasImage,
            ]);
        }

        if ($request->step_3 || $request->step_3_description || $request->hasFile('step_3_has_image')) {
            $hasImage = 0;
            if ($request->hasFile('step_3_has_image')) {
                $imageFile = $request->file('step_3_has_image');
                $fileName = "{$guide->guide_id}_step_3.jpg";
                $imageFile->storeAs('guide-images', $fileName, 'public');
                $hasImage = 1;
            }
            $guide->update([
                'step_3' => $request->step_3,
                'step_3_description' => $request->step_3_description,
                'step_3_has_image' => $hasImage,
            ]);
        }

        if ($request->step_4 || $request->step_4_description || $request->hasFile('step_4_has_image')) {
            $hasImage = 0;
            if ($request->hasFile('step_4_has_image')) {
                $imageFile = $request->file('step_4_has_image');
                $fileName = "{$guide->guide_id}_step_4.jpg";
                $imageFile->storeAs('guide-images', $fileName, 'public');
                $hasImage = 1;
            }
            $guide->update([
                'step_4' => $request->step_4,
                'step_4_description' => $request->step_4_description,
                'step_4_has_image' => $hasImage,
            ]);
        }

        if ($request->step_5 || $request->step_5_description || $request->hasFile('step_5_has_image')) {
            $hasImage = 0;
            if ($request->hasFile('step_5_has_image')) {
                $imageFile = $request->file('step_5_has_image');
                $fileName = "{$guide->guide_id}_step_5.jpg";
                $imageFile->storeAs('guide-images', $fileName, 'public');
                $hasImage = 1;
            }
            $guide->update([
                'step_5' => $request->step_5,
                'step_5_description' => $request->step_5_description,
                'step_5_has_image' => $hasImage,
            ]);
        }

        if ($request->step_6 || $request->step_6_description || $request->hasFile('step_6_has_image')) {
            $hasImage = 0;
            if ($request->hasFile('step_6_has_image')) {
                $imageFile = $request->file('step_6_has_image');
                $fileName = "{$guide->guide_id}_step_6.jpg";
                $imageFile->storeAs('guide-images', $fileName, 'public');
                $hasImage = 1;
            }
            $guide->update([
                'step_6' => $request->step_6,
                'step_6_description' => $request->step_6_description,
                'step_6_has_image' => $hasImage,
            ]);
        }

        if ($request->step_7 || $request->step_7_description || $request->hasFile('step_7_has_image')) {
            $hasImage = 0;
            if ($request->hasFile('step_7_has_image')) {
                $imageFile = $request->file('step_7_has_image');
                $fileName = "{$guide->guide_id}_step_7.jpg";
                $imageFile->storeAs('guide-images', $fileName, 'public');
                $hasImage = 1;
            }
            $guide->update([
                'step_7' => $request->step_7,
                'step_7_description' => $request->step_7_description,
                'step_7_has_image' => $hasImage,
            ]);
        }

        if ($request->step_8 || $request->step_8_description || $request->hasFile('step_8_has_image')) {
            $hasImage = 0;
            if ($request->hasFile('step_8_has_image')) {
                $imageFile = $request->file('step_8_has_image');
                $fileName = "{$guide->guide_id}_step_8.jpg";
                $imageFile->storeAs('guide-images', $fileName, 'public');
                $hasImage = 1;
            }
            $guide->update([
                'step_8' => $request->step_8,
                'step_8_description' => $request->step_8_description,
                'step_8_has_image' => $hasImage,
            ]);
        }

        if ($request->step_9 || $request->step_9_description || $request->hasFile('step_9_has_image')) {
            $hasImage = 0;
            if ($request->hasFile('step_9_has_image')) {
                $imageFile = $request->file('step_9_has_image');
                $fileName = "{$guide->guide_id}_step_9.jpg";
                $imageFile->storeAs('guide-images', $fileName, 'public');
                $hasImage = 1;
            }
            $guide->update([
                'step_9' => $request->step_9,
                'step_9_description' => $request->step_9_description,
                'step_9_has_image' => $hasImage,
            ]);
        }

        if ($request->step_10 || $request->step_10_description || $request->hasFile('step_10_has_image')) {
            $hasImage = 0;
            if ($request->hasFile('step_10_has_image')) {
                $imageFile = $request->file('step_10_has_image');
                $fileName = "{$guide->guide_id}_step_10.jpg";
                $imageFile->storeAs('guide-images', $fileName, 'public');
                $hasImage = 1;
            }
            $guide->update([
                'step_10' => $request->step_10,
                'step_10_description' => $request->step_10_description,
                'step_10_has_image' => $hasImage,
            ]);
        }


        return redirect()->route('customersupport.store-guide')->with('success', 'Guide created successfully!');
    }




}
