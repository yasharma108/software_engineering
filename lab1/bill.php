<?php
    $conn=new mysqli("localhost", "root", "", "electricity");

    $meter_id=$_POST['meter_id'];
    $units=$_POST['units'];

    $result=$conn->query("Select * from users where meter_id='$meter_id'");

    if($result->num_rows==0)
    {
        echo "Invalid Meter ID";
        exit();
    }
    $user=$result->fetch_assoc();

    $bill=0;
    if($units<=50)
    {
        $bill=$units*1;
    }
    elseif($units<=100)
    {
        $bill=(50*1)+(($units-50)*1.5);
    }
    else
    {
        $bill=(50*1)+(50*1.5)+(($units-100)*2);
    }
?>
<link rel="stylesheet" href="style.css">


<div class="bill-box">
    <h2>Electricity Bill</h2>
    <hr>
    Name: <?php echo $user['name']; ?><br>
    Mobile: <?php echo $user['mobile']; ?><br>
    Address: <?php echo $user['address']; ?><br>
    Meter ID: <?php echo $meter_id; ?><br>
    Units Consumed: <?php echo $units; ?><br>
    <hr>
    <h3>Total Amount: ₹<?php echo $bill; ?></h3>
</div>