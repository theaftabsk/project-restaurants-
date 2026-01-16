<?php include("layout.php"); ?>

<h2>Branch Menu</h2>

<form method="POST" enctype="multipart/form-data" style="max-width:520px">

<input name="name" placeholder="Item Name" required>

<input name="price" placeholder="Price" required>

<input name="offer_price" placeholder="Offer Price (optional)">

<select name="category_id" required>
<option value="">Select Category</option>
<?php
$c=mysqli_query($conn,"SELECT * FROM categories WHERE restaurant_id=".$_SESSION['restaurant_id']);
while($cat=mysqli_fetch_assoc($c)){
    echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
}
?>
</select>

<input type="file" name="image">

<button>Add Menu (This Branch Only)</button>
</form>

<?php
if($_SERVER['REQUEST_METHOD']=="POST"){

$rid = $_SESSION['restaurant_id'];
$bid = $_SESSION['branch_id']; // 🔒 LOCKED
$name = $_POST['name'];
$price = $_POST['price'];
$offer = $_POST['offer_price'] ?: NULL;
$cat = $_POST['category_id'];

/* Image */
$img = NULL;
if(!empty($_FILES['image']['name'])){
    $img = time().$_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'],"../uploads/menu/".$img);
}

/* INSERT */
mysqli_query($conn,"INSERT INTO menu_items
(restaurant_id,branch_id,category_id,name,price,image,offer_price,status)
VALUES($rid,$bid,$cat,'$name','$price','$img','$offer','active')");

echo "<p style='color:green'>Menu added for this branch</p>";
}
?>

<hr>

<h3>Menu List</h3>

<?php
$q=mysqli_query($conn,"
SELECT m.*, c.name cat
FROM menu_items m
JOIN categories c ON m.category_id=c.id
WHERE m.restaurant_id=".$_SESSION['restaurant_id']."
AND (
    m.branch_id=0
    OR m.branch_id=".$_SESSION['branch_id']."
)
ORDER BY m.created_at DESC
");

while($m=mysqli_fetch_assoc($q)){
echo "
<div style='padding:10px;border-bottom:1px solid #ddd'>
<b>{$m['name']}</b> ({$m['cat']})<br>
₹{$m['price']}
".($m['offer_price']?"<span style='color:green'> Offer ₹{$m['offer_price']}</span>":"")."
<br>
<small>
".($m['branch_id']==0 ? "Owner Menu (All Branches)" : "Branch Menu")."
</small>
</div>
";
}
?>

</div></body></html>
