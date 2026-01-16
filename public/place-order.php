<?php
include("../config/db.php");

$table_id = intval($_POST['table_id'] ?? 0);
$qtys = $_POST['qty'] ?? [];

if(!$table_id || empty($qtys)){
    die("Invalid Order");
}

/* Table info */
$tq = mysqli_query($conn,"
SELECT * FROM tables WHERE id=$table_id
");
$table = mysqli_fetch_assoc($tq);

$rid = $table['restaurant_id'];
$bid = $table['branch_id'];

/* Create order */
mysqli_query($conn,"
INSERT INTO orders
(restaurant_id,branch_id,table_id,status)
VALUES($rid,$bid,$table_id,'pending')
");

$order_id = mysqli_insert_id($conn);
$total = 0;

/* Order items */
foreach($qtys as $menu_id=>$q){
    if($q > 0){
        $m = mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT * FROM menu_items WHERE id=$menu_id
        "));

        $price = ($m['offer_price'] && $m['offer_price']>0) ?
        $m['offer_price'] : $m['price'];

        $total += $price * $q;

        mysqli_query($conn,"
        INSERT INTO order_items
        (order_id,menu_item_id,quantity,price)
        VALUES($order_id,$menu_id,$q,$price)
        ");
    }
}

/* Update total */
mysqli_query($conn,"
UPDATE orders SET total_amount=$total
WHERE id=$order_id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Placed</title>
<style>
body{
font-family:Arial;
background:#ecfdf5;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}
.box{
background:#fff;
padding:30px;
border-radius:12px;
text-align:center;
}
</style>
</head>
<body>

<div class="box">
<h2>✅ Order Placed</h2>
<p>Your order has been sent to kitchen</p>
<p><b>Total:</b> ₹<?= $total ?></p>
</div>

</body>
</html>
