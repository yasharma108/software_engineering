<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "yash", "yash1204", "electricity");

if ($conn->connect_error) {
    die("Database connection failed");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    function generateAdminID() {
        return "ADM" . rand(10000, 99999);
    }

    do {
        $admin_id = generateAdminID();
        $check = $conn->query(
            "SELECT admin_id FROM admins WHERE admin_id='$admin_id'"
        );
    } while ($check->num_rows > 0);

    $sql = "INSERT INTO admins (admin_id, name, mobile, password)
            VALUES ('$admin_id', '$name', '$mobile', '$password')";

    if ($conn->query($sql)) {
        echo "<h2>Admin Registered Successfully</h2>";
        echo "Your Admin ID is: <b>$admin_id</b>";
    } else {
        echo "SQL Error: " . $conn->error;
    }
}
?>
