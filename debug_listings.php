<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Listing;
use Illuminate\Support\Facades\DB;

echo "Checking listings and their images...\n\n";

try {
    $listings = Listing::all();

    if ($listings->count() > 0) {
        foreach ($listings as $listing) {
            echo "Listing ID: {$listing->id}\n";
            echo "Title: {$listing->title}\n";
            echo "Images (raw): " . ($listing->getAttributes()['images'] ?? 'NULL') . "\n";
            echo "Images (casted): " . (is_array($listing->images) ? json_encode($listing->images) : $listing->images) . "\n";
            echo "Images type: " . gettype($listing->images) . "\n";

            if (is_array($listing->images)) {
                echo "Images array: " . json_encode($listing->images) . "\n";
                echo "Images count: " . count($listing->images) . "\n";
                if (count($listing->images) > 0) {
                    echo "First image: " . $listing->images[0] . "\n";
                    echo "First image exists: " . (file_exists(storage_path('app/public/' . $listing->images[0])) ? 'YES' : 'NO') . "\n";
                }
            }

            echo "------------------------\n";
        }
    } else {
        echo "No listings found.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
