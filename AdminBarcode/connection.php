<?php
    $servername = "localhost";
    $username = "thammad1";
    $password = "ag9HQ89E@rjO*4"; 
    $dbname = "thammad1_barcode"; 

    $conn = mysqli_connect($servername,$username,$password,$dbname);

    mysqli_set_charset($conn, "utf8");

    if(!$conn){
        die('Connection Fail '.mysqli_connect_error());
    }


?>
