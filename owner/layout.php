<?php
include("../includes/subscription-check.php");
include("../includes/owner-auth.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>Owner Panel</title>

<link rel="stylesheet" href="../assets/css/auth.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
    margin:0;
    font-family: 'Inter', sans-serif;
    background:#f1f5f9;
}

/* Layout */
.wrapper{
    display:flex;
    min-height:100vh;
}

/* Sidebar */
.sidebar{
    width:240px;
    background:#0f172a;
    color:#fff;
    padding:20px 15px;
}

.sidebar h3{
    margin:0 0 20px;
    font-size:20px;
    text-align:center;
    color:#38bdf8;
}

.sidebar a{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 12px;
    margin-bottom:6px;
    text-decoration:none;
    color:#cbd5f5;
    border-radius:8px;
    font-size:14px;
}

.sidebar a:hover{
    background:#1e293b;
    color:#fff;
}

.sidebar a.logout{
    color:#f87171;
}

/* Main */
.main{
    flex:1;
    padding:25px;
}

/* Top bar */
.topbar{
    background:#fff;
    padding:15px 20px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,.05);
    margin-bottom:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.badge{
    padding:6px 12px;
    background:#22c55e;
    color:#fff;
    border-radius:20px;
    font-size:12px;
}
</style>
</head>

<body>

<div class="wrapper">

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>Restaurant SaaS</h3>

    <a href="dashboard.php"><i class="fa fa-chart-line"></i> Dashboard</a>
    <a href="profile.php"><i class="fa fa-user"></i> Profile</a>
    <a href="branches.php"><i class="fa fa-store"></i> Branches</a>
    <a href="menu.php"><i class="fa fa-utensils"></i> Menu</a>
    <a href="categories.php"><i class="fa fa-layer-group"></i> Categories</a>
    <a href="tables.php"><i class="fa fa-chair"></i> Tables</a>
    <a href="qrcodes.php"><i class="fa fa-qrcode"></i> QR Codes</a>
    <a href="orders.php"><i class="fa fa-receipt"></i> Orders</a>
    <a href="expenses.php"><i class="fa fa-wallet"></i> Expenses</a>
    <a href="reports.php"><i class="fa fa-file-alt"></i> Reports</a>

    <!-- PAYMENT -->
    <a href="../payment/" style="background:#2563eb;color:#fff">
        <i class="fa fa-credit-card"></i> Subscription
    </a>

    <a href="../auth/logout.php" class="logout">
        <i class="fa fa-sign-out-alt"></i> Logout
    </a>
</div>

<!-- MAIN -->
<div class="main">

<div class="topbar">
    <b>Welcome, Owner</b>
    <span class="badge">Active</span>
</div>
