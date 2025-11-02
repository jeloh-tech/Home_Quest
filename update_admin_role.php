<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$user = User::where('email', 'admin@gmail.com')->first();
if ($user) {
    $user->update(['role' => 'admin']);
    echo "Admin role updated to 'admin'\n";
} else {
    echo "Admin user not found\n";
}
