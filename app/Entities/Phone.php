<?php

include_once('Entity.php');
include_once('Logger.php');


class Phone extends Entity {
    const TABLE_NAME = 'phones';

    public function create($data) {
        $query = "INSERT INTO ".Phone::TABLE_NAME." SET first_name=:first_name, last_name=:last_name, phone_number=:phone_number, phonebook_id=:phonebook_id";
        $stmt = $this->getDBResource()->prepare($query);

        $phone = [];
        $phone['first_name'] = htmlspecialchars(strip_tags($data['first_name']));
        $phone['last_name'] = htmlspecialchars(strip_tags($data['last_name']));
        $phone['phone_number'] = htmlspecialchars(strip_tags($data['phone_number']));
        $phone['phonebook_id'] = htmlspecialchars(strip_tags($data['phonebook_id']));
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
        $query = "SELECT * FROM ".Phone::TABLE_NAME.";";

        try {
            $stmt = $this->getDBResource()->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (\Exception $e) {
            Logger::write('error', print_r($e, true));
        }
    }

    public function get($id) {
        $query = "SELECT * FROM ".Phone::TABLE_NAME." where id=$id;";
        $stmt = $this->getDBResource()->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update($id, $data) {
        $query = "UPDATE ".Phone::TABLE_NAME." SET first_name = :first_name, last_name = :last_name, phone_number = :phone_number, phonebook_id = :phonebook_id WHERE id = :id";
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
        $query = "DELETE FROM ".Phone::TABLE_NAME." WHERE id = :id";
        $stmt = $this->getDBResource()->prepare($query);

        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
