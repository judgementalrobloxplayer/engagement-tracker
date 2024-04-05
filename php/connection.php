<?php
function openDB() {
    $host = 'sql.example.org'; //DB URL
    $dbname = 'engagement'; //DB Name
    $username = 'user'; //User
    $password = 'pass'; //Pass

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>