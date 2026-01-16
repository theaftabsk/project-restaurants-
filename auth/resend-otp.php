<?php
include("../config/db.php");
include("../config/mail.php");

$email=$_GET['email'];

$otp=rand(100000,999999);
$expiry=date("Y-m-d H:i:s",strtotime("+10 minutes"));

mysqli_query($conn,"UPDATE users SET
otp_code='$otp', otp_expires_at='$expiry'
WHERE email='$email'");

sendOTP($email,$otp);
header("Location: verify-otp.php?email=$email");
exit;
