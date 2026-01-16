<?php
session_start();
include("../config/db.php");

if($_SERVER['REQUEST_METHOD']=="POST"){
    $email=$_POST['email'];
    $pass=$_POST['password'];

    $q=mysqli_query($conn,"SELECT * FROM admins WHERE email='$email'");
    if($a=mysqli_fetch_assoc($q)){
        if(password_verify($pass,$a['password'])){
            $_SESSION['admin_id']=$a['id'];
            header("Location: dashboard.php");
            exit;
        }
    }
    $err="Invalid login";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<style>
body{background:#0f172a;font-family:Arial}
.box{width:350px;margin:120px auto;background:#fff;padding:25px;border-radius:10px}
input,button{width:100%;padding:12px;margin:8px 0}
button{background:#2563eb;color:#fff;border:none}
</style>
</head>
<body>
<div class="box">
<h2>Admin Login</h2>
<form method="POST">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button>Login</button>
</form>
<p style="color:red"><?= $err??"" ?></p>
</div>
</body>
</html>
