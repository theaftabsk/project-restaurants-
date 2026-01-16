<?php
include("../config/db.php");

$table_id = intval($_GET['table_id'] ?? 0);
if(!$table_id){
    die("Invalid Table");
}

/* Table + branch */
$tq = mysqli_query($conn,"
SELECT t.*, b.id branch_id, b.restaurant_id
FROM tables t
JOIN branches b ON t.branch_id=b.id
WHERE t.id=$table_id
");

if(mysqli_num_rows($tq)==0){
    die("Table not found");
}

$table = mysqli_fetch_assoc($tq);
$rid = $table['restaurant_id'];
$bid = $table['branch_id'];

/* Categories */
$cats = mysqli_query($conn,"
SELECT * FROM categories
WHERE restaurant_id=$rid
");

/* Menu (global + branch specific) */
$menu = mysqli_query($conn,"
SELECT * FROM menu_items
WHERE restaurant_id=$rid
AND status='active'
AND (branch_id IS NULL OR branch_id=0 OR branch_id=$bid)
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Menu</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
body{font-family:Arial;background:#f9fafb;margin:0}
.header{background:#2563eb;color:#fff;padding:15px;text-align:center}
.card{background:#fff;margin:12px;padding:12px;border-radius:10px}
.menu-item{display:flex;justify-content:space-between;align-items:center}
.qty{width:45px;padding:6px}
button{
    width:100%;
    padding:14px;
    background:#16a34a;
    color:#fff;
    border:none;
    border-radius:10px;
    font-size:16px;
    margin:15px 0;
}
</style>
</head>
<body>

<div class="header">
<h2>Table <?= htmlspecialchars($table['table_number']) ?></h2>
</div>

<form action="place-order.php" method="POST">
<input type="hidden" name="table_id" value="<?= $table_id ?>">

<?php
$current_cat = "";
while($m=mysqli_fetch_assoc($menu)){
    if($m['category_id'] != $current_cat){
        $current_cat = $m['category_id'];
        $cat = mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT name FROM categories WHERE id=".$m['category_id']
        ));
        echo "<h3 style='margin-left:12px'>{$cat['name']}</h3>";
    }
?>
<div class="card">
<div class="menu-item">
<div>
<b><?= htmlspecialchars($m['name']) ?></b><br>
₹<?= $m['offer_price'] && $m['offer_price']>0 ? 
$m['offer_price'] : $m['price'] ?>
</div>

<input type="number" name="qty[<?= $m['id'] ?>]"
class="qty" min="0" value="0">
</div>
</div>
<?php } ?>

<button type="submit">Place Order</button>
</form>

</body>
</html>
