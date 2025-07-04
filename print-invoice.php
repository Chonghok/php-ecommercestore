<?php
require_once 'includes/config.php';
require_once 'includes/product-functions.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
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
    <title>Invoice - LetsGear</title>
    <link rel="icon" href="images/logo.png">
    <link rel="stylesheet" href="assets/css/print-invoice.css">
</head>
<body onload="window.print()">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <img src="images/logo.png" alt="Store Logo" style="height: 80px;">
        <h1 style="margin: 0; font-size: 20px;">Invoice</h1>
    </div>
    <h2 style="text-align: center;">Order #<?= $orderID ?></h2>

    <div class="info">
        <div><strong>Order Date:</strong> <?= date("F j, Y \a\\t g:i A", strtotime($order['orderDate'])) ?></div>
        <div><strong>Customer Name:</strong> <?= $order['fullName'] ?></div>
        <div><strong>Phone Number:</strong> <?= $order['phoneNumber'] ?></div>
        <div><strong>Address:</strong> <?= $order['address'] ?></div>
        <div><strong>Email:</strong> <?= $order['email'] ?></div>
        <div><strong>Payment Method:</strong> <?= $order['paymentMethod'] ?></div>
        <div><strong>Status:</strong> <?= ucfirst($order['status']) ?></div>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php 
            $subTotal = 0;
            foreach ($orderDetails as $detail): 
            $total = $detail['unitPrice'] * $detail['quantity'];
            $subTotal += $total;
        ?>
        <tr>
            <td><?= $detail['productName'] ?></td>
            <td>$<?= number_format($detail['unitPrice'], 2) ?></td>
            <td><?= $detail['quantity'] ?></td>
            <td>$<?= number_format($total, 2) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totals">
        <div><strong>Subtotal:</strong> $<?= number_format($subTotal, 2) ?></div>
        <div><strong>Shipping Fee:</strong> $<?= number_format($order['shippingFee'], 2) ?></div>
        <div><strong>Total Amount:</strong> $<?= number_format($order['totalAmount'], 2) ?></div>
    </div>

    <div class="footer">
        Thank you for your order!<br>
        © 2025 LetsGear. All rights reserved.<br>
        Note: The order status may be updated by the administrator. This is not the final official invoice.
    </div>
</body>
</html>