<?php include("layout.php"); ?>

<h2>Branches</h2>

<table>
<tr>
<th>Restaurant</th><th>Branch</th><th>Manager</th>
</tr>

<?php
$q=mysqli_query($conn,"
SELECT b.name branch,r.name rest,u.email manager
FROM branches b
JOIN restaurants r ON b.restaurant_id=r.id
LEFT JOIN users u ON b.manager_user_id=u.id
");

while($b=mysqli_fetch_assoc($q)){
echo "<tr>
<td>{$b['rest']}</td>
<td>{$b['branch']}</td>
<td>{$b['manager']}</td>
</tr>";
}
?>
</table>

</div></body></html>
