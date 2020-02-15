<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../../Config/Database.php';
include_once '../../../Entities/PhonebookEntry.php';

$database = new Database();
$db = $database->getConnection();

$phonebook_entry = new PhonebookEntry($db);
$data = json_decode(file_get_contents("php://input"), true);


if (isset($data['id'])) {
    try {
        $deleted = $phonebook_entry->delete($data['id'], (isset($data['cascade']) ? $data['cascade'] : false));
    
        if ($deleted) {
            http_response_code(200);
            echo json_encode(array(
                "status" => "OK",
                "code" => 200,
                "message" => "Phonebook entry deleted successfully."
            ));
        } else {
            http_response_code(503);
            echo json_encode(array(
                "status" => "NOK",
                "code" => 503,
                "message" => "Phonebook entry couldn't be deleted. Please, try again."
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
} else {
    http_response_code(400);
    echo json_encode(array(
        "status" => "NOK",
        "code" => 400,
        "message" => "Phonebook entry couldn't be deleted (The id must be provided). Please, try again."
    ));
}
