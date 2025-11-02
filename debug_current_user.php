<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VerificationHistory;

echo "Checking user ID 19 verification data...\n\n";

$user = User::find(19);

if (!$user) {
    echo "User not found.\n";
} else {
    echo "User Verification Data:\n";
    echo "User ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "document_type: " . ($user->document_type ?? 'NULL') . "\n";
    echo "valid_id_path: " . ($user->valid_id_path ?? 'NULL') . "\n";
    echo "valid_id_back_path: " . ($user->valid_id_back_path ?? 'NULL') . "\n";
    echo "verification_status: " . ($user->verification_status ?? 'NULL') . "\n";
    echo "verification_id: " . ($user->verification_id ?? 'NULL') . "\n";

    // Check verification history
    $history = VerificationHistory::where('user_id', 19)->get();
    echo "\nVerification History (" . $history->count() . " records):\n";
    foreach ($history as $record) {
        echo "- Action: {$record->action}, Status: {$record->new_status}, Date: {$record->created_at}\n";
        if ($record->document_type) {
            echo "  Document Type: {$record->document_type}\n";
        }
    }

    // Check if files exist
    if ($user->valid_id_path) {
        $file_path = storage_path('app/public/' . $user->valid_id_path);
        echo "\nFront ID file exists: " . (file_exists($file_path) ? 'YES' : 'NO') . "\n";
        echo "Front ID path: {$file_path}\n";
    }

    if ($user->valid_id_back_path) {
        $file_path = storage_path('app/public/' . $user->valid_id_back_path);
        echo "Back ID file exists: " . (file_exists($file_path) ? 'YES' : 'NO') . "\n";
        echo "Back ID path: {$file_path}\n";
    }
}
