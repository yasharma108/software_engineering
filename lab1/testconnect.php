<?php
$conn=new mysqli("localhost", "root", "", "electricity");

if($conn)
    {
        echo"Successfully connected to db";
    }
    else
        {
            echo "Error in connecting to db";
        }
?>