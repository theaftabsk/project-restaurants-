<?php
include("../config/db.php");

$token = $_GET['token'] ?? '';

if(empty($token)){
    die("Invalid confirmation link");
}

/* Find manager by token */
$userQ = mysqli_query($conn,"
    SELECT * FROM users 
    WHERE email_verify_token = '$token'
    AND pending_email IS NOT NULL
");

if(mysqli_num_rows($userQ) != 1){
    die("Invalid or expired confirmation link");
}

$user = mysqli_fetch_assoc($userQ);

/* Get temporary branch data */
$branchQ = mysqli_query($conn,"
    SELECT * FROM branch_temp 
    WHERE user_id = ".$user['id']
);

if(mysqli_num_rows($branchQ) != 1){
    die("Branch information not found");
}

$branch = mysqli_fetch_assoc($branchQ);

/* Create branch */
mysqli_query($conn,"
    INSERT INTO branches (restaurant_id, name, address)
    VALUES (
        ".$branch['restaurant_id'].",
        '".mysqli_real_escape_string($conn,$branch['name'])."',
        '".mysqli_real_escape_string($conn,$branch['address'])."'
    )
");

$branch_id = mysqli_insert_id($conn);

/* Activate manager */
mysqli_query($conn,"
    UPDATE users SET
        email = pending_email,
        pending_email = NULL,
        email_verify_token = NULL,
        branch_id = $branch_id,
        email_verified_at = NOW()
    WHERE id = ".$user['id']
);

/* Attach manager to branch */
mysqli_query($conn,"
    UPDATE branches SET
        manager_user_id = ".$user['id']."
    WHERE id = $branch_id
");

/* Cleanup temp data */
mysqli_query($conn,"
    DELETE FROM branch_temp WHERE user_id = ".$user['id']
);

/* Redirect to login */
header("Location: login.php?verified=1");
exit;
