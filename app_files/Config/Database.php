<?php

include_once '../Entities/Logger.php';

class Database {
    private $host = "database";
    private $db_name = "zipdev_test";
    private $username = "zipdev_test";
    private $password = "testing";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        } catch(PDOException $exception) {
            Logger::write('error', 'The connection to the database could not be made: '.$exception->getMessage());
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}