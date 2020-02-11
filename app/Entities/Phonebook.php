<?php

include_once('Entity.php');

class Phonebook extends Entity {
    private $table = 'phonebooks';

    private $id;
    private $name;
    private $description;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function create() {
        $query = "INSERT INTO $this->table SET name=:name, description=:description";
        $stmt = $this->getDBResource()->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM $this->table;";
        try {
            $stmt = $this->getDBResource()->prepare($query);
            $stmt->execute();
            Logger::write('debug', print_r($stmt, true));
            return $stmt;
        } catch (\Exception $e) {
            Logger::write('error', print_r($e, true));
        }
    }

    public function get($id) {
        $query = "SELECT * FROM $this->table where id=$id;";
        $stmt = $this->getDBResource()->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function update($id) {
        // TODO: Load the data beforehand to fill the non-updated fields.
        $query = "UPDATE $this->table SET name = :name, description = :description WHERE id = :id";

        $stmt = $this->getDBResource()->prepare($query);

        $this->setName(htmlspecialchars(strip_tags($this->getName())));
        $this->setDescription(htmlspecialchars(strip_tags($this->getDescription())));
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function delete($id, $cascade = false) {
        // TODO: Check if the user selected a cascade deletion.

        $query = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
