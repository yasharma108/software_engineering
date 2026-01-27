<?php
session_start();
$conn = new mysqli("localhost","yash","yash1204","electricity");

if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.html");
    exit();
}

$result = $conn->query("SELECT * FROM consumers");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Consumers</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2>Registered Consumers</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Consumer No</th>
    <th>Meter ID</th>
    <th>Name</th>
    <th>Mobile</th>
    <th>Category</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= $row['consumer_no'] ?></td>
    <td><?= $row['meter_id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['mobile'] ?></td>
    <td><?= $row['category'] ?></td>
</tr>
<?php } ?>

</table>

<br>
<a href="admin_dashboard.php">Back</a>

</body>
</html>
