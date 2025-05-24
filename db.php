<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "hotel_management_system"; 


    try {
        $conn =  mysqli_connect($host, $username, $password, $database);

    }catch(mysqli_sql_exception) {
        echo "Can not connected";
    }

?>
