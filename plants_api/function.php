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

function storePlant($plantInput){
    global $conn;

    $name_product = mysqli_real_escape_string($conn, $plantInput['name']);
    $discount_product = mysqli_real_escape_string($conn, $plantInput['discount']);
    $price_product = mysqli_real_escape_string($conn, $plantInput['price_new']);
    $pic_main = mysqli_real_escape_string($conn, $plantInput['picture_main']);
    $pic_1 = mysqli_real_escape_string($conn, $plantInput['picture_other_1']);
    $pic_2 = mysqli_real_escape_string($conn, $plantInput['picture_other_2']);
    $pic_3 = mysqli_real_escape_string($conn, $plantInput['picture_other_3']);
    $type_product = mysqli_real_escape_string($conn, $plantInput['type_plant']);
    
    $query = "INSERT INTO tb_plants (id, name, discount, price_new, picture_main, 
    picture_other_1, picture_other_2, picture_other_3, type_plant) 
    VALUES (NULL, '$name_product', '$discount_product', $price_product, ' $pic_main', 
    '$pic_1', ' $pic_2', '$pic_3', '$type_product')";
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

function getPlantList(){
    global $conn;

    $query = "SELECT * FROM tb_plants";
    $query_run = mysqli_query($conn, $query);

    if($query_run){
        if(mysqli_num_rows($query_run) > 0){
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
        
            header("HTTP/1.0 200 OK");
        
            return json_encode($res, JSON_UNESCAPED_UNICODE);;

        } else {
            $data[] = [
                'status' => 404,
                'message' => 'No sinhvien Found',
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


function getPlants_id($plantParams){
    global $conn;


    $plantType = mysqli_real_escape_string($conn, $plantParams['id']);

    $query = "SELECT * FROM tb_plants WHERE id = '$plantType'";
    $result = mysqli_query($conn, $query);
    
    if($result){
        if(mysqli_num_rows($result) > 0){
            $data = []; // Khởi tạo mảng dữ liệu

            // Lặp qua các dòng kết quả và thêm vào mảng dữ liệu
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No customer Found',
            ];
            header("HTTP/1.0 404 No customer Found");
            return json_encode($data);
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

function getPlants($plantParams){
    global $conn;


    $plantType = mysqli_real_escape_string($conn, $plantParams['type_plant']);

    $query = "SELECT * FROM tb_plants WHERE type_plant = '$plantType'";
    $result = mysqli_query($conn, $query);
    
    if($result){
        if(mysqli_num_rows($result) > 0){
            $data = []; // Khởi tạo mảng dữ liệu

            // Lặp qua các dòng kết quả và thêm vào mảng dữ liệu
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No customer Found',
            ];
            header("HTTP/1.0 404 No customer Found");
            return json_encode($data);
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

function deletePlant($plantInput){
    global $conn;

    $name_product = mysqli_real_escape_string($conn, $plantInput['name']);
    $pic_main = mysqli_real_escape_string($conn, $plantInput['picture_main']);
    
        $query = "DELETE FROM tb_plants WHERE name = '$name_product' AND picture_main = '$pic_main'";
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

function updatePlant($plantInput) {
    global $conn;

    $name_product = mysqli_real_escape_string($conn, $plantInput['name']);
    $discount_product = mysqli_real_escape_string($conn, $plantInput['discount']);
    $price_product = mysqli_real_escape_string($conn, $plantInput['price_new']);
    $pic_main = mysqli_real_escape_string($conn, $plantInput['picture_main']);
    $pic_1 = mysqli_real_escape_string($conn, $plantInput['picture_other_1']);
    $pic_2 = mysqli_real_escape_string($conn, $plantInput['picture_other_2']);
    $pic_3 = mysqli_real_escape_string($conn, $plantInput['picture_other_3']);
    $type_product = mysqli_real_escape_string($conn, $plantInput['type_plant']);

    $query1 = "SELECT id FROM tb_plants WHERE name = '$name_product' OR picture_main = '$pic_main'";
    $result1 = mysqli_query($conn, $query1);

    if ($result1) {
        $row = mysqli_fetch_assoc($result1);
        $id = $row['id'];

        $query = "UPDATE tb_plants SET name = '$name_product',
        discount = $discount_product, price_new = $price_product, picture_main = '$pic_main',
        picture_other_1 = '$pic_1', picture_other_2 = '$pic_2', picture_other_3 = '$pic_3',
        type_plant = '$type_product' WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($result) {
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
}



?>
