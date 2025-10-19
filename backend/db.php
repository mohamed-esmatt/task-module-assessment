<?php
class Database {
    private $host = "127.0.0.1"; // use 127.0.0.1 instead of localhost
    private $db_name = "task_module";  // your actual DB name
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};port=3306;dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch (PDOException $e) {
            echo json_encode(["message" => "Connection failed: " . $e->getMessage()]);
            exit;
        }
        return $this->conn;
    }
}
?>
