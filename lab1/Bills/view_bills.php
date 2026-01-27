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
        <title>All Bills</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

    <h2>All Generated Bills</h2>

    <table border="1" cellpadding="10" align="center">
    <tr>
        <th>Bill ID</th>
        <th>Meter ID</th>
        <th>Units</th>
        <th>Amount</th>
        <th>Date</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM bills ORDER BY bill_date DESC");
    while ($row = $result->fetch_assoc()) {
    ?>
    <tr>
        <td><?= $row['bill_id'] ?></td>
        <td><?= $row['meter_id'] ?></td>
        <td><?= $row['units'] ?></td>
        <td>₹<?= $row['amount'] ?></td>
        <td><?= $row['bill_date'] ?></td>
    </tr>
    <?php } ?>

    </table>

    </body>
</html>
