<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$orderID = $_GET['id'];
$order = getOrderByID($conn, $orderID);
$orderDetails = getOrderDetailsByID($conn, $orderID);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Admin Panel LetsGear</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        margin: 40px auto;
        color: #333;
        width: 80%;
        font-size: 15px;
        }
        h1, h2 {
            text-align: center;
        }
        .info {
            margin-top: 20px;
        }
        .info div {
            margin: 5px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .table th, .table td {
            border: 1px solid #888;
            padding: 8px 12px;
            text-align: center;
        }
        .table th:first-child, .table td:first-child {
            text-align: left;
        }
        .table th {
            background-color: #f4f4f4;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals div {
            margin: 5px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-style: italic;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body onload="window.print()">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <img src="../../images/logo.png" alt="Store Logo" style="height: 80px;">
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
        This invoice reflects the final status of the order as processed by the administrator.
    </div>
</body>
</html>