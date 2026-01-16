<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once(__DIR__."/../config/db.php");

if(!isset($_SESSION['restaurant_id'])){
    return;
}

$q = mysqli_query($conn,"
SELECT trial_end, subscription_end, subscription_status
FROM restaurants
WHERE id = ".intval($_SESSION['restaurant_id'])."
");

if(!$q){
    return;
}

$r = mysqli_fetch_assoc($q);

$today = date('Y-m-d');

/* Trial expired */
if(
    $r['subscription_status'] === 'trial' &&
    !empty($r['trial_end']) &&
    $today > $r['trial_end']
){
    header("Location: ../payment/");
    exit;
}

/* Subscription expired */
if(
    $r['subscription_status'] === 'expired'
){
    header("Location: ../owner/payment.php");
    exit;
}
