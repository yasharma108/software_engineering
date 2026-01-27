<?php
    $servername="localhost";
    $username="yash";
    $password="yash1204";
    $dbname="electricity";
    $conn=new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    else{
        echo "Connected successfully";
    }
?>