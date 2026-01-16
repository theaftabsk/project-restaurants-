<?php include("layout.php"); ?>

<h2>Menu Management</h2>

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

<select name="visibility" required>
    <option value="">-- Menu Visibility --</option>
    <option value="all">Show in All Branches</option>
    <option value="none">Do Not Show Anywhere</option>
</select>

<button>Add Menu Item</button>
</form>

<?php
if($_SERVER['REQUEST_METHOD']=="POST"){

$rid = $_SESSION['restaurant_id'];
$name = $_POST['name'];
$price = $_POST['price'];
$offer = $_POST['offer_price'] ?: NULL;
$cat = $_POST['category_id'];

/* VISIBILITY LOGIC */
$branch_id = ($_POST['visibility']=="all") ? 0 : "NULL";

/* IMAGE UPLOAD */
$img = NULL;
if(!empty($_FILES['image']['name'])){
    $img = time().$_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'],"../uploads/menu/".$img);
}

mysqli_query($conn,"INSERT INTO menu_items
(restaurant_id,branch_id,category_id,name,price,image,offer_price,status)
VALUES($rid,$branch_id,$cat,'$name','$price','$img','$offer','active')");
}
?>
