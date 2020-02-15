<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../../Config/Database.php';
include_once '../../../Entities/Phonebook.php';

$database = new Database();
$db = $database->getConnection();

$Phonebook = new Phonebook($db);
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['name']) && isset($data['description'])) {
    try {
        $phonebook = $Phonebook->create($data);

        if ($phonebook) {
            http_response_code(201);
            echo json_encode(array(
                "status" => "OK",
                "code" => 201,
                "message" => "Phonebook created successfully.",
                "responseData" => $phonebook
            ));
        } else {
            http_response_code(503);
            echo json_encode(array(
                "status" => "NOK",
                "code" => 503,
                "message" => "Phonebook couldn't be created. Please, try again."
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
        "status" => "OK",
        "code" => 400,
        "message" => "Unable to create phonebook. Data is incomplete.",
        "responseData" => $data
    ));
}
