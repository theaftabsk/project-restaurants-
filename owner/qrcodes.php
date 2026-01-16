<?php include("layout.php"); ?>

<h2>QR Codes</h2>

<style>
.qr-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
    gap:20px;
}
.qr-card{
    background:#fff;
    border-radius:6px;
    padding:15px;
    text-align:center;
    box-shadow:0 2px 8px rgba(0,0,0,.1);
}
.qr-card img{
    width:120px;
    margin:10px auto;
}
.qr-card b{
    display:block;
    margin-top:5px;
}
</style>

<div class="qr-grid">
<?php
$q=mysqli_query($conn,"SELECT * FROM tables 
WHERE restaurant_id=".$_SESSION['restaurant_id']);

while($t=mysqli_fetch_assoc($q)){
    if($t['qr_code']){
        echo "
        <div class='qr-card'>
            <img src='../{$t['qr_code']}'>
            <b>Table {$t['table_number']}</b>
            <a href='../{$t['qr_code']}' download>Download</a>
        </div>";
    }
}
?>
</div>

</div></div>
