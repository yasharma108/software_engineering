<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    die("Access denied");
}

$conn = new mysqli("localhost", "root", "", "electricity");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>All Consumers</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

    <h2>Registered Consumers</h2>

    <table border="1" cellpadding="10" align="center">
    <tr>
        <th>Meter ID</th>
        <th>Name</th>
        <th>Mobile</th>
        <th>Category</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM consumers");
    while ($row = $result->fetch_assoc()) {
    ?>
    <tr>
        <td><?= $row['meter_id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['mobile'] ?></td>
        <td><?= $row['category'] ?></td>
    </tr>
    <?php } ?>

    </table>

    </body>
</html>
