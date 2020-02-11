<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../Config/Database.php';
include_once '../../Entities/Phonebook.php';

$database = new Database();
$db = $database->getConnection();

$phonebook = new Phonebook($db);
$data = json_decode(file_get_contents("php://input"), true);


if (isset($data['id'])) {
    try {
        $delete_action = $phonebook->delete($data['id'], $data['cascade']);
        if ($delete_action) {  // TODO: Unhandled exception.
            http_response_code(200);
            echo json_encode(array("message" => "Phonebook was deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete phonebook: Check if it has phone numbers assigned."));
        }
    } catch (\Exception $e) {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to delete phonebook: ".$e->getMessage()));    
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to delete phonebook: The ID must be provided."));
}
