<?php

class WhatsappInfo {
    private $conn;

    public $id;
    public $PhoneNumber;
    public $Timestamp;
    public $Message;

    public function __construct($db) {
        // Check if the connection is valid
        if ($db !== null) {
            $this->conn = $db;
        } else {
            echo "Database connection is not established.";
            exit;  // Stop execution if no valid connection
        }
    }

    public function fetchAll() {
        $stmt = $this->conn->prepare('SELECT * FROM WhatsappInfo');
        $stmt->execute();
        return $stmt;
    }

    public function fetchOne() {
        $stmt = $this->conn->prepare('SELECT * FROM WhatsappInfo WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->PhoneNumber = $row['PhoneNumber'];
            $this->Message = $row['Message'];
            $this->Timestamp = $row['Timestamp'];
            return true;
        }

        return false;
    }

    public function postData() {
        $stmt = $this->conn->prepare('INSERT INTO WhatsappInfo (PhoneNumber, Message, Timestamp) VALUES (:PhoneNumber, :Message, :Timestamp)');
        $stmt->bindParam(':PhoneNumber', $this->PhoneNumber);
        $stmt->bindParam(':Message', $this->Message);
        $stmt->bindParam(':Timestamp', $this->Timestamp);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function putData() {
        $stmt = $this->conn->prepare('UPDATE WhatsappInfo SET PhoneNumber = :PhoneNumber, Message = :Message, Timestamp = :Timestamp WHERE id = :id');
        $stmt->bindParam(':PhoneNumber', $this->PhoneNumber);
        $stmt->bindParam(':Message', $this->Message);
        $stmt->bindParam(':Timestamp', $this->Timestamp);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete() {
        $stmt = $this->conn->prepare('DELETE FROM WhatsappInfo WHERE id = :id');
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
