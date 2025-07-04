<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/product-functions.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION['order_id'])) {
    header("Location: checkout.php");
    exit;
}
$orderID = $_SESSION['order_id'];
unset($_SESSION['order_id']);

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
    <link rel="stylesheet" href="assets/css/order-success.css">
</head>
<body>
    
    <?php require_once 'includes/header.php' ?>

    <main>
        <div class="order-success-container">
            <div class="success-icon">✅</div>
            <h1>Thank You!</h1>
            <p class="subtitle">Your order has been placed successfully</p>
        
            <section class="order-box">
                <h2>Order Summary</h2>
                <div class="info-grid">
                    <div><strong>Order ID:</strong> #<?= $orderID ?></div>
                    <div><strong>Order Date:</strong> <?= date("F j, Y \a\\t g:i A", strtotime($order['orderDate'])) ?></div>
                    <div><strong>Full Name:</strong> <?= $order['fullName'] ?></div>
                    <div><strong>Payment Method:</strong> <?= $order['paymentMethod'] ?></div>
                    <div><strong>Phone Number:</strong> <?= $order['phoneNumber'] ?></div>
                    <div><strong>Address:</strong> <?= $order['address'] ?></div>
                </div>
        
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qauntity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $subtotal = 0;
                            foreach ($orderDetails as $detail): ?>
                        <tr>
                            <?php
                                $price = floatval($detail['unitPrice']);
                                $total = $price * $detail['quantity'];
                            ?>
                            <td><?= $detail['productName'] ?></td>
                            <td>$<?= number_format($price, 2) ?></td>
                            <td>x<?= $detail['quantity'] ?></td>
                            <td>$<?= number_format($total, 2) ?></td>
                            <?php $subtotal = $subtotal + $total; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        
                <!-- <div class="totals">
                    <p><strong style="text-align: left;">Subtotal:</strong> $<?= number_format($subtotal, 2) ?></p>
                    <p><strong>Shipping:</strong> $<?= number_format($order['shippingFee'], 2) ?></p>
                    <p class="grand-total"><strong>Grand Total:</strong> $<?= number_format($order['totalAmount'], 2) ?></p>
                </div> -->
                <div class="totals">
                    <table>
                        <tr>
                            <td>Sub Total:</td>
                            <td>$<?= number_format($subtotal, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Shipping:</td>
                            <td>$<?= number_format($order['shippingFee'], 2) ?></td>
                        </tr>
                        <tr style="font-size: 20px; font-weight: bold;">
                            <td>
                                Grand Total:
                            </td>
                            <td>$<?= number_format($order['totalAmount'], 2) ?></td>
                        </tr>
                    </table>
                </div>
                
            </section>
            <a href="print-invoice.php?id=<?= $orderID ?>" target="_blank" class="print-btn"><i class="ri-printer-fill"></i> Print Invoice</a>
        
            <div class="button-group">
                <a href="index.php" class="btn">Continue Shopping</a>
            </div>
        </div>        
    </main>

    <?php require_once 'includes/footer.php' ?>

</body>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/home.js"></script>
</html>