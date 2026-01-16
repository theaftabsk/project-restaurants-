<?php
session_start();
include("../config/db.php");

$email=$_GET['email'] ?? '';
$msg="";

if($_SERVER['REQUEST_METHOD']=="POST"){
    $otp=$_POST['otp'];

    $q=mysqli_query($conn,"SELECT * FROM users 
        WHERE email='$email' AND otp_code='$otp'
        AND otp_expires_at>NOW()");

    if($u=mysqli_fetch_assoc($q)){
        mysqli_query($conn,"UPDATE users SET
        email_verified_at=NOW(),
        otp_code=NULL,
        otp_expires_at=NULL
        WHERE id=".$u['id']);

        $_SESSION['user_id']=$u['id'];
        $_SESSION['role']="owner";
        header("Location: ../owner/create-restaurant.php");
        exit;
    }
    $msg="Invalid or expired OTP";
}
?>
<link rel="stylesheet" href="../assets/css/auth.css">
<div class="auth-box">
<h2>Verify OTP</h2>
<div class="msg"><?= $msg ?></div>
<form method="POST">
<input name="otp" placeholder="Enter OTP" required>
<button>Verify</button>
</form>
<a href="resend-otp.php?email=<?= $email ?>">Resend OTP</a>
</div>
