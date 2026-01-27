<?php
session_start();
$conn = new mysqli("localhost","yash","yash1204","electricity");

if(!isset($_SESSION['emp_id'])) {
    header("Location: employee_login.html");
    exit();
}

$bills_result = $conn->query("SELECT * FROM bills ORDER BY date_generated DESC LIMIT 5");
$billsCount = $bills_result->num_rows;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="employee.css"> 
</head>
<body>

<h2>Employee Dashboard</h2>

<div class="dashboard">
    <a href="../Bills/generatebill.php">
        <div class="dashboard-card">
            <h3>Generate Bill</h3>
            <p>Create electricity bills for consumers</p>
        </div>
    </a>

    <a href="../Bills/view_bills.php">
        <div class="dashboard-card">
            <h3>View Bills</h3>
            <p>See all generated bills and payment status</p>
        </div>
    </a>
</div>

<hr>

<h2>Latest Bills</h2>

<?php if($billsCount > 0) { ?>
<table>
    <tr>
        <th>Consumer Name</th>
        <th>Consumer No.</th>
        <th>Meter ID</th>
        <th>Units</th>
        <th>Total Bill</th>
        <th>Date</th>
        <th>Status</th>
    </tr>

    <?php while($row = $bills_result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['consumer_name'] ?></td>
        <td><?= $row['consumer_no'] ?></td>
        <td><?= $row['meter_id'] ?></td>
        <td><?= $row['units'] ?></td>
        <td>₹ <?= $row['total_amount'] ?></td>
        <td><?= date('d-m-Y', strtotime($row['date_generated'])) ?></td>
        <td>
            <?php if($row['status']=='Paid') echo "<span class='status approved'>Paid</span>";
                  else echo "<span class='status pending'>Pending</span>"; ?>
        </td>
    </tr>
    <?php } ?>
</table>
<?php } else { ?>
<p style="text-align:center; font-size:18px; color:#475569; margin-top:20px;">
    No bills have been generated yet.
</p>
<?php } ?>

</body>
</html>
