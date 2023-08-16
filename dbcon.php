<?php
    $server = 'localhost';
    $user = 'root';
    $pass = '';
    $database = 'db_webplants';

    $conn = new mysqLi($server, $user, $pass, $database, 8111);

    if($conn){
        //mysqLi_query($conn, " SETNAME 'utf8' ");
        // die("Connection Failed:" . my sqli_connect_error());
    } else {
        echo ' ket noi that bai';
    }
?>