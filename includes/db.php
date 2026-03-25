<?php
// Database configuratie
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dagboek_jen');

// Verbinding maken
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Verbindingsfout: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database fout: " . $e->getMessage());
}

// Sessie starten
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
