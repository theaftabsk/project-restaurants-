<?php
session_start();

/* DEBUG (remove in production) */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../config/db.php");

/* AUTH */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kitchen') {
    header("Location: ../auth/login.php");
    exit;
}

$rid = (int)$_SESSION['restaurant_id'];
$bid = (int)$_SESSION['branch_id'];

/* PAGINATION */
$page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

/* FILTER */
$where = " WHERE o.restaurant_id=$rid AND o.branch_id=$bid ";

if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $from = $_GET['from']." 00:00:00";
    $to   = $_GET['to']." 23:59:59";
    $where .= " AND o.created_at BETWEEN '$from' AND '$to' ";
}

/* STATUS UPDATE */
if (isset($_GET['id'], $_GET['status'])) {
    $id = (int)$_GET['id'];
    $st = $_GET['status'];

    if (in_array($st, ['pending','cooking','ready'], true)) {
        mysqli_query($conn, "
            UPDATE orders 
            SET status='$st'
            WHERE id=$id AND restaurant_id=$rid AND branch_id=$bid
        ");
    }
    header("Location: dashboard.php");
    exit;
}

/* ORDERS */
$q = mysqli_query($conn, "
    SELECT o.*, t.table_number
    FROM orders o
    LEFT JOIN tables t ON o.table_id=t.id
    $where
    ORDER BY o.created_at DESC
    LIMIT $limit OFFSET $offset
");

/* TOTAL ORDERS (for pagination only) */
$cq = mysqli_query($conn, "
    SELECT COUNT(*) AS c FROM orders o $where
");
$total = (int)mysqli_fetch_assoc($cq)['c'];
$total_pages = ceil($total / $limit);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Kitchen Dashboard</title>
<style>
body{font-family:Arial;background:#f1f5f9;margin:0}
.header{background:#111827;color:#fff;padding:15px;font-size:18px}
.container{padding:20px}
.card{background:#fff;padding:15px;margin-bottom:15px;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,.1)}
.badge{padding:4px 10px;border-radius:20px;color:#fff;font-size:12px}
.pending{background:#dc2626}
.cooking{background:#f59e0b}
.ready{background:#16a34a}
.btn{padding:6px 12px;border-radius:6px;color:#fff;text-decoration:none;font-size:13px;margin-right:5px}
.filter input,.filter button{padding:6px;margin-right:5px}
.pagination a{padding:6px 10px;background:#2563eb;color:#fff;margin-right:5px;border-radius:4px;text-decoration:none}
.pagination a.active{background:#1e40af}
</style>
</head>

<body>

<div class="header">
🍳 Kitchen Dashboard
<a href="../auth/logout.php" style="float:right;color:#f87171">Logout</a>
</div>

<div class="container">

<form class="filter" method="GET">
From <input type="date" name="from" value="<?= htmlspecialchars($_GET['from'] ?? '') ?>">
To <input type="date" name="to" value="<?= htmlspecialchars($_GET['to'] ?? '') ?>">
<button type="submit">Filter</button>
</form>

<hr>

<?php if (mysqli_num_rows($q) === 0): ?>
<p>No orders found</p>
<?php endif; ?>

<?php while ($o = mysqli_fetch_assoc($q)): ?>
<div class="card">
<b>Order #<?= (int)$o['id'] ?></b> |
Table <?= htmlspecialchars($o['table_number'] ?? '-') ?><br>
₹<?= htmlspecialchars($o['total_amount']) ?><br>

<span class="badge <?= htmlspecialchars($o['status']) ?>">
<?= strtoupper($o['status']) ?>
</span>

<hr>

<?php
$it = mysqli_query($conn, "
    SELECT m.name, oi.quantity
    FROM order_items oi
    JOIN menu_items m ON oi.menu_item_id=m.id
    WHERE oi.order_id=".(int)$o['id']
);
while ($i = mysqli_fetch_assoc($it)) {
    echo "• ".htmlspecialchars($i['name'])." × ".(int)$i['quantity']."<br>";
}
?>

<hr>

<?php if ($o['status'] === 'pending'): ?>
<a class="btn cooking" href="?id=<?= $o['id'] ?>&status=cooking">Start Cooking</a>
<?php endif; ?>

<?php if ($o['status'] === 'cooking'): ?>
<a class="btn ready" href="?id=<?= $o['id'] ?>&status=ready">Mark Ready</a>
<?php endif; ?>

</div>
<?php endwhile; ?>

<?php if ($total_pages > 1): ?>
<div class="pagination">
<?php for ($i=1;$i<=$total_pages;$i++): ?>
<a class="<?= $i==$page?'active':'' ?>" href="?page=<?= $i ?>"><?= $i ?></a>
<?php endfor; ?>
</div>
<?php endif; ?>

</div>

<script>
// 🔔 Notification permission
if ("Notification" in window && Notification.permission === "default") {
    Notification.requestPermission();
}

// 🔄 Auto refresh
setTimeout(() => location.reload(), 10000);

// 🔊 Sound + Notification on new pending order
let lastCount = 0;

setInterval(() => {
    fetch('order-count.php')
        .then(r => r.text())
        .then(c => {
            c = parseInt(c || '0', 10);

            if (c > lastCount) {
                try { new Audio('../assets/sound/new.mp3').play(); } catch(e){}

                if (Notification.permission === "granted") {
                    new Notification("🍳 New Order Received", {
                        body: "New pending order arrived in kitchen",
                        icon: "../assets/img/notification.png"
                    });
                }
            }
            lastCount = c;
        });
}, 5000);

// 📺 Fullscreen
document.addEventListener('dblclick', () => {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    }
});
</script>

</body>
</html>
