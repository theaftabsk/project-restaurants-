<?php include("layout.php"); ?>

<?php
$uid=$_SESSION['user_id'];
$u=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id=$uid"));
$count=mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total FROM branches WHERE restaurant_id=".$_SESSION['restaurant_id']
));
?>

<h2>Profile</h2>
<p><b>Name:</b> <?= $u['name'] ?></p>
<p><b>Email:</b> <?= $u['email'] ?></p>
<p><b>Total Branch:</b> <?= $count['total'] ?></p>

<a href="../auth/logout.php">Logout</a>

</div></div>
