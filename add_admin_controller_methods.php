<?php
// Script to add admin controller methods

$filePath = 'app/Http/Controllers/Admin/AdminDashboardController.php';
$content = file_get_contents($filePath);

if ($content === false) {
    die("Could not read AdminDashboardController.php");
}

// Add the new methods before the closing brace
$search = "        return response()->json([
            'success' => true,
            'data' => $verificationData
        ]);
    }
}";
$replace = "        return response()->json([
            'success' => true,
            'data' => $verificationData
        ]);
    }

    /**
     * Show individual listing details for admin.
     */
    public function showListing(Listing $listing): View
    {
        $listing->load(['user']);

        // Get favorite and like data for the listing (similar to tenant view)
        $favoriteListingIds = [];
        $likedListingIds = [];
        $likeCounts = [];

        return view('admin.listing-details', compact('listing', 'favoriteListingIds', 'likedListingIds', 'likeCounts'));
    }

    /**
     * Ban a listing.
     */
    public function banListing(Listing $listing): RedirectResponse
    {
        // Update listing status to banned
        $listing->update([
            'status' => 'banned'
        ]);

        return redirect()->back()->with('success', 'Listing banned successfully.');
    }
}";
$replace = str_replace('}', "    }\n\n    /**\n     * Show individual listing details for admin.\n     */\n    public function showListing(Listing \$listing): View\n    {\n        \$listing->load(['user']);\n\n        // Get favorite and like data for the listing (similar to tenant view)\n        \$favoriteListingIds = [];\n        \$likedListingIds = [];\n        \$likeCounts = [];\n\n        return view('admin.listing-details', compact('listing', 'favoriteListingIds', 'likedListingIds', 'likeCounts'));\n    }\n\n    /**\n     * Ban a listing.\n     */\n    public function banListing(Listing \$listing): RedirectResponse\n    {\n        // Update listing status to banned\n        \$listing->update([\n            'status' => 'banned'\n        ]);\n\n        return redirect()->back()->with('success', 'Listing banned successfully.');\n    }\n}", $content);

if (file_put_contents($filePath, $replace) === false) {
    die("Could not write to AdminDashboardController.php");
}

echo "Controller methods added successfully!\n";
