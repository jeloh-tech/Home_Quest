<?php
require_once 'config.php';

// Check current user verification data
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Query user data
    $stmt = $pdo->prepare("SELECT id, name, email, verification_status, document_type, valid_id_path, valid_id_back_path, verification_notes FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h2>Current User Verification Data:</h2>";
    echo "<pre>";
    print_r($user);
    echo "</pre>";

    // Check if files exist
    if ($user['valid_id_path']) {
        $file_path = __DIR__ . '/storage/app/public/' . $user['valid_id_path'];
        echo "<p>Front ID file exists: " . (file_exists($file_path) ? 'YES' : 'NO') . "</p>";
        echo "<p>Front ID path: " . $file_path . "</p>";
    }

    if ($user['valid_id_back_path']) {
        $file_path = __DIR__ . '/storage/app/public/' . $user['valid_id_back_path'];
        echo "<p>Back ID file exists: " . (file_exists($file_path) ? 'YES' : 'NO') . "</p>";
        echo "<p>Back ID path: " . $file_path . "</p>";
    }
} else {
    echo "<p>No user session found</p>";
}
?>
