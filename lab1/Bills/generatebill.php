<?php
session_start();
$conn = new mysqli("localhost","yash","yash1204","electricity");

if(!isset($_SESSION['role'])) {
    header("Location: login.html");
    exit();
}

$message = "";
$bill_generated = false;

if(isset($_POST['generate'])) {

    $consumer_no = $conn->real_escape_string($_POST['consumer_no']);
    $current_reading = (int)$_POST['current_reading'];

    $consumer = $conn->query("SELECT * FROM consumers WHERE consumer_no='$consumer_no'")->fetch_assoc();

    if(!$consumer) {
        $message = "Consumer not found!";
    } else {
        $prev_reading = (int)$consumer['last_reading']; 
        $units = $current_reading - $prev_reading;

        if($units < 0) {
            $message = "Current reading cannot be less than previous reading!";
        } else {

            $category = $consumer['category'];
            switch($category){
                case 'Domestic':
                    $rates = [50 => 1.5, 50 => 2.5, 50 => 3.5]; // first, second, third 50
                    $extra_rate = 4.5;
                    $min_charge = 25;
                    $fine = 150;
                    break;
                case 'Commercial':
                    $rates = [50 => 3, 50 => 5, 50 => 7];
                    $extra_rate = 10;
                    $min_charge = 50;
                    $fine = 300;
                    break;
                case 'Industrial':
                    $rates = [50 => 5, 50 => 7, 50 => 10];
                    $extra_rate = 15;
                    $min_charge = 100;
                    $fine = 500;
                    break;
            }

            $total_amount = 0;
            $remaining_units = $units;
            $i = 0;
            foreach($rates as $limit => $rate) {
                if($remaining_units <= 0) break;
                $used_units = min($limit, $remaining_units);
                $total_amount += $used_units * $rate;
                $remaining_units -= $used_units;
                $i++;
            }
            if($remaining_units > 0) {
                $total_amount += $remaining_units * $extra_rate;
            }

            if($units == 0) $total_amount = $min_charge;

            $date_generated = date('Y-m-d');
            $due_date = date('Y-m-d', strtotime('+7 days'));
            $total_with_prev = $total_amount + $consumer['pending_amount']; 

            $stmt = $conn->prepare("INSERT INTO bills (consumer_no, consumer_name, meter_id, prev_reading, current_reading, units, total_amount, date_generated, due_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
            $stmt->bind_param("sssiiiids", $consumer['consumer_no'], $consumer['name'], $consumer['meter_id'], $prev_reading, $current_reading, $units, $total_with_prev, $date_generated, $due_date);
            $stmt->execute();

            $conn->query("UPDATE consumers SET last_reading=$current_reading, pending_amount=$total_with_prev WHERE consumer_no='{$consumer['consumer_no']}'");

            $bill_generated = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Bill</title>
    <link rel="stylesheet" href="generatebill.css">
</head>
<body>

<h2>Generate Electricity Bill</h2>

<form method="post">
    <label>Consumer Number:</label>
    <input type="text" name="consumer_no" required>

    <label>Current Meter Reading:</label>
    <input type="number" name="current_reading" required min="0">

    <input type="submit" name="generate" value="Generate Bill">
</form>

<?php if($message != ""): ?>
    <p style="color:red; text-align:center;"><?= $message ?></p>
<?php endif; ?>

<?php if($bill_generated): ?>
    <div class="bill-box">
        <h3>Electricity Bill</h3>
        <p><b>Consumer Name:</b> <?= $consumer['name'] ?></p>
        <p><b>Consumer Number:</b> <?= $consumer['consumer_no'] ?></p>
        <p><b>Meter ID:</b> <?= $consumer['meter_id'] ?></p>
        <p><b>Date:</b> <?= $date_generated ?></p>
        <p><b>Previous Reading:</b> <?= $prev_reading ?></p>
        <p><b>Current Reading:</b> <?= $current_reading ?></p>
        <p><b>Units Consumed:</b> <?= $units ?></p>
        <p><b>Previous Pending:</b> ₹ <?= $consumer['pending_amount'] ?></p>
        <p><b>Total Bill:</b> ₹ <?= $total_with_prev ?></p>
        <p><b>Due Date (without fine):</b> <?= $due_date ?></p>
        <p><b>After Due Date with Fine (₹<?= $fine ?>):</b> ₹ <?= $total_with_prev + $fine ?></p>
    </div>
<?php endif; ?>

</body>
</html>
