<?php
$conn = new mysqli("localhost", "root", "", "electricity");

$name = $_POST['name'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];

function generateEmployeeId() {
    return "EMP" . rand(10000, 99999);
}

do {
    $employee_id = generateEmployeeId();
    $check = $conn->query(
        "SELECT employee_id FROM employees WHERE employee_id='$employee_id'"
    );
} while ($check->num_rows > 0);

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$conn->query(
    "INSERT INTO employees (employee_id, name, mobile, password_hash)
     VALUES ('$employee_id', '$name', '$mobile', '$password_hash')"
);

echo "Employee registered successfully.<br>";
echo "Your Employee ID: <b>$employee_id</b>";
