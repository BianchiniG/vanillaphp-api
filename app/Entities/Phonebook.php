<?php

include_once('Entity.php');
include_once('PhonebookEntry.php');
include_once('Logger.php');


class Phonebook extends Entity {
    const TABLE_NAME = 'phonebooks';

    public function get($id) {
        $query = "SELECT * FROM ".Phonebook::TABLE_NAME." where id=$id;";
        $stmt = $this->getDBResource()->prepare($query);
        $stmt->execute();

        $phonebook = null;
        if ($stmt->rowCount()) {
            $phonebook_entry = new PhonebookEntry($this->getDBResource());

            $phonebook = $stmt->fetch(PDO::FETCH_ASSOC);
            $phonebook['phonebook_entries'] = $phonebook_entry->findByPhonebookId($id);
        }

        return $phonebook;
    }

    public function create($data) {
        $query = "INSERT INTO ".Phonebook::TABLE_NAME." SET name=:name, description=:description";
        $stmt = $this->getDBResource()->prepare($query);

        $phonebook = [];
        $phonebook['name'] = htmlspecialchars(strip_tags($data['name']));
        $phonebook['description'] = htmlspecialchars(strip_tags($data['description']));
        $stmt->bindParam(":name", $phonebook['name']);
        $stmt->bindParam(":description", $phonebook['description']);

        if ($stmt->execute()) {
            $phonebook_id = $this->getDBResource()->lastInsertId(Phonebook::TABLE_NAME);

            try {
                if (isset($data['phonebook_entries'])) {
                    $PhonebookEntry = new PhonebookEntry($this->getDBResource());
                    foreach ($data['phonebook_entries'] as $phonebook_entry) {
                        $PhonebookEntry->create([
                            "first_name" => $phonebook_entry['first_name'],
                            "last_name" => $phonebook_entry['last_name'],
                            "phonebook_id" => $phonebook_id,
                            "phone_numbers" => $phonebook_entry['phone_numbers'],
                            "emails" => $phonebook_entry['emails']
                        ]);
                    }
                }
            } catch (\Exception $e) {
                $this->delete($phonebook_id, true);
                throw new \Exception($e);
            }

            return $this->get($phonebook_id);
        }

        return false;
    }

    public function read() {
        $query = "SELECT * FROM ".Phonebook::TABLE_NAME.";";
        try {
            $stmt = $this->getDBResource()->prepare($query);
            $stmt->execute();

            $phonebooks = [];
            if ($stmt->rowCount()) {
                $phonebook_entry = new PhonebookEntry($this->getDBResource());

                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $phonebook) {
                    $phonebook = [
                        'id' => $phonebook['id'],
                        'name' => $phonebook['name'],
                        'description' => $phonebook['description'],
                        'created' => $phonebook['created'],
                        'modified' => $phonebook['modified'],
                        'phonebook_entries' => $phonebook_entry->findByPhonebookId($phonebook['id'])
                    ];

                    $phonebooks []= $phonebook;
                }
            }

            return $phonebooks;
        } catch (\Exception $e) {
            Logger::write('error', print_r($e, true));
        }
    }

    public function update($id, $data) {
        Logger::write('debug', print_r($data, true));
        $query = "UPDATE ".Phonebook::TABLE_NAME." SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->getDBResource()->prepare($query);

        $phonebook = $this->get($id);

        if ($phonebook) {
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
                if (isset($data['phonebook_entries'])) {
                    $PhonebookEntry = new PhonebookEntry($this->getDBResource());
                    foreach ($data['phonebook_entries'] as $phonebook_entry) {
                        $PhonebookEntry->update($phonebook_entry['id'], $phonebook_entry);
                    }
                }

                return $this->get($id);
            }
        }
        return false;
    }

    public function delete($id, $cascade = false) {
        $phonebook = $this->get($id);
        if (!$phonebook) {
            throw new \Exception("There's no phonebook with id $id.");
        }

        if ($cascade) {
            $PhonebookEntry = new PhonebookEntry($this->getDBResource());
            if (isset($phonebook['phonebook_entries'])) {
                foreach ($phonebook['phonebook_entries'] as $key => $phonebook_entry) {
                    if ($PhonebookEntry->delete($phonebook_entry['id'], true)) {
                        unset($phonebook['phonebook_entries'][$key]);
                    }
                }
            }
        }

        if (count($phonebook['phonebook_entries']) == 0) {
            $query = "DELETE FROM ".Phonebook::TABLE_NAME." WHERE id = :phonebook_id";
            $stmt = $this->getDBResource()->prepare($query);
            $phonebook_id = htmlspecialchars(strip_tags($id));
            $stmt->bindParam(":phonebook_id", $phonebook_id);
    
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } else {
            throw new \Exception("One or more relationships couldn't be deleted. Please, try again.");
        }
    }
}
