<?php include("layout.php"); ?>

<h2>Branch Dashboard</h2>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">

<div class="card">Orders<br>
<b>
<?php
$q=mysqli_query($conn,"SELECT COUNT(*) c FROM orders 
WHERE branch_id=".$_SESSION['branch_id']);
echo mysqli_fetch_assoc($q)['c'];
?>
</b>
</div>

<div class="card">Today Sales ₹
<?php
$q=mysqli_query($conn,"SELECT SUM(total_amount) s FROM orders 
WHERE branch_id=".$_SESSION['branch_id']." AND DATE(created_at)=CURDATE()");
echo mysqli_fetch_assoc($q)['s'] ?? 0;
?>
</div>

<div class="card">Tables<br>
<?php
$q=mysqli_query($conn,"SELECT COUNT(*) c FROM tables 
WHERE branch_id=".$_SESSION['branch_id']);
echo mysqli_fetch_assoc($q)['c'];
?>
</div>

</div>
