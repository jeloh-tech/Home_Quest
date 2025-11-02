<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$listing = App\Models\Listing::find(40);
echo "Before update:\n";
echo "Status: " . $listing->status . "\n";
echo "Tenant ID: " . ($listing->tenant_id ?? 'null') . "\n";
echo "Lease Start Date: " . ($listing->lease_start_date ?? 'null') . "\n";

$listing->status = 'rented';
$listing->tenant_id = 21;
$listing->lease_start_date = '2025-09-30';
$listing->save();

$listing->refresh();
echo "\nAfter update:\n";
echo "Status: " . $listing->status . "\n";
echo "Tenant ID: " . ($listing->tenant_id ?? 'null') . "\n";
echo "Lease Start Date: " . ($listing->lease_start_date ?? 'null') . "\n";
