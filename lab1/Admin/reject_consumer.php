<?php
    $conn = new mysqli("localhost","root","","electricity");

    $id = $_POST['id'];

    $conn->query(
        "UPDATE consumer_requests SET status='Rejected' WHERE id=$id"
    );

    header("Location: admin_dashboard.php");
?>