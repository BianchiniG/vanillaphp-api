<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


include_once '../../Config/Database.php';
include_once '../../Entities/Phonebook.php';

$database = new Database();
$db = $database->getConnection();


$phonebook = new Phonebook($db);
$stmt = $phonebook->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $phonebooks_arr = array();
    $phonebooks_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $phonebook_item = array(
            "id" => $row['id'],
            "name" => $row['name'],
            "description" => $row['description']
        );

        $phonebooks_arr["records"] []= $phonebook_item;
    }

    http_response_code(200);

    echo json_encode($phonebooks_arr);
} else {
    http_response_code(404);

    echo json_encode(
        array("message" => "No Phonebooks found.")
    );
}
