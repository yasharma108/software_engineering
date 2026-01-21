<?php
    session_start();

    if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','employee'])) {
        die("Access denied");
    }

    $conn = new mysqli("localhost", "root", "", "electricity");
    if($conn->connect_error) die("Database connection failed");

    $bill_generated = false;
    $user = null;
    $units = 0;
    $total_amount = 0;
    $bill_date = '';
    $due_date = '';

    if(isset($_POST['meter_id']) && isset($_POST['current_reading'])) {
        $meter_id = $_POST['meter_id'];
        $current_reading = (int)$_POST['current_reading'];

        $user = $conn->query("SELECT * FROM consumers WHERE meter_id='$meter_id'")->fetch_assoc();
        
        if(!$user){
            $error = "No consumer found with Meter ID: $meter_id";
        } else {
            $prev_reading = $user['prev_reading'] ?? 0; 
            $units = max(0, $current_reading - $prev_reading); 

            $category = $user['category'];

            $prev_due = 0;
            $prev_bills = $conn->query("SELECT total_amount, due_date, paid FROM bills WHERE meter_id='$meter_id' AND paid=0");
            while($row = $prev_bills->fetch_assoc()){
                $prev_due += $row['total_amount'];
                if(strtotime($row['due_date']) < time()) $prev_due += 100; 
            }

            function calculateBill($units, $category){
                $bill = 0;
                if($category=='Domestic'){
                    if($units<=50) $bill = $units*1;
                    elseif($units<=100) $bill = 50*1 + ($units-50)*1.5;
                    else $bill = 50*1 + 50*1.5 + ($units-100)*2;
                } elseif($category=='Commercial'){
                    if($units<=50) $bill = $units*2;
                    elseif($units<=100) $bill = 50*2 + ($units-50)*3;
                    else $bill = 50*2 + 50*3 + ($units-100)*5;
                } elseif($category=='Industrial'){
                    if($units<=50) $bill = $units*3;
                    elseif($units<=100) $bill = 50*3 + ($units-50)*4;
                    else $bill = 50*3 + 50*4 + ($units-100)*6;
                }
                return $bill;
            }

            $current_bill = calculateBill($units, $category);
            $total_amount = $current_bill + $prev_due;

            $bill_date = date("Y-m-d");
            $due_date = date("Y-m-d", strtotime("+10 days"));

            $conn->query("INSERT INTO bills (meter_id, units, bill_date, due_date, total_amount, paid)
                        VALUES ('$meter_id', $units, '$bill_date', '$due_date', $total_amount, 0)");

            $conn->query("UPDATE consumers SET prev_reading=$current_reading WHERE meter_id='$meter_id'");

            $bill_generated = true;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Generate Bill</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

    <h2>Generate Electricity Bill</h2>

    <form method="post">
        <label for="meter_id">Meter ID:</label>
        <input type="text" name="meter_id" id="meter_id" required>

        <label for="current_reading">Current Meter Reading:</label>
        <input type="number" name="current_reading" id="current_reading" min="0" required>

        <input type="submit" value="Generate Bill">
    </form>

    <?php if(isset($error)): ?>
        <p style="text-align:center; color:red; font-weight:bold;"><?= $error ?></p>
    <?php endif; ?>

    <?php if($bill_generated && $user): ?>
    <div class="bill-box">
        <h2>Electricity Bill</h2>
        <hr>
        <b>Name:</b> <?= htmlspecialchars($user['name']) ?><br>
        <b>Mobile:</b> <?= htmlspecialchars($user['mobile']) ?><br>
        <b>Address:</b> <?= htmlspecialchars($user['address']) ?><br>
        <b>Meter ID:</b> <?= htmlspecialchars($user['meter_id']) ?><br>
        <b>Category:</b> <?= htmlspecialchars($user['category']) ?><br>
        <b>Previous Reading:</b> <?= $prev_reading ?><br>
        <b>Current Reading:</b> <?= $current_reading ?><br>
        <b>Units Consumed:</b> <?= $units ?><br>
        <b>Bill Date:</b> <?= $bill_date ?><br>
        <b>Due Date:</b> <?= $due_date ?><br>
        <?php if($prev_due > 0): ?>
            <b>Previous Dues + Fine:</b> ₹<?= $prev_due ?><br>
        <?php endif; ?>
        <hr>
        <h3>Total Amount Payable: ₹<?= $total_amount ?></h3>
    </div>
    <?php endif; ?>

    </body>
</html>
