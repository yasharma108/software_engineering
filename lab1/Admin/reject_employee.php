<?php
$conn = new mysqli("localhost","yash","yash1204","electricity");

$id = $_POST['id'];

$conn->query("UPDATE employee_requests SET status='Rejected' WHERE id=$id");

header("Location: employee_requests.php");
exit();
?>
