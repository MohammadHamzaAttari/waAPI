<?php

class Database {
    private $host = "104.236.9.250"; // Add port if needed, e.g., "104.236.9.250:3306"
    private $user = "xtazamxamd";
    private $db = "xtazamxamd";
    private $pwd = "5sGN8J3TqY";
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
