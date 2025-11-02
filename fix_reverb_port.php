<?php
// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Path to .env file
$envPath = __DIR__ . '/.env';

// Read current .env content
$envContent = file_get_contents($envPath);

// Add or update REVERB_SERVER_PORT=8081
$search = 'REVERB_SERVER_PORT';
if (strpos($envContent, $search) === false) {
    // Append if not exists
    $envContent .= "\nREVERB_SERVER_PORT=8081";
} else {
    // Replace existing line
    $envContent = preg_replace('/^' . preg_quote($search, '/') . '=.*/m', $search . '=8081', $envContent);
}

// Write back to .env
file_put_contents($envPath, trim($envContent));

echo "REVERB_SERVER_PORT=8081 has been added/updated in .env file.\n";
echo "Please restart the Reverb server with: php artisan reverb:start --port=8081\n";
?>
