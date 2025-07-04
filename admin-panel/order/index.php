<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

$orders = getOrders($conn);
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
        <div class="product-table-header">
            <h2>Orders</h2>
            <a href="orders-report.php" target="_blank" class="btn-print"><i class="ri-printer-fill"></i> Print Orders Report</a>
        </div>
        <div class="product-table-wrapper">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Full Name</th>
                        <th>Address</th>
                        <th>Total Amount</th>
                        <th style="text-align: center;">Order Date</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['orderID'] ?></td>
                        <td class="name-word-wrap"><?= $order['fullName'] ?></td>
                        <td class="address-word-wrap"><?= $order['address'] ?></td>
                        <td style="text-align: center;">$<?= number_format($order['totalAmount'], 2) ?></td>
                        <td class="date-word-wrap" style="text-align: center;"><?= date("F j, Y \ g:i A", strtotime($order['orderDate'])) ?></td>
                        <td>
                            <?php if ($order['status'] === 'Pending'): ?>
                            <span class="status pending"><?= $order['status'] ?></span>
                            <?php elseif ($order['status'] === 'Cancelled'): ?>
                            <span class="status cancelled"><?= $order['status'] ?></span>
                            <?php else: ?>
                            <span class="status completed"><?= $order['status'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <button class="button-update" onclick="window.location.href='update-orderstatus.php?id=<?= $order['orderID'] ?>'">Change Status</button>
                            <button class="button-view" onclick="window.location.href='view-orderdetail.php?id=<?= $order['orderID'] ?>'">View</button>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
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
