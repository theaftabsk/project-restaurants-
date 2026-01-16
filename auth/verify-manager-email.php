<?php
include("../config/db.php");

$uid = intval($_GET['uid']);
$msg="";

$user = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM users WHERE id=$uid")
);

if(!$user || !$user['pending_email']){
    die("Invalid request");
}

if($_SERVER['REQUEST_METHOD']=="POST"){
    $otp = $_POST['otp'];

    if($otp == $user['email_change_otp'] &&
       strtotime($user['email_change_expires']) > time()){

        mysqli_query($conn,"UPDATE users SET
        email=pending_email,
        pending_email=NULL,
        email_change_otp=NULL,
        email_change_expires=NULL,
        email_verified_at=NOW()
        WHERE id=$uid");

        echo "<h3>Email changed successfully</h3>";
        exit;
    } else {
        $msg="Invalid or expired OTP";
    }
}
?>

<h2>Verify Email OTP</h2>

<form method="POST">
<input name="otp" placeholder="Enter OTP" required>
<button>Verify</button>
</form>

<p style="color:red"><?= $msg ?></p>
