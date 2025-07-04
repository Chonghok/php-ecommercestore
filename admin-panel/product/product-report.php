<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';


if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

$products = getProducts($conn);
date_default_timezone_set('Asia/Bangkok'); // Change based on your timezone
$generatedDate = date("F j, Y, g:i A");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List Report LetsGear</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .report-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .report-header img {
            height: 85px;
        }
        .report-header h1 {
            margin: 10px 0;
            font-size: 26px;
        }
        .generated-date {
            font-size: 13px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 13px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
        }
        .product-img {
            height: 40px;
        }
        .footer-note {
        margin-top: 40px;
        font-style: italic;
        font-size: 13px;
        color: #777;
        text-align: center;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="report-header">
        <img src="../../images/logo-removebg.png" alt="Company Logo">
        <h1>Products Report</h1>
        <div class="generated-date">Generated on <?= $generatedDate ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Price After Discount</th>
                <th>Discount (%)</th>
                <th>Short Description</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <?php
                        $originalPrice = $product['price'];
                        $discount = $product['discount'];
                        $priceAfterDiscount = $originalPrice * (1 - ($discount / 100));
                    ?>
                    <tr>
                        <td><?= $product['productID'] ?></td>
                        <td>
                            <img src="../../images/<?= $product['categoryName'] ?>/<?= htmlspecialchars($product['mainImage']) ?>" class="product-img" alt="Image">
                        </td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['categoryName']) ?></td>
                        <td><?= $product['stock'] ?></td>
                        <td>$<?= number_format($priceAfterDiscount, 2) ?></td>
                        <td><?= $discount ?>%</td>
                        <td><?= htmlspecialchars($product['shortDesc']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">No products found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="footer-note">
        © 2025 LetsGear. All rights reserved.
    </div>
</body>
</html>
