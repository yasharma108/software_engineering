<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access Denied");
}
$conn = new mysqli("localhost", "root", "", "electricity");
if ($conn->connect_error) {
    die("Database connection failed");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div>Admin Dashboard</div>
    <div>
        <a href="logout.php">Logout</a>
    </div>
</div>

<p><b>Welcome Admin: </b> <?php echo $_SESSION['admin_id']; ?></p>

<div class="home-buttons">
    <a href="view_consumers.php"><button>View Consumers</button></a>
    <a href="pending_requests.php"><button>Approve Consumer Requests</button></a>
    <a href="generatebill.php"><button>Generate Bill</button></a>
    <a href="view_all_bills.php"><button>View All Bills</button></a>
</div>

</body>
</html>
