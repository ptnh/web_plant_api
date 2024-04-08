<?php

require '../inc/dbcon.php';

function error422($message){
    $data = [
        'status' => 422,
        'message' =>  $message,
    ];
    header("HTTP/1.0 422 Unprocessable Entity");

    echo json_encode($data);
    exit();
}

function getOrderList(){
    global $conn;

    $query = "SELECT * FROM tb_orders";
    $query_run = mysqli_query($conn, $query);

    if($query_run){
        if(mysqli_num_rows($query_run) > 0){
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            header("HTTP/1.0 200 OK");
        
            return json_encode($res, JSON_UNESCAPED_UNICODE);

        } else {
            $data[] = [
                'status' => 404,
                'message' => 'No cart Found',
            ];
            // header("HTTP/1.0 404 Not Found");
        
            return json_encode($res, JSON_UNESCAPED_UNICODE);
        }

    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
    
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }
}
function getOrderRevenue($orderParams) {
    global $conn;

    $revenueOrder = mysqli_real_escape_string($conn, $orderParams['total_money']);
 
    $query = "";
    if ($revenueOrder === 'quy') {
         // Tính ngày hiện tại và ngày trước 3 tháng
        $currentMonth = date('m');
        $currentYear = date('Y');
        $currentDate = date('d');
        $startOfMonth = date('Y-m-d');
        $endOfThreeMonthsAgo = date('Y-m-d', strtotime("-3 months"));

        $query = "SELECT SUM(total_money) AS total_amount FROM tb_orders 
              WHERE day_bought BETWEEN '$endOfThreeMonthsAgo' AND '$startOfMonth'
              AND status_order = 'Đã giao'";
        // $result = mysqli_query($conn, $query);
    } else if ($revenueOrder =='thang'){
         // Tính ngày hiện tại và tháng trước
    $currentMonth = date('m');
    $currentYear = date('Y');
    $currentDate = date('d');
    $startOfMonth = date('Y-m-d');
    $endOfOneMonthsAgo = date('Y-m-d', strtotime("-1 months"));

    $query = "SELECT SUM(total_money) AS total_amount FROM tb_orders 
              WHERE day_bought BETWEEN '$endOfOneMonthsAgo' AND '$startOfMonth'
              AND status_order = 'Đã giao'";
    // $result = mysqli_query($conn, $query);
    } else if ($revenueOrder == 'tuan'){
         // Tính ngày hiện tại và ngày 7 ngày trước
        $currentDate = date('Y-m-d');
        $sevenDaysAgo = date('Y-m-d', strtotime("-7 days"));

        $query = "SELECT SUM(total_money) AS total_amount FROM tb_orders 
        WHERE day_bought BETWEEN '$sevenDaysAgo' AND '$currentDate'
        AND status_order = 'Đã giao'";
        // $result = mysqli_query($conn, $query);
    } else if($revenueOrder == 'tong'){
        $query = "SELECT SUM(total_money) AS total_amount FROM tb_orders 
              WHERE status_order = 'Đã giao'";
    }

    // $query = "SELECT SUM(total_money) AS total_amount FROM tb_orders 
    //            WHERE status_order = '$statusOrder'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $totalAmount = $row['total_amount'];
            header("HTTP/1.0 200 OK");
            echo json_encode(['total_amount' => $totalAmount], JSON_UNESCAPED_UNICODE);
        } else {
            header("HTTP/1.0 404 No customer Found");
            echo json_encode(['total_amount' => 0], JSON_UNESCAPED_UNICODE);
        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal server',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }
}

function getOrderStatus($orderParams) {
    global $conn;

    $statusOrder = mysqli_real_escape_string($conn, $orderParams['status_order']);
 
    $query = "";
    if ($statusOrder === 'Đã giao') {
        $query = "SELECT COUNT(DISTINCT id_order) AS total_count FROM tb_orders 
        WHERE status_order = 'Đã giao'";
    } else if ($statusOrder =='Chờ duyệt'){
        $query = "SELECT COUNT(DISTINCT id_order) AS total_count FROM tb_orders 
        WHERE status_order = 'Chờ duyệt'";
    } else if ($statusOrder == 'Đang chuyển'){
        $query = "SELECT COUNT(DISTINCT id_order) AS total_count FROM tb_orders 
        WHERE status_order = 'Đã duyệt'";
    } else if($statusOrder == 'tong'){
        $query = "SELECT COUNT(DISTINCT id_order) AS total_count FROM tb_orders;";
    }

    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $totalAmount = $row['total_count'];
            header("HTTP/1.0 200 OK");
            echo json_encode(['total_count' => $totalAmount], JSON_UNESCAPED_UNICODE);
        } else {
            header("HTTP/1.0 404 No customer Found");
            echo json_encode(['total_count' => 0], JSON_UNESCAPED_UNICODE);
        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal server',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        echo json_encode($data);
    }
}

function getOrderQuantity($orderParams) {
    global $conn;

    $quantityOrder = mysqli_real_escape_string($conn, $orderParams['quantity']);
 
    $query = "";
    if ($quantityOrder === 'tong') {
        $query = "SELECT SUM(quantity) AS tongsoluong FROM tb_orders 
        WHERE status_order = 'Đã giao'";

        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $totalAmount = $row['tongsoluong'];
                header("HTTP/1.0 200 OK");
                echo json_encode(['tongsoluong' => $totalAmount], JSON_UNESCAPED_UNICODE);
            } else {
                header("HTTP/1.0 404 No customer Found");
                echo json_encode(['tongsoluong' => 0], JSON_UNESCAPED_UNICODE);
            }
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal server',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            echo json_encode($data);
        }
    } else if ($quantityOrder =='rank1'){
        $query = "SELECT name_product, SUM(quantity) AS tongsoluong
        FROM tb_orders
        WHERE status_order = 'Đã giao'
        GROUP BY name_product
        ORDER BY tongsoluong DESC
        LIMIT 1;";

        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                // $totalAmount = $row['tongsoluong'];
                header("HTTP/1.0 200 OK");
                echo json_encode($row, JSON_UNESCAPED_UNICODE);
            } else {
                header("HTTP/1.0 404 No customer Found");
                echo json_encode($row, JSON_UNESCAPED_UNICODE);
            }
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal server',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            echo json_encode($data);
        }
    } else if ($quantityOrder == 'rank2'){
        $query = "SELECT name_product, SUM(quantity) AS tongsoluong
        FROM tb_orders
        WHERE status_order = 'Đã giao'
        GROUP BY name_product
        ORDER BY tongsoluong DESC
        LIMIT 1 OFFSET 1";

        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                // $totalAmount = $row['tongsoluong'];
                header("HTTP/1.0 200 OK");
                echo json_encode($row, JSON_UNESCAPED_UNICODE);
            } else {
                header("HTTP/1.0 404 No customer Found");
                echo json_encode($row, JSON_UNESCAPED_UNICODE);
            }
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal server',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            echo json_encode($data);
        }
    } else if($quantityOrder == 'rank3'){
        $query = "SELECT name_product, SUM(quantity) AS tongsoluong
        FROM tb_orders
        WHERE status_order = 'Đã giao'
        GROUP BY name_product
        ORDER BY tongsoluong DESC
        LIMIT 1 OFFSET 2";

        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                // $totalAmount = $row['tongsoluong'];
                header("HTTP/1.0 200 OK");
                echo json_encode($row, JSON_UNESCAPED_UNICODE);
            } else {
                header("HTTP/1.0 404 No customer Found");
                echo json_encode($row, JSON_UNESCAPED_UNICODE);
            }
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal server',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            echo json_encode($data);
        }
    }

   
}

function getOrders($orderParams){
    global $conn;

    if($orderParams['id_owner'] == null){
        return error422('Enter your id  owner');
    }
    $idOwner = mysqli_real_escape_string($conn, $orderParams['id_owner']);

    $query = "SELECT * FROM tb_orders WHERE id_owner = '$idOwner'";
    $result = mysqli_query($conn, $query);
    
    if($result){
        if(mysqli_num_rows($result) >0){
            $orders = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $orders[] = $row;
            }

            header("HTTP/1.0 200 OK");
            return json_encode($orders, JSON_UNESCAPED_UNICODE);;
        } else {
            $orders = array();
            header("HTTP/1.0 404 No customer Found");
            echo json_encode($orders, JSON_UNESCAPED_UNICODE);
        }
        
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal server',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

function getIdOrder($orderParams){
    global $conn;

    if($orderParams['id_order'] == null){
        return error422('Enter your id  order');
    }
    $idOrder = mysqli_real_escape_string($conn, $orderParams['id_order']);

    $query = "SELECT * FROM tb_orders WHERE id_order = '$idOrder'";
    $result = mysqli_query($conn, $query);
    
    if($result){
        if(mysqli_num_rows($result) >0){
            // $res = mysqli_fetch_assoc($result);
            $idOrders = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $idOrders[] = $row;
            }

            // $data = [
                // 'status' => 200,
                // 'message' => 'Customer Fetch Successfully',
                //  $carts
                // $res
            // ];
            header("HTTP/1.0 200 OK");
            return json_encode($idOrders, JSON_UNESCAPED_UNICODE);;
        } else {
            $orders = array();
            header("HTTP/1.0 404 No customer Found");
            echo json_encode($idOrders, JSON_UNESCAPED_UNICODE);
        }
        
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal server',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

function storeOrders($orderInput){
    global $conn;

    $id_order = mysqli_real_escape_string($conn, $orderInput['id_order']);
    $id_owner = mysqli_real_escape_string($conn, $orderInput['id_owner']);
    $id_plant = mysqli_real_escape_string($conn, $orderInput['id_plant']);
    $name_product = mysqli_real_escape_string($conn, $orderInput['name_product']);
    $image_represent = mysqli_real_escape_string($conn, $orderInput['image_represent']);
    $price_product = mysqli_real_escape_string($conn, $orderInput['price_product']);
    $quantity = mysqli_real_escape_string($conn, $orderInput['quantity']);
    $day_bought = mysqli_real_escape_string($conn, $orderInput['day_bought']);
    $total_money = mysqli_real_escape_string($conn, $orderInput['total_money']);
    $status_order = mysqli_real_escape_string($conn, $orderInput['status_order']);
    
            $query = "INSERT INTO  tb_orders ( id_order ,  id_owner ,  id_plant ,  name_product , 
             image_represent ,  price_bought ,  quantity, day_bought ,total_money, status_order) VALUES ('$id_order', $id_owner, $id_plant,
             '$name_product', '$image_represent', $price_product, $quantity, '$day_bought', $total_money, '$status_order')";
            $result = mysqli_query($conn, $query);
            if($result){
                $data = [
                    'status' => 201,
                    'message' => 'Order Created',
                ];
                header("HTTP/1.0 201 Created");
            
                return json_encode($data);
    
            } else {
                $data = [
                    'status' => 500,
                    'message' => 'Internal Server Error',
                ];
                header("HTTP/1.0 500 Internal Server Error");
            
                return json_encode($data);
            }
}

function deleteOrder($orderInput){
    global $conn;

    $id_order = mysqli_real_escape_string($conn, $orderInput['id_order']);
    
        $query = "DELETE FROM tb_orders WHERE id_order = '$id_order' AND status_order = 'Chờ duyệt'";
        $result = mysqli_query($conn, $query);
    
        if($result){
            $data = [
                'status' => 201,
                'message' => 'Customer delete oke',
            ];
            header("HTTP/1.0 201 OK");
        
            return json_encode($data);

        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
        
            return json_encode($data);
        }
}

function updateOrder($orderInput){
    global $conn;

    $id_order = mysqli_real_escape_string($conn, $orderInput['id_order']);
    $status = mysqli_real_escape_string($conn, $orderInput['status_order']);
    
        $query = "UPDATE tb_orders SET status_order='$status' WHERE id_order = '$id_order'";
        $result = mysqli_query($conn, $query);
        // return($flag);
        if($result){
            $data = [
                'status' => 201,
                'message' => 'Cart updated oke',
            ];
            header("HTTP/1.0 201 OK");
        
            return json_encode($data);

        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
        
            return json_encode($data);
        }
}

?>
