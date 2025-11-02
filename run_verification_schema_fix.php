<?php
require_once 'config.php';

try {
    // Use variables from config.php
    global $host, $dbname, $username, $password;

    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to database successfully.\n";

    // Read the SQL file
    $sql = file_get_contents('fix_verification_schema.sql');

    if (!$sql) {
        echo "Error: Could not read SQL file.\n";
        exit(1);
    }

    // Split the SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $statement) {
        if (!empty($statement)) {
            echo "Executing: " . substr($statement, 0, 50) . "...\n";
            $pdo->exec($statement);
            echo "✓ Statement executed successfully.\n";
        }
    }

    echo "\n=== VERIFICATION SCHEMA FIX COMPLETED ===\n";
    echo "All verification fields have been added to the users table.\n";

    // Verify the changes
    echo "\n=== VERIFYING CHANGES ===\n";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $verificationFields = [
        'verification_status',
        'verification_id',
        'document_type',
        'valid_id_path',
        'valid_id_back_path',
        'verification_notes',
        'verified_at'
    ];

    echo "Verification fields in users table:\n";
    foreach ($columns as $column) {
        if (in_array($column['Field'], $verificationFields)) {
            echo "✓ {$column['Field']} - {$column['Type']}\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
