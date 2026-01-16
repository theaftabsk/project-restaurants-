<?php
include("layout.php");

if($_SESSION['role']!='owner'){
    die("Access denied");
}

$branch_id = intval($_GET['id']);

// Branch verify
$branchQ = mysqli_query($conn,"SELECT * FROM branches 
WHERE id=$branch_id AND restaurant_id=".$_SESSION['restaurant_id']);
if(mysqli_num_rows($branchQ)==0){
    die("Branch not found");
}
$branch = mysqli_fetch_assoc($branchQ);

// Kitchen user
$kitchen = null;
if($branch['kitchen_user_id']){
    $kitchen = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT * FROM users WHERE id=".$branch['kitchen_user_id'])
    );
}

$msg="";

if($_SERVER['REQUEST_METHOD']=="POST"){

    $email = trim($_POST['kitchen_email']);
    $password = trim($_POST['kitchen_password']);

    if(!$kitchen && empty($password)){
        $msg="<span style='color:red'>Password required</span>";
    } else {

        if($kitchen){
            // Update existing
            mysqli_query($conn,"UPDATE users SET email='$email' WHERE id=".$kitchen['id']);

            if($password){
                mysqli_query($conn,"UPDATE users SET
                password='".password_hash($password,PASSWORD_DEFAULT)."'
                WHERE id=".$kitchen['id']);
            }

            $msg="<span style='color:green'>Kitchen user updated</span>";

        } else {
            // Create new
            $pass = password_hash($password,PASSWORD_DEFAULT);
            mysqli_query($conn,"INSERT INTO users
            (restaurant_id,branch_id,name,email,password,role,email_verified_at)
            VALUES(".$_SESSION['restaurant_id'].",$branch_id,
            'Kitchen','$email','$pass','kitchen',NOW())");

            $uid = mysqli_insert_id($conn);
            mysqli_query($conn,"UPDATE branches SET kitchen_user_id=$uid WHERE id=$branch_id");

            $msg="<span style='color:green'>Kitchen user created</span>";
        }
    }
}
?>

<h2>Kitchen Login – <?= htmlspecialchars($branch['name']) ?></h2>

<div style="max-width:420px;background:#fff;padding:25px;border-radius:12px;
box-shadow:0 8px 25px rgba(0,0,0,.08)">

<form method="POST">

<label>Kitchen Email</label>
<input type="email" name="kitchen_email" required
value="<?= $kitchen?$kitchen['email']:"" ?>">

<label>Password <?= $kitchen?"(optional)":"(required)" ?></label>
<input type="password" name="kitchen_password">

<button type="submit" style="
margin-top:15px;width:100%;
padding:12px;background:#2563eb;
color:#fff;border:none;border-radius:8px;font-weight:600;">
Save
</button>

</form>

<p><?= $msg ?></p>
</div>
