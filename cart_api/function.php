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

function storeOrders($cartInput){
    global $conn;

    $id_owner = mysqli_real_escape_string($conn, $cartInput['id_owner']);
    $id_plant = mysqli_real_escape_string($conn, $cartInput['id_plant']);
    $name_product = mysqli_real_escape_string($conn, $cartInput['name_product']);
    $image_represent = mysqli_real_escape_string($conn, $cartInput['image_represent']);
    $price_product = mysqli_real_escape_string($conn, $cartInput['price_product']);
    $quantity = mysqli_real_escape_string($conn, $cartInput['quantity']);
    $query = "INSERT INTO tb_cart (id_cart, id_owner, id_plant, name_product, image_represent, price_product, quantity) 
    VALUES (NULL, $id_owner, $id_plant, '$name_product', '$image_represent', $price_product, $quantity)";
    $result = mysqli_query($conn, $query);
    if($result){
        $data = [
            'status' => 201,
            'message' => 'Customer Created',
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
            

function getCartList(){
    global $conn;

    $query = "SELECT * FROM tb_cart";
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

function getCarts($cartParams){
    global $conn;
  
    $id = mysqli_real_escape_string($conn, $cartParams['id_owner']);

    if($id === 'cart'){
        $query = "SELECT *
        FROM tb_cart
        INNER JOIN tb_accounts ON tb_cart.id_owner = tb_accounts.id_account ORDER BY tb_cart.id_owner";
        $result = mysqli_query($conn, $query);
        
        if($result){
            if(mysqli_num_rows($result) >0){
            
                $carts = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    $carts[] = $row;
                }

                header("HTTP/1.0 200 OK");
                return json_encode($carts, JSON_UNESCAPED_UNICODE);;
            } else {
                $data = [
                    'status' => 404,
                    'message' => 'No customer Found',
                ];
                header("HTTP/1.0 404 No customer Found");
                return json_encode($data, JSON_UNESCAPED_UNICODE);;
            }
            
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal server',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    } else {
        $query = "SELECT * FROM tb_cart as cart WHERE id_owner = $id";
        $result = mysqli_query($conn, $query);
        
        if($result){
            if(mysqli_num_rows($result) >0){
               
                $carts = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    $carts[] = $row;
                }

                header("HTTP/1.0 200 OK");
                return json_encode($carts, JSON_UNESCAPED_UNICODE);;
            } else {
                $data = [
                    'status' => 404,
                    'message' => 'No customer Found',
                ];
                header("HTTP/1.0 404 No customer Found");
                return json_encode($carts, JSON_UNESCAPED_UNICODE);;
            }
            
        } else {
            $carts = [
                'status' => 500,
                'message' => 'Internal server',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($carts);
        }
    }
}

function updateCart($cartInput){
    global $conn;

    $quantity = mysqli_real_escape_string($conn, $cartInput['quantity']);
    $id_owner = mysqli_real_escape_string($conn, $cartInput['id_owner']);
    $id_plant = mysqli_real_escape_string($conn, $cartInput['id_plant']);
    
        $query = "UPDATE tb_cart SET quantity=$quantity WHERE id_owner = $id_owner AND id_plant = $id_plant";
        $result = mysqli_query($conn, $query);
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

function deleteCart($cartInput){
    global $conn;

    $id_cart = mysqli_real_escape_string($conn, $cartInput['id_cart']);

        $query = "DELETE FROM tb_cart WHERE id_cart = $id_cart";
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

?>
