<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


include_once '../../Config/Database.php';
include_once '../../Entities/Phonebook.php';

$database = new Database();
$db = $database->getConnection();

$phonebook = new Phonebook($db);

try {
    $phonebooks = $phonebook->read();
    if (count($phonebooks) > 0) {
        http_response_code(200);
        echo json_encode(array(
            "status" => "OK",
            "code" => 200,
            "message" => "Phonebook entries retrieved successfully.",
            "responseData" => $phonebooks
        ));
    } else {
        http_response_code(404);
        echo json_encode(array(
            "status" => "OK",
            "code" => 404,
            "message" => "No Phones found.",
            "responseData" => $phonebooks
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
