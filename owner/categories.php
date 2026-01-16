<?php include("layout.php"); ?>

<h2>Categories</h2>

<form method="POST">
<input name="name" placeholder="Category Name">
<button>Add</button>
</form>

<?php
if($_POST){
mysqli_query($conn,"INSERT INTO categories
(restaurant_id,name)
VALUES({$_SESSION['restaurant_id']},'$_POST[name]')");
}

$q=mysqli_query($conn,"SELECT * FROM categories
WHERE restaurant_id=".$_SESSION['restaurant_id']);

while($c=mysqli_fetch_assoc($q)){
echo "<p>{$c['name']}</p>";
}
?>

</div></div>
