<?php
session_start();
include("../config/db.php");

$msg="";

if ($_SERVER['REQUEST_METHOD']=="POST"){

    $email=$_POST['email'];
    $password=$_POST['password'];

    $q=mysqli_query($conn,"SELECT * FROM users 
        WHERE email='$email' AND email_verified_at IS NOT NULL");

    if($u=mysqli_fetch_assoc($q)){
        if(password_verify($password,$u['password'])){

            $_SESSION['user_id']=$u['id'];
            $_SESSION['role']=$u['role'];
            $_SESSION['restaurant_id']=$u['restaurant_id'];
            $_SESSION['branch_id']=$u['branch_id'];

            if($u['role']=="owner"){
                if($u['restaurant_id']==NULL){
                    header("Location: ../owner/create-restaurant.php");
                } else {
                    header("Location: ../owner/dashboard.php");
                }
            }
            if($u['role']=="branch_manager") header("Location: ../branch/dashboard.php");
            if($u['role']=="kitchen") header("Location: ../kitchen/dashboard.php");
            exit;
        }
    }
    $msg="Invalid login";
}
?>
<link rel="stylesheet" href="../assets/css/auth.css">
<div class="auth-box">
<h2>Login</h2>
<div class="msg"><?= $msg ?></div>
<form method="POST">
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>
<button>Login</button>
</form>
<a href="forgot-password.php">Forgot password?</a>
</div>
