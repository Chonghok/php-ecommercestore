<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/product-functions.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['userID'];
$orders = getUserOrderHistory($conn, $userID);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetsGear</title>
    <link rel="icon" href="images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/order-history.css">
</head>
<body>
    
    <?php require_once 'includes/header.php' ?>

    <main>
        <div class="product-banner">
            <img src="images/product-banner.png" alt="">
            <span>My Orders</span>
        </div>       

        <?php if (empty($orders)): ?>
        <div class="no-product">
            <h1>You have not placed any orders yet</h1>
        </div>
        <?php else: ?>
        <table class="order-history">
            <thead>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>View Detail</th>
            </thead>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['orderID'] ?></td>
                <td><?= date("F j, Y g:i A", strtotime($order['orderDate'])) ?></td>
                <td><?= $order['status'] ?></td>
                <td>$<?= $order['totalAmount'] ?></td>
                <td><?= $order['paymentMethod'] ?></td>
                <td><a href="order-history-detail.php?id=<?= $order['orderID'] ?>">View</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </main>

    <?php require_once 'includes/footer.php' ?>

</body>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/home.js"></script>
</html>