<?php
session_start();
$conn = new mysqli("localhost","yash","yash1204","electricity");

if(!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.html");
    exit();
}

$result = $conn->query("SELECT * FROM employee_requests WHERE status='Pending'");
$pendingCount = $result->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pending Employee Requests</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h2>Pending Employee Requests</h2>

<?php if($pendingCount > 0) { ?>
<table>
    <tr>
        <th>Name</th>
        <th>Mobile</th>
        <th>Action</th>
    </tr>

    <?php while($row = $result->fetch_assoc()) { ?>
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
<p style="text-align:center; font-size:18px; color:#475569; margin-top:30px;">
    There are no pending employee registration requests.
</p>
<?php } ?>

</body>
</html>
