<?php
session_start();
include("../config/db.php");

// Branch login check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'branch_manager'){
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Branch Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body{
            margin:0;
            font-family:Arial, Helvetica, sans-serif;
            background:#f1f5f9;
        }
        .sidebar{
            width:220px;
            background:#1e293b;
            color:#fff;
            height:100vh;
            position:fixed;
            padding:15px;
        }
        .sidebar h3{
            margin-top:0;
            text-align:center;
        }
        .sidebar a{
            display:block;
            padding:8px;
            color:#fff;
            text-decoration:none;
            border-radius:6px;
            margin-bottom:5px;
        }
        .sidebar a:hover{
            background:#334155;
        }
        .sidebar a.logout{
            color:#f87171;
        }
        .main{
            margin-left:240px;
            padding:20px;
        }
        .card{
            background:#fff;
            padding:15px;
            border-radius:10px;
            box-shadow:0 4px 10px rgba(0,0,0,0.08);
            margin-bottom:15px;
        }
        button{
            padding:10px 15px;
            background:#2563eb;
            color:#fff;
            border:none;
            border-radius:6px;
            cursor:pointer;
        }
        input{
            width:100%;
            padding:8px;
            margin:6px 0;
            border:1px solid #ccc;
            border-radius:6px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>Branch Panel</h3>
    <a href="dashboard.php">Dashboard</a>
    <a href="menu.php">Menu</a>
    <a href="tables.php">Tables</a>
    <a href="orders.php">Orders</a>
    <a href="expenses.php">Expenses</a>
    <a href="reports.php">Reports</a>
    <a href="../auth/logout.php" class="logout">Logout</a>
</div>

<div class="main">
