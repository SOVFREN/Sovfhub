<?php
// Secure Database Connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO("mysql:host=localhost;dbname=bacefbae_sso_db", "bacefbae_dev", "Ax51Guj1988=", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to check if token is blacklisted
function isTokenBlacklisted($pdo, $token) {
    $stmt = $pdo->prepare("SELECT token FROM token_blacklist WHERE token = ?");
    $stmt->execute([$token]);
    return $stmt->fetch() ? true : false;
}

// Function to blacklist a token on logout
function blacklistToken($pdo, $token) {
    $stmt = $pdo->prepare("INSERT INTO token_blacklist (token, expires_at) VALUES (?, ?)");
    return $stmt->execute([$token, time() + 3600]); // Expire after 1 hour
}

