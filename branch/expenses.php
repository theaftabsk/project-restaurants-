<?php include("layout.php"); ?>

<h2>Expenses</h2>

<form method="POST">
<input name="title" placeholder="Expense Title" required>
<input name="amount" placeholder="Amount" required>
<button>Add</button>
</form>

<?php
if($_POST){
mysqli_query($conn,"INSERT INTO expenses
(restaurant_id,branch_id,title,amount,expense_date)
VALUES({$_SESSION['restaurant_id']},{$_SESSION['branch_id']},
'$_POST[title]','$_POST[amount]',CURDATE())");
}

$q=mysqli_query($conn,"SELECT * FROM expenses WHERE branch_id=".$_SESSION['branch_id']);
while($e=mysqli_fetch_assoc($q)){
echo "<p>{$e['title']} – ₹{$e['amount']}</p>";
}
?>
