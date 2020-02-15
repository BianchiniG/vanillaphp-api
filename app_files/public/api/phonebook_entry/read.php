<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


include_once '../../../Config/Database.php';
include_once '../../../Entities/PhonebookEntry.php';

$database = new Database();
$db = $database->getConnection();

$phonebook_entry = new PhonebookEntry($db);

$id = isset($_GET['id']) ? $_GET['id'] : null;

try {
    if ($id) {
        $entry = $phonebook_entry->get($id);
        if (count($entry)) {
            $code = 200;
            $message = "Phonebook engtry retrieved successfully.";
        } else {
            $code = 404;
            $message = "No phonebook entry found with id $id";
        }
        $responseData = $entry;
    } else {
        $phonebook_entries = $phonebook_entry->read();
        if (count($phonebook_entries)) {
            $code = 200;
            $message = "Phonebook entries retrieved successfully.";
        } else {
            $code = 404;
            $message = "No phonebook entries found.";
        }
        $responseData = $phonebook_entries;
    }

    http_response_code($code);
    echo json_encode(array(
        "status" => "OK",
        "code" => $code,
        "message" => $message,
        "responseData" => $phonebook_entries
    ));
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        "status" => "NOK",
        "code" => 500,
        "message" => $e->getMessage(),
        "responseData" => $e
    ));
}
