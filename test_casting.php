<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Listing;

$listing = Listing::find(44); // EMEME listing
echo 'Raw images from DB: ' . $listing->getAttributes()['images'] . PHP_EOL;
echo 'Casted images: ' . (is_array($listing->images) ? 'ARRAY' : 'NOT ARRAY') . PHP_EOL;
echo 'Images type: ' . gettype($listing->images) . PHP_EOL;
if (is_array($listing->images)) {
    echo 'Images count: ' . count($listing->images) . PHP_EOL;
    echo 'First image: ' . $listing->images[0] . PHP_EOL;
} else {
    echo 'Images value: ' . $listing->images . PHP_EOL;
}
