<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "electricity");

    if ($conn->connect_error) 
    {
        die("Database connection failed");
    }
    $admin_id=$_POST['admin_id'];
    $password=$_POST['password'];
    $result=$conn->query("select * from admins where admin_id='$admin_id'");
    if($result->num_rows==0)
    {
        die("Invalid ID");
    }
    $admin=$result->fetch_assoc();
    if(password_verify($password, $admin['password']))
    {
        $_SESSION['admin_id']=$admin_id;
        $_SESSION['role']='admin';
        header("Location: admin_dashboard.php");
        exit();
    }
    else
    {
        echo "Incorrect Password";
    }
    
?>