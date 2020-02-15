<?php


class Entity {
    private $table = '';
    private $db_connection;

    public function __construct($db_connection) {
        $this->db_connection = $db_connection;
    }

    public function find($field, $value) {
        $query = "SELECT * FROM $this->table where $field=$value;";
        $stmt = $this->db_connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getDBResource() {
        return $this->db_connection;
    }
}
