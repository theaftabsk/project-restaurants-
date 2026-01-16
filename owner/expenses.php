<?php include("layout.php"); ?>

<h2>Expenses</h2>

<form method="POST">
<input name="amount" placeholder="Amount">
<input name="title" placeholder="Title">
<button>Add</button>
</form>

<?php
if($_POST){
mysqli_query($conn,"INSERT INTO expenses
(restaurant_id,branch_id,amount,title,expense_date)
VALUES({$_SESSION['restaurant_id']},{$_SESSION['branch_id']},
'$_POST[amount]','$_POST[title]',CURDATE())");
}

$q=mysqli_query($conn,"SELECT * FROM expenses WHERE restaurant_id=".$_SESSION['restaurant_id']);
while($e=mysqli_fetch_assoc($q)){
echo "<p>₹{$e['amount']} - {$e['title']}</p>";
}
?>

</div></div>
