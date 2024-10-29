<?php

class Database {
    private $host = "127.0.0.1"; // Add port if needed, e.g., "104.236.9.250:3306"
    private $user = "root";
    private $db = "waapi";
    private $pwd = "Hamza786";
    private $conn = NULL;

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db;charset=utf8mb4",
                $this->user,
                $this->pwd,
                [
                    PDO::ATTR_TIMEOUT => 5,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
