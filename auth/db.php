<?php
/**
 * includes/db.php
 * Shared PDO database connection.
 * register.php already calls: require_once '../includes/db.php';
 * All other pages that need PDO should do the same.
 */

$host     = '127.0.0.1';
$dbname   = 'event_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
