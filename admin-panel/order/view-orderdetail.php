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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>LetsGear Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../admin-assets/css/style.css">
    <link rel="stylesheet" href="../admin-assets/css/order.css">
</head>
<body>

    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>

    <main>
        <div class="order-detail-header">
            <h2>Order Detail</h2>
        </div>

        <div class="order-info-box">
            <div class="order-info-item"><strong>User ID:</strong> <?= $order['userID'] ?></div>
            <div class="order-info-item"><strong>Order ID:</strong> <?= $orderID ?></div>
            <div class="order-info-item"><strong>Full Name:</strong> <?= $order['fullName'] ?></div>
            <div class="order-info-item"><strong>Order Date:</strong> <?= date("F j, Y \a\\t g:i A", strtotime($order['orderDate'])) ?></div>
            <div class="order-info-item"><strong>Email:</strong> <?= $order['email'] ?></div>
            <div class="order-info-item"><strong>Payment Method:</strong> <?= $order['paymentMethod'] ?></div>
            <div class="order-info-item"><strong>Address:</strong> <?= $order['address'] ?></div>
            <div class="order-info-item"><strong>Status:</strong> <?= $order['status'] ?></div>
            <div class="order-info-item"><strong>Phone Number:</strong> <?= $order['phoneNumber'] ?></div>
            <div><strong></strong> </div>
        </div>


        <div class="product-table-wrapper">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total = 0; 
                        $subtotal = 0;
                        foreach ($orderDetails as $detail): ?>
                    <tr>
                        <td><?= $detail['productName'] ?></td>
                        <td>x<?= $detail['quantity'] ?></td>
                        <td>$<?= number_format($detail['unitPrice'], 2) ?></td>
                        <?php $total = $detail['quantity'] * $detail['unitPrice'] ?>
                        <td>$<?= number_format($total, 2) ?></td>
                    </tr>
                    <?php
                        $subtotal = $subtotal + $total; 
                        endforeach; ?>
                    <tr class="grand-total-row">
                        <td colspan="3" class="grand-total-label">
                            Sub Total
                        </td>
                        <td>$<?= number_format($subtotal, 2) ?></td>
                    </tr>
                    <tr class="grand-total-row">
                        <td colspan="3" class="grand-total-label">
                            Shipping Fee
                        </td>
                        <td>$<?= number_format($order['shippingFee'], 2) ?></td>
                    </tr>
                    <tr class="grand-total-row">
                        <td colspan="3" class="grand-total-label">
                            Grand Total Amount
                        </td>
                        <td class="grand-total-amount">
                            $<?= number_format($order['totalAmount'], 2) ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="order-detail-actions-left">
                <a href="index.php" class="link-back">Back</a>
                <a href="orderdetail-report.php?id=<?= $orderID ?>" target="_blank" class="btn-print"><i class="ri-printer-fill"></i> Print Invoice</a>
            </div>
        </div>
    </main>

    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <div class="confirmation-message" id="confirmationMessage">Are you sure you want to log out?</div>
            <div class="confirmation-buttons">
                <button class="confirmation-btn cancel-btn" id="cancelBtn">Cancel</button>
                <button class="confirmation-btn confirm-btn" id="confirmBtn">Log Out</button>
            </div>
        </div>
    </div>
    
</body>
<script src="../admin-assets/js/script.js"></script>
</html>
