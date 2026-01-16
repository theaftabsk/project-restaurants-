<?php
include("layout.php");
include("../includes/subscription-check.php");
require_once("../config/mail.php");
?>

<h2>Add Branch</h2>

<form method="POST" style="max-width:420px;background:#fff;padding:20px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.08)">
    <label>Branch Name</label>
    <input type="text" name="name" placeholder="Main Branch" required>

    <label>Branch Address</label>
    <input type="text" name="address" placeholder="City / Area" required>

    <label>Manager Email</label>
    <input type="email" name="manager_email" placeholder="manager@email.com" required>

    <label>Manager Password</label>
    <input type="password" name="manager_password" placeholder="Create password" required>

    <button type="submit">Create Branch & Send Email</button>
</form>

<?php
if($_SERVER['REQUEST_METHOD']=="POST"){

    $rid  = $_SESSION['restaurant_id'];
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $addr = mysqli_real_escape_string($conn,$_POST['address']);
    $email= mysqli_real_escape_string($conn,$_POST['manager_email']);
    $pass = password_hash($_POST['manager_password'], PASSWORD_DEFAULT);

    // email unique check
    $chk = mysqli_query($conn,"SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($chk)>0){
        echo "<p style='color:red'>Email already exists</p>";
        exit;
    }

    // generate confirm token
    $token = bin2hex(random_bytes(32));

    // create manager (email pending)
    mysqli_query($conn,"INSERT INTO users
    (restaurant_id,name,password,role,pending_email,email_verify_token)
    VALUES($rid,'Branch Manager','$pass','branch_manager','$email','$token')");

    $uid = mysqli_insert_id($conn);

    // save branch temporarily
    mysqli_query($conn,"INSERT INTO branch_temp
    (user_id,restaurant_id,name,address)
    VALUES($uid,$rid,'$name','$addr')");

    // confirmation link
    $link = "upgradeachiever.in/auth/confirm-email.php?token=$token";

    // send email
    sendMail(
        $email,
        "Confirm Branch Manager Email",
        "Hello,<br><br>
        Your branch manager account is almost ready.<br>
        Please click the link below to confirm your email:<br><br>
        <a href='$link'>$link</a><br><br>
        If you didn’t request this, ignore this email."
    );

    echo "<p style='color:green;font-weight:600'>
    Confirmation link sent to manager email.
    </p>";
}
?>

<style>
form label{
    font-weight:600;
    display:block;
    margin-top:10px;
}
form input{
    width:100%;
    padding:10px;
    margin-top:5px;
    border:1px solid #ccc;
    border-radius:6px;
}
button{
    margin-top:15px;
    padding:12px;
    width:100%;
    background:#2563eb;
    color:#fff;
    border:none;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
}
button:hover{
    background:#1e40af;
}
</style>

</div></div>
