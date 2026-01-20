<?php
    $conn=new mysqli("localhost", "root", "", "electricity");
    $name=$_POST['name'];
    $mobile=$_POST['mobile'];
    $address=$_POST['address'];

    function generateMeterID()
    {
        $chars="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $id="EM";
        for($i=0; $i<6; $i++)
        {
            $id.=$chars[rand(0, strlen($chars)-1)];
        }
        return $id;
    }

    do{
        $meter_id=generateMeterID();
        $check=$conn->query("select meter_id from users where meter_id='$meter_id'");
    }while($check->num_rows>0);
    $sql = "insert into users values('$meter_id', '$name', '$mobile', '$address')";

    if($conn->query($sql))
    {
        echo "<h2>User Registration Successfully Done</h2>";
        echo "Assigned Meter ID: <b>$meter_id</b>";        
    }
    else
    {
        echo "Error";
    }
?>