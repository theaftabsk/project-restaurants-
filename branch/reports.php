<?php include("layout.php"); ?>

<h2>Branch Report</h2>

<?php
$q=mysqli_query($conn,"
SELECT 
SUM(total_amount) total,
COUNT(*) orders
FROM orders 
WHERE branch_id=".$_SESSION['branch_id']);

$r=mysqli_fetch_assoc($q);

echo "
<p>Total Orders: {$r['orders']}</p>
<p>Total Sales: ₹{$r['total']}</p>
";
?>
