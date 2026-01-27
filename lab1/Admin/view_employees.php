<?php
session_start();
$conn = new mysqli("localhost","yash","yash1204","electricity");

if(!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.html");
    exit();
}

$result = $conn->query("SELECT * FROM employees");
$empCount = $result->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approved Employees</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2>Approved Employees</h2>

<?php if($empCount > 0) { ?>
<table>
    <tr>
        <th>Employee ID</th>
        <th>Name</th>
        <th>Mobile</th>
    </tr>

    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['emp_id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['mobile'] ?></td>
    </tr>
    <?php } ?>
</table>

<?php } else { ?>
<p style="text-align:center; font-size:18px; color:#475569; margin-top:30px;">
    No employees registered yet.
</p>
<?php } ?>

</body>
</html>
