<?php

header("Access-Control-Allow-Origin:*");
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET");

include('function.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];
if($requestMethod == "GET"){

    if(isset($_GET['id_owner'])){
        $carts = getCarts($_GET);
        echo $carts;
    } else {
        $cartList = getCartList();
        echo $cartList;
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