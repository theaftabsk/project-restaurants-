<?php
session_start();
include("../config/db.php");
$rid = (int)$_SESSION['restaurant_id'];
$bid = (int)$_SESSION['branch_id'];

$q = mysqli_query($conn, "
    SELECT COUNT(*) AS c 
    FROM orders 
    WHERE restaurant_id=$rid AND branch_id=$bid AND status='pending'
");
echo (int)mysqli_fetch_assoc($q)['c'];
