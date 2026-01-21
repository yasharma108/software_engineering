<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "electricity");

    $employee_id = $_POST['employee_id'];
    $password = $_POST['password'];

    $result = $conn->query(
        "SELECT * FROM employees WHERE employee_id='$employee_id'"
    );

    if ($result->num_rows == 1) {
        $employee = $result->fetch_assoc();
        if (password_verify($password, $employee['password_hash'])) {
            $_SESSION['role'] = 'employee';
            $_SESSION['employee_id'] = $employee_id;
            header("Location: employee_dashboard.php");
            exit();
        }
    }

    echo "Invalid Employee ID or Password";
?>