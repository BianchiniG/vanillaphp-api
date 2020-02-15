<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../../Config/Database.php';
include_once '../../../Entities/PhonebookEntry.php';


$database = new Database();
$db = $database->getConnection();

$phonebook_entry = new PhonebookEntry($db);
$data = json_decode(file_get_contents("php://input"), true);


try {
    $updated_phonebook_entry = $phonebook_entry->update($data['id'], $data);

    if ($updated_phonebook_entry) {
        http_response_code(200);
        echo json_encode(array(
            "status" => "OK",
            "code" => 200,
            "message" => "Phonebook entry updated successfully.",
            "responseData" => $updated_phonebook_entry
        ));
    } else {
        http_response_code(503);
        echo json_encode(array(
            "status" => "NOK",
            "code" => 503,
            "message" => "Phonebook entry couldn't be updated. Please, try again."
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
