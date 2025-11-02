<?php
// Script to fix admin listings page link

$filePath = 'resources/views/admin/listings.blade.php';
$content = file_get_contents($filePath);

if ($content === false) {
    die("Could not read admin/listings.blade.php");
}

// Fix the View Details link
$search = '                                            <a href="#" class="flex-1 text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">View Details</a>';
$replace = '                                            <a href="{{ route(\'admin.listings.show\', $listing) }}" class="flex-1 text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">View Details</a>';

$content = str_replace($search, $replace, $content);

if (file_put_contents($filePath, $content) === false) {
    die("Could not write to admin/listings.blade.php");
}

echo "Admin listings page link fixed successfully!\n";
