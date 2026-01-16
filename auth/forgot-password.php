<?php
include("../config/db.php");
include("../config/mail.php");

$msg="";
if($_SERVER['REQUEST_METHOD']=="POST"){
    $email=$_POST['email'];
    $otp=rand(100000,999999);
    $expiry=date("Y-m-d H:i:s",strtotime("+10 minutes"));

    mysqli_query($conn,"UPDATE users SET
    otp_code='$otp', otp_expires_at='$expiry'
    WHERE email='$email'");

    sendOTP($email,$otp);
    header("Location: reset-password.php?email=$email");
    exit;
}
?>
<link rel="stylesheet" href="../assets/css/auth.css">
<div class="auth-box">
<h2>Forgot Password</h2>
<form method="POST">
<input name="email" type="email" placeholder="Your Email" required>
<button>Send OTP</button>
</form>
</div>
