<?php
$conn = new mysqli("localhost","yash","yash1204","electricity");

$id = $_POST['id'];

do {
    $consumer_no = rand(10000, 999999);
    $check = $conn->query(
        "SELECT consumer_no FROM consumers WHERE consumer_no='$consumer_no'"
    );
} while ($check->num_rows > 0);

function generateMeterID() {
    return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 7);
}

do {
    $meter_id = generateMeterID();
    $check2 = $conn->query(
        "SELECT meter_id FROM consumers WHERE meter_id='$meter_id'"
    );
} while ($check2->num_rows > 0);

$request = $conn->query(
    "SELECT * FROM consumer_requests WHERE id=$id"
)->fetch_assoc();

$conn->query("
    INSERT INTO consumers
    (consumer_no, meter_id, name, mobile, address, category, cid, iid)
    VALUES
    ('$consumer_no','$meter_id',
     '{$request['name']}','{$request['mobile']}',
     '{$request['address']}','{$request['category']}',
     '{$request['cid']}','{$request['iid']}')
");

$conn->query(
    "DELETE FROM consumer_requests WHERE id=$id"
);

header("Location: admin_dashboard.php");
exit();