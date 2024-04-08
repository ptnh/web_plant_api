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

function getComments($commentParams){
    global $conn;
    $id_plant_comment = mysqli_real_escape_string($conn, $commentParams['id_plant']);
    $query = "SELECT * FROM tb_comments INNER JOIN tb_accounts ON tb_comments.id_owner = tb_accounts.id_account WHERE id_plant = '$id_plant_comment'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $data = []; // Khởi tạo mảng dữ liệu

        // Lặp qua các dòng kết quả và thêm vào mảng dữ liệu
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (count($data) > 0) {
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            header("HTTP/1.0 200 OK"); // Trả về mã trạng thái 200 OK
            return json_encode([]); // Trả về mảng dữ liệu rỗng
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

function getCommentss($commentParams){
    global $conn;
    $id_plant_comment = mysqli_real_escape_string($conn, $commentParams['id_plants']);

    $query = "SELECT *, SUM(start) as sosao, COUNT(start) as sl FROM tb_comments INNER JOIN tb_accounts ON tb_comments.id_owner = tb_accounts.id_account WHERE id_plant = '$id_plant_comment'";
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

function getCommentsss($commentParams){
    global $conn;
    $id_plant_comment = mysqli_real_escape_string($conn, $commentParams['id_plantss']);

    $query = "SELECT *
    FROM tb_comments
    INNER JOIN tb_accounts ON tb_comments.id_owner = tb_accounts.id_account
    INNER JOIN tb_plants ON tb_comments.id_plant = tb_plants.id
    ORDER BY tb_comments.id_plant;
    
    ";
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

function getIdComment($commentParams){
    global $conn;
    $id_comment = mysqli_real_escape_string($conn, $commentParams['id_comment']);
   
    
    if($id_comment == 'tong'){
        $query = "SELECT COUNT(*) AS total_count FROM tb_comments";
    } else if ($id_comment == 'rank1'){
        $query = "SELECT COUNT(*) AS total_count FROM tb_comments WHERE start = 5";
    } else if ($id_comment == 'rank2'){
        $query = "SELECT COUNT(*) AS total_count FROM tb_comments WHERE start = 3 OR start = 4";
    } else if ($id_comment == 'rank3'){
        $query = "SELECT COUNT(*) AS total_count FROM tb_comments WHERE start = 1 OR start = 2";
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

function storeComments($commentInput){
    global $conn;
    $id_owner = mysqli_real_escape_string($conn, $commentInput['id_owner']);
    $id_plant = mysqli_real_escape_string($conn, $commentInput['id_plant']);
    $content = mysqli_real_escape_string($conn, $commentInput['content']);
    $start = mysqli_real_escape_string($conn, $commentInput['start']);
   

            $query = "INSERT INTO tb_comments(id_comment, id_owner, id_plant, content, start) 
            VALUES (NULL, $id_owner, $id_plant, '$content', $start)";
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

function deleteComment($commentInput){
    global $conn;

    $id_comment = mysqli_real_escape_string($conn, $commentInput['id_comment']);
    
        $query = "DELETE FROM tb_comments WHERE id_comment = $id_comment";
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
