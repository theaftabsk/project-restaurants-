<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='owner'){
    die("Unauthorized access");
}

$payment_id = $_GET['pid'] ?? '';
$order_id   = $_GET['oid'] ?? '';
$amount     = intval($_GET['amt'] ?? 0);

if(!$payment_id || !$order_id || !$amount){
    die("Invalid payment data");
}

$plan = ($amount == 9999) ? 'yearly' : 'monthly';

$rid = $_SESSION['restaurant_id'];
$uid = $_SESSION['user_id'];

/* Save payment */
mysqli_query($conn,"
INSERT INTO saas_payments
(restaurant_id,user_id,payment_id,order_id,amount,plan,status)
VALUES
($rid,$uid,'$payment_id','$order_id',$amount,'$plan','success')
");

/* Subscription expiry */
if($plan=='monthly'){
    $expiry = date("Y-m-d", strtotime("+30 days"));
}else{
    $expiry = date("Y-m-d", strtotime("+365 days"));
}

/* Update restaurant */
mysqli_query($conn,"
UPDATE restaurants SET
subscription_plan='$plan',
subscription_expiry='$expiry'
WHERE id=$rid
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Success</title>
<style>
body{
    font-family:Arial;
    background:#ecfdf5;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.card{
    background:#fff;
    padding:35px;
    border-radius:14px;
    text-align:center;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
}
.card h2{color:#16a34a}
.card a{
    display:inline-block;
    margin-top:20px;
    padding:12px 26px;
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    border-radius:8px;
}
</style>
</head>
<body>

<div class="card">
<h2>✅ Payment Successful</h2>
<p><b><?= ucfirst($plan) ?></b> subscription activated</p>
<p>Amount Paid: ₹<?= $amount ?></p>
<a href="../owner/dashboard.php">Go to Dashboard</a>
</div>

</body>
</html>
