<?php
class Database {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO("mysql:host=localhost;dbname=rekenmachine", "root", "");
    }

    public function saveCalculation($expr, $res) {
        $stmt = $this->pdo->prepare("INSERT INTO calculations (expression, result) VALUES (?, ?)");
        $stmt->execute([$expr, $res]);
    }

    public function getCalculations() {
        return $this->pdo->query("SELECT * FROM calculations ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
