<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../Config/Database.php';
include_once '../../Entities/Phone.php';

$database = new Database();
$db = $database->getConnection();

$phone = new Phone($db);
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['first_name']) && isset($data['last_name']) && isset($data['phone_number']) && isset($data['phonebook_id'])) {
    if ($phone->create($data)) {
        http_response_code(201);
        echo json_encode(array("message" => "Phone was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create phone."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create phone. Data is incomplete."));
}
