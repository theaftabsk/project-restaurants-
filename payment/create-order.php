<?php
session_start();
include("../config/db.php");
require("../razorpay/Razorpay.php");

use Razorpay\Api\Api;

$KEY_ID = "xxxxxxxxxxxx";
$KEY_SECRET = "xxxxxxxxxxxx";

$api = new Api($KEY_ID, $KEY_SECRET);

$amount = intval($_GET['amount']); // 999 or 9999
$amount_paise = $amount * 100;

$order = $api->order->create([
    'receipt' => 'order_'.time(),
    'amount' => $amount_paise,
    'currency' => 'INR'
]);

echo json_encode([
    "order_id" => $order['id'],
    "amount"   => $amount_paise,
    "key"      => $KEY_ID
]);

