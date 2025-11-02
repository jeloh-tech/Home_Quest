<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "Testing Storage::disk('public')->exists() for verification files...\n\n";

$testPaths = [
    'verification_documents/14/front_test.jpg',
    'verification_documents/14/back_test.jpg',
    'verification/test_front_1.png',
    'verification/test_back_1.png',
    'verification-ids/front_22.jpg',
    'verification-ids/back_22.jpg'
];

foreach ($testPaths as $path) {
    $exists = Storage::disk('public')->exists($path);
    $fullPath = storage_path('app/public/' . $path);
    $fileExists = file_exists($fullPath);

    echo "Path: $path\n";
    echo "Storage::exists(): " . ($exists ? 'true' : 'false') . "\n";
    echo "file_exists(): " . ($fileExists ? 'true' : 'false') . "\n";
    if ($fileExists) {
        echo "File size: " . filesize($fullPath) . " bytes\n";
    }
    echo "------------------------\n";
}
