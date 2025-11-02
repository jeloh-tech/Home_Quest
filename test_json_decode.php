<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Listing;

$listing = Listing::find(44);
$rawJson = $listing->getAttributes()['images'];

echo 'Raw JSON: ' . $rawJson . PHP_EOL;
echo 'JSON valid: ' . (json_decode($rawJson) !== null ? 'YES' : 'NO') . PHP_EOL;

$decoded = json_decode($rawJson, true);
echo 'Decoded type: ' . gettype($decoded) . PHP_EOL;
if (is_array($decoded)) {
    echo 'Decoded count: ' . count($decoded) . PHP_EOL;
    echo 'First item: ' . $decoded[0] . PHP_EOL;
}

// Test Laravel's casting manually
$casted = $listing->images;
echo 'Laravel casted: ' . (is_array($casted) ? 'ARRAY' : 'NOT ARRAY') . PHP_EOL;
echo 'Laravel casted type: ' . gettype($casted) . PHP_EOL;
echo 'Laravel casted value: ' . var_export($casted, true) . PHP_EOL;
