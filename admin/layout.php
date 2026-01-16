<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php"); exit;
}
include("../config/db.php");
?>
<!DOCTYPE html>
<html>
<head>
<style>
body{margin:0;font-family:Arial;background:#f1f5f9}
.sidebar{
width:220px;position:fixed;height:100%;
background:#020617;color:#fff;padding:20px
}
.sidebar a{color:#fff;display:block;padding:10px 0;text-decoration:none}
.sidebar a:hover{color:#38bdf8}
.main{margin-left:240px;padding:20px}
.card{background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,.1)}
table{width:100%;border-collapse:collapse}
th,td{padding:10px;border-bottom:1px solid #ddd}
</style>
</head>
<body>

<div class="sidebar">
<h3>SaaS Admin</h3>
<a href="dashboard.php">Dashboard</a>
<a href="restaurants.php">Restaurants</a>
<a href="branches.php">Branches</a>
<a href="payments.php">Payments</a>
<a href="logout.php" style="color:#f87171">Logout</a>
</div>

<div class="main">
