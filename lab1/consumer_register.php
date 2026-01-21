<?php
    session_start();
    $conn = new mysqli("localhost","root","","electricity");

    $message = "";
    if(isset($_POST['register'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $mobile = $conn->real_escape_string($_POST['mobile']);
        $address = $conn->real_escape_string($_POST['address']);
        $category = $conn->real_escape_string($_POST['category']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hashed

        // Random 12-char alphanumeric meter ID
        $meter_id = strtoupper(substr(md5(uniqid()),0,12));

        $sql = "INSERT INTO consumer_requests (name,mobile,address,category,status,meter_id)
                VALUES ('$name','$mobile','$address','$category','Pending','$meter_id')";
        
        if($conn->query($sql)) {
            $message = "Registration submitted! Waiting for admin approval. Your Meter ID: $meter_id";
        } else {
            $message = "Error: ".$conn->error;
        }
    }   
?>
