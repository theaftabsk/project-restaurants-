<?php include("layout.php"); ?>

<h2>Dashboard</h2>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px">

<div class="card">
<h3>Total Restaurants</h3>
<?php echo mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM restaurants"))[0]; ?>
</div>

<div class="card">
<h3>Total Branches</h3>
<?php echo mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM branches"))[0]; ?>
</div>

<div class="card">
<h3>Total Orders</h3>
<?php echo mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM orders"))[0]; ?>
</div>

<div class="card">
<h3>Total Payments</h3>
<?php echo mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM saas_payments"))[0]; ?>
</div>

</div>

</div></body></html>
