<?php include("layout.php"); ?>

<h2>Payments</h2>

<table>
<tr>
<th>Restaurant</th><th>Amount</th><th>Date</th>
</tr>

<?php
$q=mysqli_query($conn,"
SELECT p.*,r.name
FROM saas_payments p
JOIN restaurants r ON p.restaurant_id=r.id
ORDER BY p.created_at DESC
");

while($p=mysqli_fetch_assoc($q)){
echo "<tr>
<td>{$p['name']}</td>
<td>₹{$p['amount']}</td>
<td>{$p['created_at']}</td>
</tr>";
}
?>
</table>

</div></body></html>
