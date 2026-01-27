<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "yash", "yash1204", "electricity");
if ($conn->connect_error) {
    die("DB connection failed");
}

if (isset($_POST['register'])) {

    $name     = $_POST['name'];
    $mobile   = $_POST['mobile'];
    $address  = $_POST['address'];
    $category = $_POST['category'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $CID = NULL;
    $IID = NULL;

    if ($category === "Commercial") {
        $CID = $_POST['CID'];
    }
    if ($category === "Industrial") {
        $IID = $_POST['IID'];
    }

    $stmt = $conn->prepare(
        "INSERT INTO consumer_requests 
        (name, mobile, address, category, CID, IID, password) 
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssss",
        $name, $mobile, $address, $category, $CID, $IID, $password
    );

    if ($stmt->execute()) {
        echo "Registration successful. Waiting for admin approval.";
    } else {
        echo "Execution failed: " . $stmt->error;
    }
}
?>
