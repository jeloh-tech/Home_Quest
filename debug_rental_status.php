<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\RentalApplication;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

// Simulate user authentication (replace with actual user ID)
$userId = 1; // Replace with the actual tenant user ID

echo "=== RENTAL STATUS DEBUG ===\n\n";

// Check active rental for user
$activeRental = Listing::where('tenant_id', $userId)
    ->where('status', 'rented')
    ->first();

if ($activeRental) {
    echo "Active Rental Found:\n";
    echo "- Listing ID: {$activeRental->id}\n";
    echo "- Title: {$activeRental->title}\n";
    echo "- Status: {$activeRental->status}\n";
    echo "- Tenant ID: {$activeRental->tenant_id}\n\n";

    // Check applications for this listing and user
    $applications = RentalApplication::where('tenant_id', $userId)
        ->where('listing_id', $activeRental->id)
        ->get();

    echo "Applications for this rental:\n";
    foreach ($applications as $app) {
        echo "- Application ID: {$app->id}\n";
        echo "  Status: {$app->status}\n";
        echo "  Created: {$app->created_at}\n";
        echo "  Cancelled At: " . ($app->cancelled_at ?? 'Not cancelled') . "\n\n";
    }
} else {
    echo "No active rental found for user ID: {$userId}\n\n";
}

// Check all applications for user
$allApplications = RentalApplication::where('tenant_id', $userId)->get();

echo "All applications for user:\n";
foreach ($allApplications as $app) {
    echo "- Application ID: {$app->id}\n";
    echo "  Listing ID: {$app->listing_id}\n";
    echo "  Status: {$app->status}\n";
    echo "  Created: {$app->created_at}\n";
    echo "  Cancelled At: " . ($app->cancelled_at ?? 'Not cancelled') . "\n\n";
}
