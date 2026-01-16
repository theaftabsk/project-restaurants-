<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'owner') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* Already has restaurant? */
$u = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT restaurant_id FROM users WHERE id=$user_id")
);

if ($u['restaurant_id']) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    /* Trial dates */
    $trial_start = date('Y-m-d');
    $trial_end   = date('Y-m-d', strtotime('+14 days'));

    /* 1️⃣ Create Restaurant with Trial */
    mysqli_query($conn,"INSERT INTO restaurants 
    (owner_id,name,phone,address,
     trial_end,subscription_status)
    VALUES (
        $user_id,
        '$name',
        '$phone',
        '$address',
        '$trial_end',
        'trial'
    )");

    $restaurant_id = mysqli_insert_id($conn);

    /* 2️⃣ Create Main Branch */
    mysqli_query($conn,"INSERT INTO branches 
    (restaurant_id,name,address,manager_user_id)
    VALUES (
        $restaurant_id,
        'Main Branch',
        '$address',
        $user_id
    )");

    $branch_id = mysqli_insert_id($conn);

    /* 3️⃣ Update Owner User */
    mysqli_query($conn,"UPDATE users SET
        restaurant_id=$restaurant_id,
        branch_id=$branch_id
    WHERE id=$user_id");

    /* Session update */
    $_SESSION['restaurant_id'] = $restaurant_id;
    $_SESSION['branch_id']     = $branch_id;

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Restaurant</title>
<link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

<div class="auth-box">
<h2>Create Your Restaurant</h2>

<form method="POST">
    <input name="name" placeholder="Restaurant Name" required>
    <input name="phone" placeholder="Phone Number">
    <input name="address" placeholder="Address" required>
    <button>Create Restaurant</button>
</form>

</div>
</body>
</html>
