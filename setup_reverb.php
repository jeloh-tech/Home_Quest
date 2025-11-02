<?php

// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Generate random keys for Reverb
$appId = 'local';
$appKey = 'local-' . bin2hex(random_bytes(16));
$appSecret = bin2hex(random_bytes(32));

// Read current .env content
$envPath = __DIR__ . '/.env';
$envContent = file_get_contents($envPath);

// Update or add Reverb configuration
$replacements = [
    'BROADCAST_CONNECTION=reverb' => 'BROADCAST_CONNECTION=reverb',
    'REVERB_APP_ID=' . $appId => 'REVERB_APP_ID=' . $appId,
    'REVERB_APP_KEY=' . $appKey => 'REVERB_APP_KEY=' . $appKey,
    'REVERB_APP_SECRET=' . $appSecret => 'REVERB_APP_SECRET=' . $appSecret,
    'REVERB_HOST="localhost"' => 'REVERB_HOST="localhost"',
    'REVERB_PORT=8081' => 'REVERB_PORT=8081',
    'REVERB_SCHEME=http' => 'REVERB_SCHEME=http',
    'VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"' => 'VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"',
    'VITE_REVERB_HOST="${REVERB_HOST}"' => 'VITE_REVERB_HOST="${REVERB_HOST}"',
    'VITE_REVERB_PORT="${REVERB_PORT}"' => 'VITE_REVERB_PORT="${REVERB_PORT}"',
    'VITE_REVERB_SCHEME="${REVERB_SCHEME}"' => 'VITE_REVERB_SCHEME="${REVERB_SCHEME}"',
];

// Add or update each line
foreach ($replacements as $search => $replace) {
    if (strpos($envContent, substr($search, 0, strpos($search, '='))) === false) {
        $envContent .= "\n" . $replace;
    } else {
        $envContent = preg_replace('/^' . preg_quote(substr($search, 0, strpos($search, '=')), '/') . '.*/m', $replace, $envContent);
    }
}

// Write back to .env
file_put_contents($envPath, $envContent);

echo "Reverb configuration updated in .env file:\n";
echo "REVERB_APP_ID=$appId\n";
echo "REVERB_APP_KEY=$appKey\n";
echo "REVERB_APP_SECRET=$appSecret\n";
echo "BROADCAST_CONNECTION=reverb\n";
echo "\nNext steps:\n";
echo "1. Run: php artisan reverb:start\n";
echo "2. Run: npm run dev (to rebuild assets)\n";
echo "3. Test real-time messaging\n";
