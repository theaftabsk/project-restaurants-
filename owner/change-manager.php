<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* SESSION */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* SECURITY CHECKS */
require_once "../includes/subscription-check.php";
require_once "../config/db.php";
require_once "../config/mail.php";

/* AUTH */
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    die("Access denied");
}

if (empty($_SESSION['restaurant_id'])) {
    die("Invalid session");
}

$branch_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$branch_id) {
    die("Invalid branch id");
}

/* FETCH BRANCH */
$branchQ = mysqli_query($conn,"
    SELECT * FROM branches
    WHERE id = $branch_id
    AND restaurant_id = ".(int)$_SESSION['restaurant_id']."
    LIMIT 1
");

if (!$branchQ || mysqli_num_rows($branchQ) === 0) {
    die("Branch not found");
}

$branch = mysqli_fetch_assoc($branchQ);

if (empty($branch['manager_user_id'])) {
    die("No manager assigned");
}

/* FETCH MANAGER */
$managerQ = mysqli_query($conn,"
    SELECT * FROM users
    WHERE id = ".(int)$branch['manager_user_id']."
    LIMIT 1
");

if (!$managerQ) {
    die(mysqli_error($conn));
}

$manager = mysqli_fetch_assoc($managerQ);
$msg = "";

/* POST ACTIONS */
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    /* EMAIL CHANGE (OTP) */
    if (!empty($_POST['new_email']) && $_POST['new_email'] !== $manager['email']) {

        $new_email = mysqli_real_escape_string($conn, trim($_POST['new_email']));

        $exists = mysqli_query($conn,"SELECT id FROM users WHERE email='$new_email'");
        if ($exists && mysqli_num_rows($exists) > 0) {
            $msg = "<span style='color:red'>Email already exists</span>";
        } else {

            $otp = random_int(100000, 999999);
            $exp = date("Y-m-d H:i:s", strtotime("+10 minutes"));

            mysqli_query($conn,"
                UPDATE users SET
                pending_email='$new_email',
                email_change_otp='$otp',
                email_change_expires='$exp'
                WHERE id=".$manager['id']
            );

            sendOTP($new_email, $otp);

            header("Location: ../auth/verify-manager-email.php?uid=".$manager['id']);
            exit;
        }
    }

    /* PASSWORD CHANGE */
    if (!empty($_POST['new_password'])) {
        $hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        mysqli_query($conn,"
            UPDATE users SET password='$hash'
            WHERE id=".$manager['id']
        );
        $msg = "<span style='color:green'>Password updated successfully</span>";
    }
}
?>

<?php include("layout.php"); ?>

<h2>Branch Manager Settings</h2>
<!-- HTML stays same -->


<div style="max-width:420px;background:#fff;padding:25px;border-radius:12px;
box-shadow:0 8px 25px rgba(0,0,0,.08)">

<form method="POST">

<label>Current Manager Email</label>
<input type="email" value="<?= htmlspecialchars($manager['email']) ?>" readonly>

<label>New Manager Email</label>
<input type="email" name="new_email" placeholder="Enter new email">
<small>Email change requires OTP verification</small>

<hr>

<label>Change Password</label>
<input type="password" name="new_password" placeholder="New password">
<small>Password changes instantly (No OTP)</small>

<button type="submit" style="
margin-top:15px;width:100%;padding:12px;background:#2563eb;
color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
Save Changes
</button>

</form>

<p><?= $msg ?></p>
</div>  