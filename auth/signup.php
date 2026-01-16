<?php
include("../config/db.php");
include("../config/mail.php");

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn,"SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($check)>0){
        $msg = "Email already registered";
    } else {

        $otp = rand(100000,999999);
        $expiry = date("Y-m-d H:i:s",strtotime("+10 minutes"));

        mysqli_query($conn,"INSERT INTO users
        (name,email,password,role,otp_code,otp_expires_at)
        VALUES('$name','$email','$password','owner','$otp','$expiry')");

        sendOTP($email,$otp);
        header("Location: verify-otp.php?email=$email");
        exit;
    }
}
?>
<link rel="stylesheet" href="../assets/css/auth.css">
<div class="auth-box">
<h2>Signup</h2>
<div class="msg"><?= $msg ?></div>
<form method="POST">
<input name="name" placeholder="Full Name" required>
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>
<button>Create Account</button>
</form>
<a href="login.php">Already have account?</a>
</div>
