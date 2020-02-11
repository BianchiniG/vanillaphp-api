<?php

include_once('Entity.php');
include_once('Phonebook.php');
include_once('Phone.php');
include_once('Email.php');
include_once('Logger.php');


class PhonebookEntry extends Entity {
    const TABLE_NAME = 'phonebook_entries';

    public function get($id) {
        $query = "SELECT * FROM ".PhonebookEntry::TABLE_NAME." where id=$id;";
        $stmt = $this->getDBResource()->prepare($query);
        $stmt->execute();

        $phonebook_entry = null;
        if ($stmt->rowCount()) {
            $phonebook = new Phonebook($this->getDBResource());
            $phone = new Phone($this->getDBResource());
            $email = new Email($this->getDBResource());

            $phonebook_entry = $stmt->fetch(PDO::FETCH_ASSOC);
            $phonebook_entry['phonebook'] = $phonebook->get($phonebook_entry['phonebook_id']);
            $phonebook_entry['phone_numbers'] = $phone->findByPhonebookEntryId($phonebook_entry['id']);
            $phonebook_entry['emails'] = $email->findByPhonebookEntryId($phonebook_entry['id']);
        }

        return $phonebook_entry;
    }

    public function create($data) {
        $query = "INSERT INTO ".PhonebookEntry::TABLE_NAME." SET first_name=:first_name, last_name=:last_name, phonebook_id=:phonebook_id";
        $stmt = $this->getDBResource()->prepare($query);

        $phone = [];
        $phone['first_name'] = htmlspecialchars(strip_tags($data['first_name']));
        $phone['last_name'] = htmlspecialchars(strip_tags($data['last_name']));
        $phone['phonebook_id'] = htmlspecialchars(strip_tags($data['phonebook_id']));
        $stmt->bindParam(":first_name", $phone['first_name']);
        $stmt->bindParam(":last_name", $phone['last_name']);
        $stmt->bindParam(":phonebook_id", $phone['phonebook_id']);

        if ($stmt->execute()) {
            $phonebook_entry_id = $this->getDBResource()->lastInsertId(PhonebookEntry::TABLE_NAME);

            try {
                $phone = new Phone($this->getDBResource());
                foreach ($phone_numbers as $phone_number) {
                    $phone->create(['phone_number' => $phone_number, 'phonebook_entry_id' => $phonebook_entry_id]);
                }

                $email_obj = new Email($this->getDBResource());
                foreach ($emails as $email) {
                    $email_obj->create(['email' => $email, 'phonebook_entry_id' => $phonebook_entry_id]);
                }
            } catch (\Exception $e) {
                $this->delete($phonebook_entry_id);
                throw new \Exception($e);
            }
            
            return $this->get($phonebook_entry_id);
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM ".PhonebookEntry::TABLE_NAME.";";

        try {
            $stmt = $this->getDBResource()->prepare($query);
            $stmt->execute();

            $entries = [];
            if ($stmt->rowCount()) {
                $phonebook = new Phonebook($this->getDBResource());
                $phone = new Phone($this->getDBResource());
                $email = new Email($this->getDBResource());

                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $phonebook_entry) {
                    $entry = [
                        'id' => $phonebook_entry['id'],
                        'first_name' => $phonebook_entry['first_name'],
                        'last_name' => $phonebook_entry['last_name'],
                        'created' => $phonebook_entry['created'],
                        'modified' => $phonebook_entry['modified'],
                        'phonebook' => $phonebook->get($phonebook_entry['phonebook_id']),
                        'phone_numbers' => $phone->findByPhonebookEntryId($phonebook_entry['id']),
                        'emails' => $email->findByPhonebookEntryId($phonebook_entry['id'])
                    ];

                    $entries []= $entry;
                }
            }

            return $entries;
        } catch (\Exception $e) {
            Logger::write('error', print_r($e, true));
            throw new \Exception($e);
        }
    }

    public function update($id, $data) {
        $query = "UPDATE ".PhonebookEntry::TABLE_NAME." SET first_name = :first_name, last_name = :last_name, phonebook_id = :phonebook_id WHERE id = :id";
        $stmt = $this->getDBResource()->prepare($query);

        $phone = $this->get($id);

        if ($phone) {
            if (isset($data['first_name']) && $phone['first_name'] != $data['first_name']) {
                $phone['first_name'] = htmlspecialchars(strip_tags($data['first_name']));
            }
            if (isset($data['last_name']) && $phone['last_name'] != $data['last_name']) {
                $phone['last_name'] = htmlspecialchars(strip_tags($data['last_name']));
            }
            if (isset($data['phonebook_id']) && $phone['phonebook_id'] != $data['phonebook_id']) {
                $phone['phonebook_id'] = htmlspecialchars(strip_tags($data['phonebook_id']));
            }

            $stmt->bindParam(':first_name', $phone['first_name']);
            $stmt->bindParam(':last_name', $phone['last_name']);
            $stmt->bindParam(':phonebook_id', $phone['phonebook_id']);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $phone = new Phone($this->getDBResource());
                foreach ($data['phone_numbers'] as $phone_number) {
                    $phone->update($phone_number['id'], ['phone_number' => $phone_number['phone_number'], 'phonebook_entry_id' => $id]);
                }

                $email_obj = new Email($this->getDBResource());
                foreach ($data['emails'] as $email) {
                    $email_obj->update($email['id'], ['email' => $email['email'], 'phonebook_entry_id' => $id]);
                }

                return $this->get($id);
            }
        }

        return false;
    }

    public function delete($id, $cascade = false) {
        if ($cascade) {
            $phonebook_entry = $this->get($id);
            Logger::write('debug', print_r($phonebook_entry, true));
            $phone = new Phone($this->getDBResource());
            foreach ($phonebook_entry['phone_numbers'] as $key => $phone_number) {
                if ($phone->delete($phone_number['id'])) {
                    unset($phonebook_entry['phone_numbers'][$key]);
                }
            }
            
            $email_obj = new Email($this->getDBResource());
            foreach ($phonebook_entry['emails'] as $key => $email) {
                if ($email_obj->delete($email['id'])) {
                    unset($phonebook_entry['emails'][$key]);
                }
            }
        }

        if (count($phonebook_entry['phone_numbers']) == 0 && count($phonebook_entry['emails']) == 0) {
            $query = "DELETE FROM ".PhonebookEntry::TABLE_NAME." WHERE id = :id";
            $stmt = $this->getDBResource()->prepare($query);
    
            $id = htmlspecialchars(strip_tags($id));
            $stmt->bindParam(':id', $id);
    
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } else {
            throw new \Exception("One or more relationships couldn't be deleted. Please, try again.");
        }
    }
}