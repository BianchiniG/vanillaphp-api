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
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->name) && !empty($data->description)) {
    $phonebook->setName($data->name);
    $phonebook->setDescription($data->description);
    
    if ($phonebook->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Phonebook was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create phonebook."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create phonebook. Data is incomplete."));
}
