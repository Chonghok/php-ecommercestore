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

if (!isset($_GET['id'])) {
    header("Location: order-history.php");
    exit;
}

$orderID = $_GET['id'];
$order = getOrderInfo($conn, $orderID);
$orderDetails = getOrderDetails($conn, $orderID);
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
            <span>Order Details</span>
        </div>       
        <?php if (empty($order)): ?>
            <div class="no-product">
                <h1>Order Details not found</h1>
            </div>
        <?php else: ?>
        <div class="history-detail">
            <div class="order-item"><strong>Order ID:</strong> <?= $orderID ?></div>
            <div class="order-item"><strong>Order Date:</strong> <?= date("F j, Y \a\\t g:i A", strtotime($order['orderDate'])) ?></div>
            <div class="order-item"><strong>Status:</strong> <?= $order['status'] ?></div>
            <div class="order-item"><strong>Payment Method:</strong> <?= $order['paymentMethod'] ?></div>
            <div class="order-item"><strong>Shipping Fee:</strong> $<?= number_format($order['shippingFee'], 2) ?></div>
            <div class="order-item"><strong>Total Amount:</strong> $<?= number_format($order['totalAmount'], 2) ?></div>

            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
                <?php
                    $subtotal = 0; 
                    foreach ($orderDetails as $detail): 
                    $price = floatval($detail['unitPrice']);
                    $total = $price * $detail['quantity'];
                ?>
                <tr>
                    <td><?= $detail['productName'] ?></td>
                    <td>$<?= number_format($price, 2) ?></td>
                    <td>x<?= $detail['quantity'] ?></td>
                    <td>$<?= number_format($total, 2) ?></td>
                    <?php $subtotal = $subtotal + $total; ?>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" style="font-size: 16px;"><strong>Sub Total:</strong></td>
                    <td style="font-size: 16px;"><strong>$<?= number_format($subtotal, 2) ?></strong></td>
                </tr>
            </table>

            <div class="order-detail-actions-left">
                <a href="order-history.php" class="link-back">Back</a>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <?php require_once 'includes/footer.php' ?>

</body>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/home.js"></script>
</html>