<?php include("layout.php"); ?>

<h2>Branches</h2>
<a href="branch-add.php">➕ Add Branch</a>

<table border="1" width="100%" cellpadding="8">
<tr>
    <th>Name</th>
    <th>Manager</th>
    <th>Action</th>
</tr>

<?php
$q = mysqli_query($conn,"
    SELECT b.*, u.email AS manager_email
    FROM branches b
    LEFT JOIN users u ON b.manager_user_id = u.id
    WHERE b.restaurant_id = ".$_SESSION['restaurant_id']
);

while($b = mysqli_fetch_assoc($q)){
?>
<tr>
    <td><?= htmlspecialchars($b['name']) ?></td>
    <td><?= htmlspecialchars($b['manager_email'] ?? 'Not Assigned') ?></td>
    <td>
        <a href="change-manager.php?id=<?= $b['id'] ?>">Manager</a> |
        <a href="change-kitchen.php?id=<?= $b['id'] ?>">Kitchen</a>
    </td>
</tr>
<?php } ?>

</table>
