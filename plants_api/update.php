<?php

error_reporting(0);

header("Access-Control-Allow-Origin:*");
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

include('function.php');


$requestMethod = $_SERVER["REQUEST_METHOD"];
if($requestMethod == 'POST'){
    $inputData = json_decode(file_get_contents("php://input"), true);
    if(empty($inputData)){
        $updatePlant = updatePlant($_POST);
    } else {
        $updatePlant = updatePlant($_POST);
    }
    echo $updatePlant;
} else {
    $data = [
        'status' => 405,
        'mesage' => $requestMethod, 'Method Not Allowed',
    ];
    header("HTTP/1.0 405 Method Not Allowed");

    echo json_encode($data);
}

?>