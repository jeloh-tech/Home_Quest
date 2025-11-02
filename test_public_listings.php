<?php

// Test script to verify publicListings method works
require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\Tenant\DashboardController;
use App\Models\Listing;

// Create a mock request
$request = new Request();

// Create controller instance
$controller = new DashboardController();

// Test the publicListings method
try {
    $response = $controller->publicListings($request);
    echo "publicListings method executed successfully!\n";
    echo "Response type: " . get_class($response) . "\n";

    // Check if it's a view response
    if (method_exists($response, 'getData')) {
        $data = $response->getData();
        echo "View data keys: " . implode(', ', array_keys($data)) . "\n";

        // Check if required data is present
        $requiredKeys = ['listings', 'favoriteListingIds', 'likedListingIds', 'likeCounts'];
        $missingKeys = array_diff($requiredKeys, array_keys($data));
        if (empty($missingKeys)) {
            echo "All required data keys are present!\n";
        } else {
            echo "Missing data keys: " . implode(', ', $missingKeys) . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error executing publicListings: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
