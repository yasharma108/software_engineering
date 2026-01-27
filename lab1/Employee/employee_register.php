<?php
$conn = new mysqli("localhost","yash","yash1204","electricity");

if($conn->connect_error) die("Database connection failed");

$name = $_POST['name'];
$mobile = $_POST['mobile'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Insert as pending (no employee ID yet)
$sql = "INSERT INTO employee_requests (name, mobile, password, status)
        VALUES ('$name','$mobile','$password','Pending')";

if($conn->query($sql)) {
    echo "<h2>Employee Registration Submitted</h2>";
    echo "Waiting for Admin Approval. Your Employee ID will be generated after approval.";
} else {
    echo "Error: " . $conn->error;
}
?>
