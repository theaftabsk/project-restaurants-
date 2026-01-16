<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner'){
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Choose Subscription</title>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<style>
body{
    font-family:Arial;
    background:#f8fafc;
}
.container{
    max-width:900px;
    margin:auto;
    padding:30px;
}
h2{text-align:center;margin-bottom:30px}
.plans{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:20px;
}
.plan{
    background:#fff;
    border-radius:10px;
    padding:25px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}
.plan h3{font-size:22px}
.plan p{font-size:18px;color:#555}
.plan button{
    background:#2563eb;
    color:#fff;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
    margin-top:15px;
}
.plan button:hover{background:#1e40af}
</style>
</head>

<body>

<div class="container">
<h2>Choose Subscription Plan</h2>

<div class="plans">

<div class="plan">
    <h3>Monthly Plan</h3>
    <p>₹999 / Month</p>
    <button onclick="pay(999)">Subscribe</button>
</div>

<div class="plan">
    <h3>Yearly Plan</h3>
    <p>₹9999 / Year</p>
    <button onclick="pay(9999)">Subscribe</button>
</div>

</div>
</div>

<script>
function pay(amount){
    fetch("create-order.php?amount="+amount)
    .then(res => res.json())
    .then(data => {

        var options = {
            key: data.key,
            amount: data.amount,
            currency: "INR",
            name: "Restaurant SaaS",
            description: "Subscription Payment",
            order_id: data.order_id,
            handler: function (response){
                window.location =
                "payment-success.php?pid="+response.razorpay_payment_id+
                "&oid="+response.razorpay_order_id+
                "&amt="+amount;
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    });
}
</script>

</body>
</html>
