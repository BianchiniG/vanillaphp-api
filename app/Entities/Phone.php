<?php

include_once('Entity.php');
include_once('Logger.php');


class Phone extends Entity {
    private $table = 'phones';

    private $first_name;
    private $last_name;
    private $phone_number;
    private $phonebook_id;
    private $created;
    private $modified;

    public function getFirstName() {
        return $this->first_name;
    }

    public function setFirstName($first_name) {
        $this->first_name = $first_name;
    }

    public function getLastName() {
        return $this->last_name;
    }

    public function setLastName($last_name) {
        $this->last_name = $last_name;
    }

    public function getPhoneNumber() {
        return $this->phone_number;
    }

    public function setPhoneNumber($phone_number) {
        $this->phone_number = $phone_number;
    }

    public function getPhonebookId() {
        return $this->phonebook_id;
    }

    public function setPhonebookId($phonebook_id) {
        $this->phonebook_id = $phonebook_id;
    }

    public function getCreated() {
        return $this->created;
    }

    private function setCreated($created) {
        $this->created = $created;
    }

    public function getModified() {
        return $this->modified;
    }

    public function setModified($modified) {
        $this->modified = $modified;
    }

    public function create($data) {
        $query = "INSERT INTO $this->table SET first_name=:first_name, last_name=:last_name, phone_number=:phone_number, phonebook_id=:phonebook_id";
        $stmt = $this->getDBResource()->prepare($query);

        $phone = [];
        $phone['first_name'] = htmlspecialchars(strip_tags($phone['first_name']));
        $phone['last_name'] = htmlspecialchars(strip_tags($phone['last_name']));
        $phone['phone_number'] = htmlspecialchars(strip_tags($phone['phone_number']));
        $phone['phonebook_id'] = htmlspecialchars(strip_tags($phone['phonebook_id']));
        $stmt->bindParam(":first_name", $phone['first_name']);
        $stmt->bindParam(":last_name", $phone['last_name']);
        $stmt->bindParam(":phone_number", $phone['phone_number']);
        $stmt->bindParam(":phonebook_id", $phone['phonebook_id']);

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

    public function update($id, $data) {
        $query = "UPDATE $this->table SET first_name = :first_name, last_name = :last_name, phone_number = :phone_number, phonebook_id = :phonebook_id WHERE id = :id";
        $stmt = $this->getDBResource()->prepare($query);

        $phone = $this->get($id);

        if ($phone) {
            $phone = $phone->fetch(PDO::FETCH_ASSOC);
            if (isset($data['first_name']) && $phone['first_name'] != $data['first_name']) {
                $phone['first_name'] = htmlspecialchars(strip_tags($data['first_name']));
            }
            if (isset($data['last_name']) && $phone['last_name'] != $data['last_name']) {
                $phone['last_name'] = htmlspecialchars(strip_tags($data['last_name']));
            }
            if (isset($data['phone_number']) && $phone['phone_number'] != $data['phone_number']) {
                $phone['phone_number'] = htmlspecialchars(strip_tags($data['phone_number']));
            }
            if (isset($data['phonebook_id']) && $phone['phonebook_id'] != $data['phonebook_id']) {
                $phone['phonebook_id'] = htmlspecialchars(strip_tags($data['phonebook_id']));
            }

            $stmt->bindParam(':first_name', $phone['first_name']);
            $stmt->bindParam(':last_name', $phone['last_name']);
            $stmt->bindParam(':phone_number', $phone['phone_number']);
            $stmt->bindParam(':phonebook_id', $phone['phonebook_id']);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                return true;
            }
        }

        return false;
    }

    public function delete($id) {
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
