<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../Config/Database.php';
include_once '../../Entities/Phonebook.php';


$database = new Database();
$db = $database->getConnection();

$phonebook = new Phonebook($db);
$data = json_decode(file_get_contents("php://input"));

$phonebook->setName($data->name);
$phonebook->setDescription($data->description);

if ($phonebook->update($data->id)) {
    http_response_code(200);
    echo json_encode(array("message" => "Phonebook was updated."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update phonebook."));
}
