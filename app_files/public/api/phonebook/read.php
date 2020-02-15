<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


include_once '../../../Config/Database.php';
include_once '../../../Entities/Phonebook.php';

$database = new Database();
$db = $database->getConnection();

$phonebook = new Phonebook($db);

$id = isset($_GET['id']) ? $_GET['id'] : null;

try {
    if ($id) {
        $phonebook = $phonebook->get($id);
        if (count($phonebook)) {
            $code = 200;
            $message = "Phonebook retrieved successfully.";
        } else {
            $code = 404;
            $message = "No phonebook found with id $id";
        }
        $responseData = $phonebook;
    } else {
        $phonebooks = $phonebook->read();
        if (count($phonebooks)) {
            $code = 200;
            $message = "Phonebook retrieved successfully.";
        } else {
            $code = 404;
            $message = "No phonebook found.";
        }
        $responseData = $phonebooks;
    }

    http_response_code($code);
    echo json_encode(array(
        "status" => "OK",
        "code" => $code,
        "message" => $message,
        "responseData" => $responseData
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
