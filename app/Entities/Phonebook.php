<?php

include_once('Entity.php');
include_once('Phone.php');
include_once('Logger.php');


class Phonebook extends Entity {
    const TABLE_NAME = 'phonebooks';

    public function create($data) {
        $query = "INSERT INTO ".Phonebook::TABLE_NAME." SET name=:name, description=:description";
        $stmt = $this->getDBResource()->prepare($query);

        $phonebook = [];
        $phonebook['name'] = htmlspecialchars(strip_tags($data['name']));
        $phonebook['description'] = htmlspecialchars(strip_tags($data['description']));
        $stmt->bindParam(":name", $phonebook['name']);
        $stmt->bindParam(":description", $phonebook['description']);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM ".Phonebook::TABLE_NAME.";";
        try {
            $stmt = $this->getDBResource()->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (\Exception $e) {
            Logger::write('error', print_r($e, true));
        }
    }

    public function get($id) {
        $query = "SELECT * FROM ".Phonebook::TABLE_NAME." where id=$id;";
        $stmt = $this->getDBResource()->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update($id, $data) {
        $query = "UPDATE ".Phonebook::TABLE_NAME." SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->getDBResource()->prepare($query);

        $phonebook = $this->get($id);

        if ($phonebook) {
            $phonebook = $phonebook->fetch(PDO::FETCH_ASSOC);
            if (isset($data['name']) && $phonebook['name'] != $data['name']) {
                $phonebook['name'] = htmlspecialchars(strip_tags($data['name']));
            }
            if (isset($data['description']) && $phonebook['description'] != $data['description']) {
                $phonebook['description'] = htmlspecialchars(strip_tags($data['description']));
            }
            
            $stmt->bindParam(':name', $phonebook['name']);
            $stmt->bindParam(':description', $phonebook['description']);
            $stmt->bindParam(':id', $id);
    
            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    public function delete($id, $cascade = false) {
        if ($cascade) {
            $this->clearPhonebook($id);
        }

        $query = "DELETE FROM ".Phonebook::TABLE_NAME." WHERE id = :phonebook_id";
        $stmt = $this->getDBResource()->prepare($query);
        $phonebook_id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(":phonebook_id", $phonebook_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function clearPhonebook($phonebook_id) {
        $query = "SELECT * FROM ".Phone::TABLE_NAME." WHERE phonebook_id = :phonebook_id;";
        $stmt = $this->getDBResource()->prepare($query);
        $stmt->bindParam(':phonebook_id', $phonebook_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $phone) {
                $query = "DELETE FROM ".Phone::TABLE_NAME." WHERE id = ".$phone['id'].";";
                $stmt = $this->getDBResource()->prepare($query);

                if (!$stmt->execute()) {
                    throw new \Exception("Phone ".$phone['phone_number']." (".$phone['id'].") could not be deleted. Action aborted.");
                }
            }
        }
    }
}
