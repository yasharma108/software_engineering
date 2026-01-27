<?php
    session_start();
    if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        die("Access Denied");
    }

    $conn = new mysqli("localhost", "root", "", "electricity");
    if($conn->connect_error) die("Database connection failed");

    if(isset($_POST['action']) && isset($_POST['req_id'])) 
        {
        $id = (int)$_POST['req_id'];
        $action = $_POST['action'];

        if($action == 'approve') 
            {

            $row = $conn->query("SELECT * FROM consumer_requests WHERE request_id=$id")->fetch_assoc();

            function generateMeterId($conn) {
                do {
                    $meter_id = "EM" . rand(100000, 999999); // EM + 6 digits
                    $check = $conn->query("SELECT meter_id FROM consumers WHERE meter_id='$meter_id'");
                } while($check->num_rows > 0);
                return $meter_id;
            }
            $meter_id = generateMeterId($conn);

            $conn->query("INSERT INTO consumers (meter_id,name,mobile,address,category)
                        VALUES ('$meter_id','{$row['name']}','{$row['mobile']}','{$row['address']}','{$row['category']}')");

            $conn->query("UPDATE consumer_requests SET status='Approved', meter_id='$meter_id' WHERE request_id=$id");

        } 
        elseif($action == 'reject') 
        {
            $conn->query("UPDATE consumer_requests SET status='Rejected' WHERE request_id=$id");
        }

        header("Location: pending_requests.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pending Consumer Requests</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

    <h2>Pending Consumer Requests</h2>

    <table border="1" cellpadding="10" align="center">
    <tr>
        <th>Name</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Category</th>
        <th>Action</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM consumer_requests WHERE status='Pending'");
    while($row = $result->fetch_assoc()) {
    ?>
    <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['mobile'] ?></td>
        <td><?= $row['address'] ?></td>
        <td><?= $row['category'] ?></td>
        <td>
            <form method="post" class="pending-actions">
                <input type="hidden" name="req_id" value="<?= $row['request_id'] ?>">
                <button type="submit" name="action" value="approve" class="approve">Approve</button>
                <button type="submit" name="action" value="reject" class="reject">Reject</button>
            </form>
        </td>
    </tr>
    <?php } ?>
    </table>

    </body>
</html>
