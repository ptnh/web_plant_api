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

function getAccounts($accountParams){
    global $conn;

    if($accountParams['id_account'] == null){
        return error422('Enter your id account');
    }
    $idAccount = mysqli_real_escape_string($conn, $accountParams['id_account']);

    $query = "SELECT * FROM tb_accounts WHERE id_account = '$idAccount' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if($result){
        if(mysqli_num_rows($result) == 1){
            $res = mysqli_fetch_assoc($result);

            $data = [
                $res
            ];
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

function getAccountList(){
    global $conn;

    $query = "SELECT * FROM tb_accounts";
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

function storeAccounts($accountInput){
    global $conn;

    $name_client = mysqli_real_escape_string($conn, $accountInput['name_client']);
    $email_client = mysqli_real_escape_string($conn, $accountInput['email_client']);
    $phone_client = mysqli_real_escape_string($conn, $accountInput['phone_client']);
    $address_client = mysqli_real_escape_string($conn, $accountInput['address_client']);
    $username_account = mysqli_real_escape_string($conn, $accountInput['username_account']);
    $password_account = mysqli_real_escape_string($conn, $accountInput['password_account']);
    $permission = mysqli_real_escape_string($conn, $accountInput['permission']);
            $query = "INSERT INTO tb_accounts (id_account, name_client, email_client, 
            phone_client, address_client, username_account, password_account, permission) 
            VALUES (NULL, '$name_client', '$email_client', '$phone_client', '$address_client', '$username_account', '$password_account', '$permission')";
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

function updateAccount($accountInput){
    global $conn;

    $name_client = mysqli_real_escape_string($conn, $accountInput['name_client']);
    $email_client = mysqli_real_escape_string($conn, $accountInput['email_client']);
    $phone_client = mysqli_real_escape_string($conn, $accountInput['phone_client']);
    $address_client = mysqli_real_escape_string($conn, $accountInput['address_client']);
    $username_account = mysqli_real_escape_string($conn, $accountInput['username_account']);
    $password_account = mysqli_real_escape_string($conn, $accountInput['password_account']);
    
        $query = "UPDATE tb_accounts SET name_client='$name_client',
         email_client='$email_client', phone_client='$phone_client', address_client='$address_client'
         , password_account='$password_account' WHERE  username_account='$username_account'";
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

?>
