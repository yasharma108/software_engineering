<?php
session_start();
$conn = new mysqli("localhost","yash","yash1204","electricity");

if(!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.html");
    exit();
}

$consumer_result = $conn->query("SELECT * FROM consumer_requests WHERE status='Pending'");
$consumer_pendingCount = $consumer_result->num_rows;

$employee_result = $conn->query("SELECT * FROM employee_requests WHERE status='Pending'");
$employee_pendingCount = $employee_result->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2>Admin Dashboard</h2>

<div class="dashboard">
    <a href="view_consumers.php">
        <div class="dashboard-card">
            <h3>View Consumers</h3>
            <p>See all registered electricity consumers</p>
        </div>
    </a>

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

    <a href="view_employees.php">
        <div class="dashboard-card">
            <h3>View Employees</h3>
            <p>See all approved employees</p>
        </div>
    </a>

    <a href="employee_requests.php">
        <div class="dashboard-card">
            <h3>Approve Employees</h3>
            <p>Review pending employee registration requests</p>
        </div>
    </a>
</div>

<hr>

<h2>Pending Consumer Requests</h2>
<?php if($consumer_pendingCount > 0) { ?>
<table>
    <tr>
        <th>Name</th>
        <th>Mobile</th>
        <th>Category</th>
        <th>Details</th>
        <th>Action</th>
    </tr>
    <?php while($row = $consumer_result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['mobile'] ?></td>
        <td><?= $row['category'] ?></td>
        <td>
            <?php
                if($row['category']=='Commercial') echo "CID: ".$row['cid'];
                elseif($row['category']=='Industrial') echo "IID: ".$row['iid'];
                else echo "Domestic";
            ?>
        </td>
        <td class="pending-actions">
            <form action="approve_consumer.php" method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button class="approve">Approve</button>
            </form>

            <form action="reject_consumer.php" method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button class="reject">Reject</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
<?php } else { ?>
<p style="text-align:center; font-size:18px; color:#475569; margin-top:15px;">
    There are no pending consumer registration requests.
</p>
<?php } ?>

<hr>

<h2>Pending Employee Requests</h2>
<?php if($employee_pendingCount > 0) { ?>
<table>
    <tr>
        <th>Name</th>
        <th>Mobile</th>
        <th>Action</th>
    </tr>
    <?php while($row = $employee_result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['mobile'] ?></td>
        <td class="pending-actions">
            <form action="approve_employee.php" method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button class="approve">Approve</button>
            </form>

            <form action="reject_employee.php" method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button class="reject">Reject</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
<?php } else { ?>
<p style="text-align:center; font-size:18px; color:#475569; margin-top:15px;">
    There are no pending employee registration requests.
</p>
<?php } ?>

</body>
</html>
