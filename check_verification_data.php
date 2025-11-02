<?php
require_once 'config.php';

$user_id = 19; // From the logs, this is the current user ID

try {
    // Check user verification data
    $stmt = $pdo->prepare("SELECT id, name, document_type, valid_id_path, valid_id_back_path, verification_status, verification_id FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h2>User Verification Data (ID: $user_id)</h2>";
    echo "<pre>";
    print_r($user);
    echo "</pre>";

    // Check if files exist
    if ($user['valid_id_path']) {
        $file_path = __DIR__ . '/storage/app/public/' . $user['valid_id_path'];
        echo "<p>Front ID file exists: " . (file_exists($file_path) ? 'YES' : 'NO') . "</p>";
        echo "<p>Front ID path: $file_path</p>";
    } else {
        echo "<p>No front ID path in database</p>";
    }

    if ($user['valid_id_back_path']) {
        $file_path = __DIR__ . '/storage/app/public/' . $user['valid_id_back_path'];
        echo "<p>Back ID file exists: " . (file_exists($file_path) ? 'YES' : 'NO') . "</p>";
        echo "<p>Back ID path: $file_path</p>";
    } else {
        echo "<p>No back ID path in database</p>";
    }

    // Check verification history
    $stmt = $pdo->prepare("SELECT * FROM verification_histories WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Verification History</h3>";
    if (count($history) > 0) {
        echo "<pre>";
        print_r($history);
        echo "</pre>";
    } else {
        echo "<p>No verification history found</p>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
