<?php
class Database {
    private $host = "localhost";
    private $db_name = "furniture_store";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            error_log("Database connection error: " . $exception->getMessage());
            die("Database connection failed. Please check your configuration.");
        }
        return $this->conn;
    }
}
?>