<?php
session_start();
$conn = new mysqli("localhost","yash","yash1204","electricity");

if(!isset($_SESSION['emp_id'])) {
    header("Location: employeelogin.html");
    exit();
}

// Fetch all bills
$bills_result = $conn->query("SELECT * FROM bills ORDER BY due_date ASC");
$billsCount = $bills_result->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Bills</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2>All Generated Bills</h2>

<?php if($billsCount > 0) { ?>
<table>
    <tr>
        <th>Consumer Name</th>
        <th>Consumer No.</th>
        <th>Meter ID</th>
        <th>Units</th>
        <th>Total Bill</th>
        <th>Due Date</th>
        <th>Status</th>
    </tr>

    <?php while($row = $bills_result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['consumer_name'] ?></td>
        <td><?= $row['consumer_no'] ?></td>
        <td><?= $row['meter_id'] ?></td>
        <td><?= $row['units'] ?></td>
        <td>₹ <?= $row['total_amount'] ?></td>
        <td><?= date('d-m-Y', strtotime($row['due_date'])) ?></td>
        <td>
            <?php
            if($row['status']=='Paid') echo "<span class='status approved'>Paid</span>";
            else echo "<span class='status pending'>Pending</span>";
            ?>
        </td>
    </tr>
    <?php } ?>

</table>
<?php } else { ?>
<p style="text-align:center; font-size:18px; color:#475569; margin-top:15px;">
    No bills have been generated yet.
</p>
<?php } ?>

</body>
</html>
