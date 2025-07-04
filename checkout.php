<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/product-functions.php';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $reviewCart = getCartProducts($conn, $_SESSION['userID']);
}
else {
    header("Location: login.php");
    exit;
}
$totalAmount = 0;
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
    <link rel="stylesheet" href="assets/css/checkout.css">
</head>
<body>
    
    <?php require_once 'includes/header.php' ?>

    <main>
        <div class="checkout">
            <div class="checkout-container">
                <div class="small-title">
                    <h1>Checkout</h1>
                </div>
                <form action="process-order.php" method="post" class="checkout-form">
                    <div class="form-group">
                        <label for="email">Email address <span class="required">*</span></label>
                        <input type="email" name="email" placeholder="Enter your email address" required>
                    </div>

                    <div class="form-group">
                        <label for="fullname">Full name <span class="required">*</span></label>
                        <input type="text" name="fullname" placeholder="Enter your full name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address <span class="required">*</span></label>
                        <input type="text" name="address" placeholder="Enter your address" required>
                    </div>
        
                    <div class="form-group">
                        <label for="phone">Phone number <span class="required">*</span></label>
                        <input type="tel" name="phone" placeholder="Enter your phone number" required>
                    </div>
        
                    <div class="form-group">
                        <label for="paymentMethod">Payment method</label>
                        <select name="paymentMethod" required>
                            <option value="cod">Cash on Delivery</option>
                            <option value="qr">QR Code</option>
                        </select>
                    </div>
        
                    <!-- QR section (initially hidden) -->
                    <div id="qr-payment" class="qr-container">
                        <h3>Scan QR Code to Pay</h3>
                        <img src="images/qr-payment.jpg" alt="QR Code">
                    </div>

                    <?php if (empty($reviewCart)): ?>
                    <button class="cart-empty" disabled>Cannot Place Order</button>
                    <?php else: ?>
                    <button type="submit" name="confirmOrder" class="confirm-order-btn">Confirm Order</button>
                    <?php endif; ?>
                </form>
            </div>
            <?php if (empty($reviewCart)): ?>
            <div class="cart-container no-cart-review">
                <div class="no-cart-text">
                    <h4>Your cart is currently empty</h4>
                    <h4>Please add items before proceeding to checkout</h4>
                </div>
            </div>
            <?php else: ?>
            <div class="checkout-container cart-review">
                <div class="review-container">
                    <h3>Review your cart</h3>
                    <?php foreach ($reviewCart as $product): ?>
                    <div class="review-item">
                        <div class="review-image">
                            <img src="images/<?= urlencode($product['categoryName']) ?>/<?= htmlspecialchars($product['mainImage']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        <div class="review-info">
                            <p><?= htmlspecialchars($product['name']) ?></p>
                            <p><?= htmlspecialchars($product['quantity']) ?>x</p>
                            <?php
                                $total = 0;
                                $discount = floatval($product['discount']);
                                if ($discount > 0){
                                    $discountAmount = $product['price'] * (1 - ($discount / 100));
                                    $total = $discountAmount * $product['quantity'];
                                } else {
                                $total = $product['price'] * $product['quantity'];
                                }
                            ?>
                            <p>$<?= number_format($total, 2) ?></p>
                        </div>
                    </div>
                    <?php 
                    $totalAmount = $totalAmount + $total;
                    endforeach; 
                    ?>
                    


                    <table>
                        <?php
                            $shippingFee = number_format(floor($totalAmount * 0.10), 2, '.', '');
                            $grandTotal = number_format($totalAmount + $shippingFee, 2);
                        ?>
                        <tr>
                            <td>Sub Total</td>
                            <td>$<?= number_format($totalAmount, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Shipping</td>
                            <td>$<?= $shippingFee ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; color: black;">Grand Total</td>
                            <td class="total">$<?= $grandTotal ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require_once 'includes/footer.php' ?>

    <!-- Confirmation Modal -->
    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <div class="confirmation-message" id="confirmationMessage">Are you sure you want to place your order?</div>
            <div class="confirmation-buttons">
                <button class="confirmation-btn cancel-btn" id="cancelRemove">Cancel</button>
                <button class="confirmation-btn confirm-btn" id="confirmRemove">Place Order</button>
            </div>
        </div>
    </div>
</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/checkout.js"></script>
</html>