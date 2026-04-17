<?php 
session_start();

class DB {
    public $pdo;

    public function __construct($db = "dagboek_jen", $user = "root", $pwd = "", $host = "localhost") {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pwd);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

public function run($sql, $placeholders = null) {
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($placeholders);
    return $stmt;
}
}

?>

