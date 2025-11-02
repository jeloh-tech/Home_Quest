<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking profile photo paths...\n\n";

try {
    $users = DB::table('users')
        ->whereNotNull('profile_photo_path')
        ->select('id', 'name', 'email', 'profile_photo_path')
        ->get();

    if ($users->count() > 0) {
        foreach ($users as $user) {
            echo "User ID: {$user->id}\n";
            echo "Name: {$user->name}\n";
            echo "Email: {$user->email}\n";
            echo "profile_photo_path: {$user->profile_photo_path}\n";
            echo "------------------------\n";
        }
    } else {
        echo "No users found with profile photos.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
