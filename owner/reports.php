<?php include("layout.php"); ?>

<h2>Reports</h2>

<?php
$in=mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(total_amount) t FROM orders WHERE restaurant_id=".$_SESSION['restaurant_id']))['t'];

$ex=mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(amount) t FROM expenses WHERE restaurant_id=".$_SESSION['restaurant_id']))['t'];

echo "<p>Total Income: ₹$in</p>";
echo "<p>Total Expense: ₹$ex</p>";
echo "<p>Profit: ₹".($in-$ex)."</p>";
?>

</div></div>
