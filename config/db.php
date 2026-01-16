<?php
$host = "localhost";
$user = "xxxxxxxxxxx";
$pass = "xxxxxxxxxxx";
$db   = "xxxxxxxxxxx";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database Connection Failed");
}
?>

