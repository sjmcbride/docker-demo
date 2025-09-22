<?php
// Database configuration
define('DB_HOST', 'postgres');
define('DB_NAME', 'smclab_auth');
define('DB_USER', 'postgres');
define('DB_PASS', 'secure_password_123');
define('SITE_NAME', 'demo2');

// Start session
session_start();

// Database connection
function getDbConnection() {
    try {
        $pdo = new PDO("pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['site']) && $_SESSION['site'] === SITE_NAME;
}

// Login user
function loginUser($username, $password) {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = ? AND (site = ? OR site = 'all')");
    $stmt->execute([$username, SITE_NAME]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['site'] = SITE_NAME;
        return true;
    }
    return false;
}

// Logout user
function logoutUser() {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>