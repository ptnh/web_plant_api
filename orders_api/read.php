<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET");

include('function.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];
if($requestMethod == "GET"){

    if(isset($_GET['id_owner'])){
        $orders = getOrders($_GET);
        echo $orders;
    } else if(isset($_GET['total_money'])){
        $orders = getOrderRevenue($_GET);
        echo $orders;
    } else if(isset($_GET['status_order'])){
        $orders = getOrderStatus($_GET);
        echo $orders;
    } else if(isset($_GET['quantity'])){
        $orders = getOrderQuantity($_GET);
        echo $orders;
    } else if(isset($_GET['id_order'])){
        $orders = getIdOrder($_GET);
        echo $orders;
    } else{
        $orderList = getOrderList();
        echo $orderList;
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