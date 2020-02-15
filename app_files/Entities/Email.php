<?php

include_once('Entity.php');
include_once('Logger.php');


class Email extends Entity {
    const TABLE_NAME = 'emails';

    public function get($id) {
        $query = "SELECT * FROM ".Email::TABLE_NAME." where id=$id;";
        $stmt = $this->getDBResource()->prepare($query);
        $stmt->execute();

        $email = null;
        if ($stmt->rowCount()) {
            $email = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $email;
    }

    public function create($data) {
        $query = "INSERT INTO ".Email::TABLE_NAME." SET email=:email, phonebook_entry_id=:phonebook_entry_id;";
        $stmt = $this->getDBResource()->prepare($query);

        $data['email'] = htmlspecialchars(strip_tags($data['email']));
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":phonebook_entry_id", $data['phonebook_entry_id']);

        if (!$stmt->execute()) {
            throw new \Exception("The email ".$data['email']." could not be saved.");
        }
    }

    public function update($id, $data) {
        $query = "UPDATE ".Email::TABLE_NAME." SET email = :email WHERE id = :id";
        $stmt = $this->getDBResource()->prepare($query);

        $email = $this->get($id);

        if ($email) {
            if (isset($data['email']) && $email['email'] != $data['email']) {
                $email['email'] = htmlspecialchars(strip_tags($data['email']));
            }

            $stmt->bindParam(':email', $email['email']);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                return $this->get($id);
            }
        }

        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM ".Email::TABLE_NAME." WHERE id = :id";
        $stmt = $this->getDBResource()->prepare($query);

        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function findByPhonebookEntryId($id) {
        $query = "SELECT * FROM ".Email::TABLE_NAME." where phonebook_entry_id=$id;";
        $stmt = $this->getDBResource()->prepare($query);
        $stmt->execute();

        $email = [];
        if ($stmt->rowCount()) {
            $email = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $email;
    }
}