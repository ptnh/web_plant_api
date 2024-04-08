<?php
     $server = "localhost";
     $user = 'root';
     $pass = '';
     $database = 'data_webplant';
 
     $conn = new mysqLi($server, $user, $pass, $database);
 
     if($conn){
            echo ' ket noi thanh cong';
         // die("Connection Failed:" . my sqli_connect_error());
     } else {
         echo ' ket noi that bai';
     }
?>