<?php
    $conn=new mysqli("localhost", "root", "", "electricity");
    if($conn->connect_error)
    {
        die("Failed to connect to db");
    }
    $name=$_POST['name'];
    $phone=$_POST['phone'];
    $password=password_hash($_POST['password'], PASSWORD_DEFAULT);

    function generateAdminID()
    {
        return "ADM" . rand(10000, 99999);
    }
    do{
        $admin_id=generateAdminID();
        $check=$conn->query(
            "select admin_id from admins where admin_id='$admin_id'"
        );
    }while($check->num_rows>0);
    $sql="insert into admins values('$admin_id', '$name', '$phone', '$password')";
    if($conn->query($sql))
    {
        echo "<h2>Admin Registered Successfully</h2>";
        echo "Your Admin ID is: <b>$admin_id</b>";
    }
    else
    {
        echo "Error: ". $conn->error;
    }
    
?>