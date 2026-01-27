<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "yash", "yash1204", "electricity");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if form submitted
if (isset($_POST['login'])) {
    $user_id = $conn->real_escape_string($_POST['user_id']);
    $password = $_POST['password'];

    // Fetch consumer by consumer_no OR meter_id
    $stmt = $conn->prepare("SELECT * FROM consumers WHERE consumer_no=? OR meter_id=?");
    $stmt->bind_param("ss", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // No such consumer found
        header("Location: consumerlogin.html?message=Invalid+Consumer+Number+or+Meter+ID");
        exit();
    }

    $consumer = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $consumer['password'])) {
        // Successful login
        $_SESSION['role'] = 'consumer';
        $_SESSION['consumer_no'] = $consumer['consumer_no'];
        $_SESSION['meter_id'] = $consumer['meter_id'];
        $_SESSION['name'] = $consumer['name'];

        // Redirect to consumer dashboard
        header("Location: consumer_dashboard.php");
        exit();
    } else {
        // Incorrect password
        header("Location: consumerlogin.html?message=Incorrect+Password");
        exit();
    }
} else {
    // Direct access to this page without POST
    header("Location: consumerlogin.html");
    exit();
}
?>
