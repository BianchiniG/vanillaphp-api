<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


include_once '../../Config/Database.php';
include_once '../../Entities/PhonebookEntry.php';

$database = new Database();
$db = $database->getConnection();

$phonebook_entry = new PhonebookEntry($db);

try {
    $phonebook_entries = $phonebook_entry->read();
    if (count($phonebook_entries) > 0) {
        http_response_code(200);
        echo json_encode(array(
            "status" => "OK",
            "code" => 200,
            "message" => "Phonebook entries retrieved successfully.",
            "responseData" => $phonebook_entries
        ));
    } else {
        http_response_code(404);
        echo json_encode(array(
            "status" => "OK",
            "code" => 404,
            "message" => "No Phones found.",
            "responseData" => $phonebook_entries
        ));
    }
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        "status" => "NOK",
        "code" => 500,
        "message" => $e->getMessage(),
        "responseData" => $e
    ));
}
