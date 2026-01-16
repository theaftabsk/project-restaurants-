<?php
include("../config/db.php");
$msg="";
$email=$_GET['email'];

if($_SERVER['REQUEST_METHOD']=="POST"){
    $otp=$_POST['otp'];
    $pass=password_hash($_POST['password'],PASSWORD_DEFAULT);

    $q=mysqli_query($conn,"SELECT id FROM users
        WHERE email='$email' AND otp_code='$otp'
        AND otp_expires_at>NOW()");

    if(mysqli_num_rows($q)==1){
        mysqli_query($conn,"UPDATE users SET
        password='$pass', otp_code=NULL
        WHERE email='$email'");
        header("Location: login.php");
        exit;
    }
    $msg="Invalid OTP";
}
?>
<link rel="stylesheet" href="../assets/css/auth.css">
<div class="auth-box">
<h2>Reset Password</h2>
<div class="msg"><?= $msg ?></div>
<form method="POST">
<input name="otp" placeholder="OTP" required>
<input name="password" type="password" placeholder="New Password" required>
<button>Reset</button>
</form>
</div>
