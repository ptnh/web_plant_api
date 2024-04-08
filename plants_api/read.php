<?php

header("Access-Control-Allow-Origin:*");
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET");

include('function.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];
if($requestMethod == "GET"){

    if(isset($_GET['type_plant'])){
        $plants = getPlants($_GET);
        echo $plants;
    } else if(isset($_GET['id'])){
        $plants = getPlants_id($_GET);
        echo $plants;
    }
    else{
        $plantList = getPlantList();
        echo $plantList;
    }
    


} else {
    $data = [
        'status' => 405,
        'mesage' => $requestMethod, 'Method Not Allowed',
    ];
    header("HTTP/1.0 405 Method Not Allowed");

    echo json_encode($data);
}

?>