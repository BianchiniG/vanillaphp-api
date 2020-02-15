<?php

include_once('Entity.php');
include_once('Logger.php');


class Phone extends Entity {
    const TABLE_NAME = 'phones';

    public function get($id) {
        $query = "SELECT * FROM ".Phone::TABLE_NAME." where id=$id;";
        $stmt = $this->getDBResource()->prepare($query);
        $stmt->execute();

        $phone = null;
        if ($stmt->rowCount()) {
            $phone = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $phone;
    }

    public function create($data) {
        $query = "INSERT INTO ".Phone::TABLE_NAME." SET phone_number=:phone_number, phonebook_entry_id=:phonebook_entry_id;";
        $stmt = $this->getDBResource()->prepare($query);

        $data['phone_number'] = htmlspecialchars(strip_tags($data['phone_number']));
        $stmt->bindParam(":phone_number", $data['phone_number']);
        $stmt->bindParam(":phonebook_entry_id", $data['phonebook_entry_id']);

        if (!$stmt->execute()) {
            throw new \Exception("The phone number ".$data['phone_number']." could not be saved.");
        }
    }

    public function update($id, $data) {
        $query = "UPDATE ".Phone::TABLE_NAME." SET phone_number = :phone_number WHERE id = :id";
        $stmt = $this->getDBResource()->prepare($query);
        
        $phone = $this->get($id);
        
        if ($phone) {
            if (isset($data['phone_number']) && $phone['phone_number'] != $data['phone_number']) {
                $phone['phone_number'] = htmlspecialchars(strip_tags($data['phone_number']));
            }
            
            $stmt->bindParam(':phone_number', $phone['phone_number']);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                return $this->get($id);
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

    public function findByPhonebookEntryId($id) {
        $query = "SELECT * FROM ".Phone::TABLE_NAME." where phonebook_entry_id=$id;";
        $stmt = $this->getDBResource()->prepare($query);
        $stmt->execute();

        $phones = [];
        if ($stmt->rowCount()) {
            $phones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $phones;
    }
}