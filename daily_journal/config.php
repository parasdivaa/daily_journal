<?php
session_start();

// Koneksi database
$host = 'localhost';
$dbname = 'daily_journal';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Cek login
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
}

// Cek admin
function checkAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        echo "<script>alert('Hanya admin!'); window.location='index.php';</script>";
        exit;
    }
}
?>