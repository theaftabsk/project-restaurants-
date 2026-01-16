<?php include("layout.php"); ?>

<h2>Orders</h2>

<?php
$q=mysqli_query($conn,"SELECT * FROM orders 
WHERE branch_id=".$_SESSION['branch_id']." ORDER BY id DESC");

while($o=mysqli_fetch_assoc($q)){
echo "
<p>
Order #{$o['id']} – ₹{$o['total_amount']} – {$o['status']}
</p>";
}
?>
