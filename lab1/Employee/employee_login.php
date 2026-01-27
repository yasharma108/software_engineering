<?php
session_start();
$conn = new mysqli("localhost","yash","yash1204","electricity");

if($conn->connect_error) {
    die("DB connection failed");
}

$emp_id = $_POST['emp_id'];
$password = $_POST['password'];

$result = $conn->query("SELECT * FROM employees WHERE emp_id='$emp_id'");

if($result->num_rows==0) {
    die("Invalid Employee ID or not approved yet");
}

$employee = $result->fetch_assoc();

if(password_verify($password, $employee['password'])) {
    $_SESSION['emp_id'] = $emp_id;
    $_SESSION['role'] = 'employee';
    header("Location: employee_dashboard.php");
    exit();
} else {
    echo "Incorrect Password";
}
?>
