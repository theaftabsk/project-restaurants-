<?php
include("layout.php");
include("../phpqrcode/qrlib.php");
?>

<h2>Tables</h2>

<form method="POST">
<input name="table" placeholder="Table Number" required>
<button>Add Table</button>
</form>

<?php
if($_POST){
    $table = $_POST['table'];
    $rid = $_SESSION['restaurant_id'];
    $bid = $_SESSION['branch_id'];

    // Insert table
    mysqli_query($conn,"INSERT INTO tables
    (restaurant_id,branch_id,table_number)
    VALUES($rid,$bid,'$table')");

    $table_id = mysqli_insert_id($conn);

    // QR content (order page)
    $qrText = "https://upgradeachiever.in/public/order.php?table_id=".$table_id;

    // QR path
    $qrFile = "../qrcodes/tables/table_$table_id.png";

    // Generate QR
    QRcode::png($qrText, $qrFile, QR_ECLEVEL_L, 6);

    // Save QR path in DB
    mysqli_query($conn,"UPDATE tables SET
    qr_code='qrcodes/tables/table_$table_id.png'
    WHERE id=$table_id");

    echo "<p style='color:green'>Table added & QR generated</p>";
}
?>
