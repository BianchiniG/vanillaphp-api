<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


include_once '../../Config/Database.php';
include_once '../../Entities/Phone.php';

$database = new Database();
$db = $database->getConnection();

$phone = new Phone($db);
$stmt = $phone->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $phones_arr = array();
    $phones_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $phone_item = array(
            "id" => $row['id'],
            "first_name" => $row['first_name'],
            "last_name" => $row['last_name'],
            "phone_number" => $row['phone_number'],
            "phonebook_id" => $row['phonebook_id']

        );

        $phones_arr["records"] []= $phone_item;
    }

    http_response_code(200);

    echo json_encode($phones_arr);
} else {
    http_response_code(404);

    echo json_encode(
        array("message" => "No Phones found.")
    );
}
