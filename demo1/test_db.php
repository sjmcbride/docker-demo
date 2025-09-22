<?php
// Database test script
define('DB_HOST', 'postgres');
define('DB_NAME', 'smclab_auth');
define('DB_USER', 'smclab');
define('DB_PASS', 'secure_password_123');

echo "Testing database connection...\n";
echo "Host: " . DB_HOST . "\n";
echo "Database: " . DB_NAME . "\n";
echo "User: " . DB_USER . "\n";

try {
    $pdo = new PDO("pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connection successful!\n";

    // Test query
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    echo "✅ Found $count users in database\n";

} catch(PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";

    // Try to get more details
    echo "\nDebug info:\n";
    echo "Error code: " . $e->getCode() . "\n";
    echo "Error info: " . print_r($e->errorInfo ?? [], true) . "\n";
}
?>