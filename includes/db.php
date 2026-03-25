<?php 
    class DB {
        public $pdo;

        public function __construct($db = "dagboek_jen", $user = "root", $pwd = "", $host = "localhost") {
            try {
                $this->pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        public function run($sql, $placeholders = null) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($placeholders); 
            return $stmt;
        }
    }
}
?>

