<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

echo "Checking user verification data...\n\n";

try {
    // Get the current authenticated user (if any)
    $user = Auth::user();

    if (!$user) {
        echo "No authenticated user found.\n";
        echo "Checking all users with verification data...\n\n";

        $users = DB::table('users')
            ->whereNotNull('valid_id_path')
            ->orWhereNotNull('valid_id_back_path')
            ->orWhereNotNull('document_type')
            ->get();

        if ($users->count() > 0) {
            foreach ($users as $user) {
                echo "User ID: {$user->id}\n";
                echo "Name: {$user->name}\n";
                echo "Email: {$user->email}\n";
                echo "valid_id_path: " . ($user->valid_id_path ?? 'NULL') . "\n";
                echo "valid_id_back_path: " . ($user->valid_id_back_path ?? 'NULL') . "\n";
                echo "document_type: " . ($user->document_type ?? 'NULL') . "\n";
                echo "verification_status: " . ($user->verification_status ?? 'NULL') . "\n";
                echo "------------------------\n";
            }
        } else {
            echo "No users found with verification data.\n";
        }
    } else {
        echo "Authenticated User Data:\n";
        echo "User ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "valid_id_path: " . ($user->valid_id_path ?? 'NULL') . "\n";
        echo "valid_id_back_path: " . ($user->valid_id_back_path ?? 'NULL') . "\n";
        echo "document_type: " . ($user->document_type ?? 'NULL') . "\n";
        echo "verification_status: " . ($user->verification_status ?? 'NULL') . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
