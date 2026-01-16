<?php include("layout.php"); ?>

<h2>Restaurants</h2>

<table>
<tr>
<th>Name</th><th>Owner</th><th>Phone</th><th>Created</th>
</tr>

<?php
$q=mysqli_query($conn,"
SELECT r.*,u.email owner_email
FROM restaurants r
JOIN users u ON r.owner_id=u.id
ORDER BY r.created_at DESC
");
while($r=mysqli_fetch_assoc($q)){
echo "<tr>
<td>{$r['name']}</td>
<td>{$r['owner_email']}</td>
<td>{$r['phone']}</td>
<td>{$r['created_at']}</td>
</tr>";
}
?>
</table>

</div></body></html>
