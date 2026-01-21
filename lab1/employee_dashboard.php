<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    die("Access Denied");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div>Employee Dashboard</div>
    <div>
        <a href="logout.php">Logout</a>
    </div>
</div>

<p><b>Welcome Employee: </b> <?php echo $_SESSION['employee_id']; ?></p>

<div class="home-buttons">
    <a href="generatebill.php"><button>Generate Bill</button></a>
    <a href="view_all_bills.php"><button>View All Bills</button></a>
</div>

</body>
</html>
