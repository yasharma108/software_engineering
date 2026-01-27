<?php
session_start();

$conn = new mysqli("localhost", "yash", "yash1204", "electricity");
if ($conn->connect_error) {
    die("Database connection failed");
}

if (isset($_POST['admin_id'], $_POST['password'])) {

    $admin_id = $_POST['admin_id'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE admin_id = ?");
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Invalid Admin ID";
        exit();
    }

    $admin = $result->fetch_assoc();

    if (password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['role'] = 'admin';
        header("Location: ./admin_dashboard.php");
        exit();
    } else {
        echo "Incorrect Password";
    }
}
?>
