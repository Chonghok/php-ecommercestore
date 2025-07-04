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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Report - LetsGear</title>
    <style>
        body {
        font-family: Arial, Helvetica, sans-serif;
        padding: 40px;
        margin: 0;
        background: #fff;
        color: #333;
        }

        .report-header {
        text-align: center;
        margin-bottom: 30px;
        }

        .report-header h1 {
        margin-bottom: 5px;
        font-size: 25px;
        }

        .report-header p {
        font-size: 14px;
        color: #666;
        }
        .report-title {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .report-title img {
            margin-top: 14px;
        }

        table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 13px;
        }

        th, td {
        border: 1px solid #999;
        padding: 10px;
        text-align: left;
        }

        th {
        background-color:rgb(234, 231, 231);
        }

        .footer-note {
        margin-top: 40px;
        font-style: italic;
        font-size: 13px;
        color: #777;
        text-align: center;
        }

        @media print {
        body {
            padding: 0;
            margin: 0;
        }
        }
    </style>
</head>
<body>

    <div class="report-header">
        <div class="report-title">
            <img src="../../images/logo.png" height="80px" alt=""><h1>Orders Report</h1>
        </div>
        <?php date_default_timezone_set('Asia/Bangkok'); ?>
        <p>Generated on <?= date('F j, Y \a\t g:i A') ?></p>
    </div>

    <table>
        <thead>
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Payment Method</th>
            <th>Status</th>
            <th>Total Amount</th>
            <th>Order Date</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
            <td><?= $order['orderID'] ?></td>
            <td><?= $order['userID'] ?></td>
            <td><?= htmlspecialchars($order['fullName']) ?></td>
            <td><?= htmlspecialchars($order['email']) ?></td>
            <td><?= htmlspecialchars($order['phoneNumber']) ?></td>
            <td><?= htmlspecialchars($order['address']) ?></td>
            <td><?= $order['paymentMethod'] ?></td>
            <td><?= ucfirst($order['status']) ?></td>
            <td>$<?= number_format($order['totalAmount'], 2) ?></td>
            <td><?= date("F j, Y g:i A", strtotime($order['orderDate'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer-note">
        © 2025 LetsGear. All rights reserved.
    </div>

    <script>
        // Optional: Automatically trigger print
        window.onload = () => window.print();
    </script>
</body>
</html>