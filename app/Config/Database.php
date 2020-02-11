<?php

// include_once('../Utilities/Logger.php');

class Database {
    private $host = "172.26.0.2";
    private $db_name = "zipdev_test";
    private $username = "root";
    private $password = "test";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            // Logger::write('error', 'The connection to the database could not be made.');
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}