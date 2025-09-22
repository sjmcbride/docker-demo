<?php
echo "<h2>Database Connection Debugging</h2>";

// Step 1: Check if PostgreSQL extension is available
echo "<h3>1. PHP PostgreSQL Support</h3>";
if (extension_loaded('pdo_pgsql')) {
    echo "✅ PDO PostgreSQL extension is loaded<br>";
} else {
    echo "❌ PDO PostgreSQL extension is NOT loaded<br>";
}

// Step 2: Check if we can resolve the hostname
echo "<h3>2. Network Connectivity</h3>";
$host = 'postgres';
$port = 5432;

echo "Attempting to connect to $host:$port...<br>";
$connection = @fsockopen($host, $port, $errno, $errstr, 10);
if ($connection) {
    echo "✅ Network connection to PostgreSQL container successful<br>";
    fclose($connection);
} else {
    echo "❌ Cannot connect to PostgreSQL container: $errstr ($errno)<br>";
}

// Step 3: Test database connection with different methods
echo "<h3>3. Database Connection Tests</h3>";

$configs = [
    ['host' => 'postgres', 'user' => 'postgres', 'pass' => 'secure_password_123', 'db' => 'smclab_auth'],
    ['host' => 'postgres', 'user' => 'postgres', 'pass' => 'secure_password_123', 'db' => 'postgres'],
    ['host' => 'localhost', 'user' => 'postgres', 'pass' => 'secure_password_123', 'db' => 'smclab_auth'],
];

foreach ($configs as $i => $config) {
    echo "<h4>Test " . ($i + 1) . ": {$config['user']}@{$config['host']}/{$config['db']}</h4>";

    try {
        $dsn = "pgsql:host={$config['host']};dbname={$config['db']}";
        echo "DSN: $dsn<br>";

        $pdo = new PDO($dsn, $config['user'], $config['pass'], [
            PDO::ATTR_TIMEOUT => 10,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        echo "✅ Connection successful!<br>";

        // Test a simple query
        $stmt = $pdo->query("SELECT version()");
        $version = $stmt->fetchColumn();
        echo "PostgreSQL version: $version<br>";

        // Check if our database exists
        $stmt = $pdo->query("SELECT datname FROM pg_database WHERE datname = 'smclab_auth'");
        $dbExists = $stmt->fetchColumn();
        if ($dbExists) {
            echo "✅ Database 'smclab_auth' exists<br>";
        } else {
            echo "❌ Database 'smclab_auth' does not exist<br>";
        }

        $pdo = null;
        break; // Stop on first successful connection

    } catch (PDOException $e) {
        echo "❌ Connection failed: " . $e->getMessage() . "<br>";
        echo "Error code: " . $e->getCode() . "<br>";
    }
    echo "<br>";
}

// Step 4: Environment check
echo "<h3>4. Environment Information</h3>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown' . "<br>";

// Step 5: Docker network info (if available)
echo "<h3>5. Network Information</h3>";
if (function_exists('gethostbyname')) {
    $ip = gethostbyname('postgres');
    echo "PostgreSQL container IP: $ip<br>";

    if ($ip !== 'postgres') {
        echo "✅ DNS resolution working<br>";
    } else {
        echo "❌ Cannot resolve 'postgres' hostname<br>";
    }
}

?>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3, h4 { color: #333; }
</style>