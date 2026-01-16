<?php include("layout.php"); ?>

<h2>Orders</h2>

<form>
<input type="date" name="d">
<button>Filter</button>
</form>

<?php
$sql="SELECT * FROM orders WHERE restaurant_id=".$_SESSION['restaurant_id'];
if(isset($_GET['d'])){
$sql.=" AND DATE(created_at)='".$_GET['d']."'";
}
$q=mysqli_query($conn,$sql);

while($o=mysqli_fetch_assoc($q)){
echo "<p>Order #{$o['id']} ₹{$o['total_amount']}</p>";
}
?>

</div></div>
