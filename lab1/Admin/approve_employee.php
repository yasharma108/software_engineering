<?php
$conn = new mysqli("localhost","yash","yash1204","electricity");

$id = $_POST['id'];

$request = $conn->query("SELECT * FROM employee_requests WHERE id=$id")->fetch_assoc();

function generateEmployeeID($conn) {
    do {
        $emp_id = "EMP" . rand(10000,99999);
        $check = $conn->query("SELECT emp_id FROM employees WHERE emp_id='$emp_id'");
    } while($check->num_rows > 0);
    return $emp_id;
}

$emp_id = generateEmployeeID($conn);

$conn->query("
    INSERT INTO employees (emp_id, name, mobile, password)
    VALUES ('$emp_id','{$request['name']}','{$request['mobile']}','{$request['password']}')
");

$conn->query("DELETE FROM employee_requests WHERE id=$id");

header("Location: employee_requests.php");
exit();
?>
