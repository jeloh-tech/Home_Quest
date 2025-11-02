<?php
// Simple image server to serve files from storage directory

// For CLI testing
if (php_sapi_name() === 'cli') {
    if (isset($argv[1])) {
        parse_str($argv[1], $_GET);
    }
}

$path = $_GET['path'] ?? '';

if (empty($path)) {
    if (php_sapi_name() === 'cli') {
        echo "Usage: php image-server.php \"path=valid-ids/filename.jpg\"\n";
        exit(1);
    }
    http_response_code(400);
    echo 'No path specified';
    exit;
}

// Sanitize the path to prevent directory traversal
$sanitizedPath = str_replace(['../', '..\\'], '', $path);

// Handle different directory structures
if (strpos($sanitizedPath, 'verification/') === 0) {
    // For verification directory (current)
    $fullPath = __DIR__ . '/../storage/app/public/' . $sanitizedPath;
} elseif (strpos($sanitizedPath, 'verification-ids/') === 0) {
    // For verification-ids directory
    $fullPath = __DIR__ . '/../storage/app/public/' . $sanitizedPath;
} elseif (strpos($sanitizedPath, 'verification_documents/') === 0) {
    // For verification_documents directory
    $fullPath = __DIR__ . '/../storage/app/public/' . $sanitizedPath;
} elseif (strpos($sanitizedPath, 'verification_docs/') === 0) {
    // For verification_docs directory
    $fullPath = __DIR__ . '/../storage/app/public/' . $sanitizedPath;
} elseif (strpos($sanitizedPath, 'valid-ids/') === 0) {
    // For valid-ids directory (legacy)
    $fullPath = __DIR__ . '/../storage/app/public/' . $sanitizedPath;
} else {
    // For other files in public storage
    $fullPath = __DIR__ . '/../storage/app/public/' . $sanitizedPath;
}

// Debug: log the requested path and full path
error_log("Requested path: " . $path);
error_log("Full path: " . $fullPath);
error_log("File exists: " . (file_exists($fullPath) ? 'yes' : 'no'));
error_log("Is readable: " . (is_readable($fullPath) ? 'yes' : 'no'));

// Check if file exists and is readable
if (!file_exists($fullPath)) {
    if (php_sapi_name() === 'cli') {
        echo "File not found: $fullPath\n";
        exit(1);
    }
    http_response_code(404);
    echo 'File not found: ' . $fullPath;
    exit;
}

if (!is_readable($fullPath)) {
    if (php_sapi_name() === 'cli') {
        echo "File not readable: $fullPath\n";
        exit(1);
    }
    http_response_code(403);
    echo 'File not readable';
    exit;
}

// Get the MIME type
$mimeTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'pdf' => 'application/pdf',
];

$extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
$mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

// For CLI testing, just output info
if (php_sapi_name() === 'cli') {
    echo "File: $fullPath\n";
    echo "MIME Type: $mimeType\n";
    echo "Size: " . filesize($fullPath) . " bytes\n";
    echo "Exists: " . (file_exists($fullPath) ? 'yes' : 'no') . "\n";
    echo "Readable: " . (is_readable($fullPath) ? 'yes' : 'no') . "\n";
    exit(0);
}

// Set headers and output the file
header('Content-Type: ' . $mimeType);
header('Content-Length: ' . filesize($fullPath));
header('Cache-Control: max-age=3600'); // Cache for 1 hour

readfile($fullPath);
