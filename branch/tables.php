<?php
include("layout.php");
include("../phpqrcode/qrlib.php");
?>

<h2>Tables & QR Codes</h2>

<form method="POST" style="max-width:300px">
    <input name="table" placeholder="Table Number" required>
    <button>Add Table</button>
</form>

<?php
if($_SERVER['REQUEST_METHOD']=="POST"){

    $table = mysqli_real_escape_string($conn,$_POST['table']);
    $rid = $_SESSION['restaurant_id'];
    $bid = $_SESSION['branch_id'];

    // Insert table
    mysqli_query($conn,"INSERT INTO tables
    (restaurant_id,branch_id,table_number)
    VALUES($rid,$bid,'$table')");

    $table_id = mysqli_insert_id($conn);

    // QR content
    $qrText = "https://upgradeachiever.in/public/order.php?table_id=".$table_id;

    // QR file path
    $qrDir = "../qrcodes/tables/";
    if(!is_dir($qrDir)){
        mkdir($qrDir,0777,true);
    }

    $qrFile = $qrDir."table_".$table_id.".png";

    // Generate QR
    QRcode::png($qrText, $qrFile, QR_ECLEVEL_L, 6);

    // Save QR path
    mysqli_query($conn,"UPDATE tables SET
    qr_code='qrcodes/tables/table_$table_id.png'
    WHERE id=$table_id");

    echo "<p style='color:green'>Table added & QR generated</p>";
}
?>

<hr>

<h3>Generated QR Codes</h3>

<style>
.qr-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
    gap:20px;
    margin-top:20px;
}
.qr-card{
    background:#fff;
    border-radius:10px;
    padding:15px;
    text-align:center;
    box-shadow:0 4px 10px rgba(0,0,0,.1);
}
.qr-card img{
    width:130px;
    margin:10px auto;
}
.qr-card a{
    display:inline-block;
    margin-top:5px;
    color:#2563eb;
    font-weight:600;
}
</style>

<div class="qr-grid">
<?php
$q=mysqli_query($conn,"SELECT * FROM tables
WHERE branch_id=".$_SESSION['branch_id']);

while($t=mysqli_fetch_assoc($q)){
    if($t['qr_code']){
        echo "
        <div class='qr-card'>
            <img src='../{$t['qr_code']}' alt='QR'>
            <b>Table {$t['table_number']}</b><br>
            <a href='../{$t['qr_code']}' download>Download QR</a>
        </div>";
    }
}
?>
</div>

</div>
</body>
</html>
