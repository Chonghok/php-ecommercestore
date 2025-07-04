<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/product-functions.php';
    
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $cartProducts = getCartProducts($conn, $_SESSION['userID']);
}
else {
    header("Location: login.php");
    exit;
}

$totalPrice = 0;
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
    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>
    
    <?php require_once 'includes/header.php' ?>

    <main>
        <?php if (empty($cartProducts)): ?>
        <div class="no-product">
            <h1>No items in your cart yet</h1>
        </div>
        <?php else: ?>
        <div class="small-title">
            <h1>My Cart <span>(<?= count($cartProducts) ?> item<?= count($cartProducts) > 1 ? 's' : '' ?>)</span></h1>
            <button class="btn-clear" title="Clear all items in cart">Clear Cart</button>
        </div>

        <div class="cart-container">
            <table>
                <tr>
                    <th>Product</th>
                    <th class="qty-container">Quantity</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($cartProducts as $product): ?>
                <tr data-product-id="<?= htmlspecialchars($product['productID']) ?>">
                    <td>
                        <div class="cart-item">
                            <div class="cart-image" onclick="window.location.href='product-detail.php?id=<?= htmlspecialchars($product['productID']); ?>'">
                                <img src="images/<?= urlencode($product['categoryName']) ?>/<?= htmlspecialchars($product['mainImage']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="cart-info">
                                <a href="product-detail.php?id=<?= htmlspecialchars($product['productID']) ?>" class="cart-productName"><h4><?= htmlspecialchars($product['name']) ?></h4></a>
                                <?php
                                    $discount = floatval($product['discount']);
                                    if ($discount > 0):
                                        $discountedPrice = number_format($product['price'] * (1 - ($discount / 100)), 2);
                                ?>
                                <!-- For product with discount price -->
                                <p>Price: <span class="cart-discount">$<?= $discountedPrice ?></span><strike>$<?= number_format($product['price'], 2) ?></strike></p>
                                <?php else: ?>
                                <!-- For product with normal price (discount = 0) -->
                                <p>Price: <span>$<?= number_format($product['price'], 2) ?></span></p>
                                <?php endif; ?>
                                <button class="cart-remove">Remove</button>
                            </div>
                        </div>
                    </td>
                    <td class="qty-container"><input class="cart-quantity" type="number" value="<?= $product['quantity'] ?>" min="1" max="<?= $product['stock'] ?>"></td>
                    <?php
                        $total = 0;
                        if ($discount > 0){
                            $total = number_format(($product['quantity'] * $discountedPrice), 2);
                        }else{
                            $total = number_format(($product['quantity'] * $product['price']), 2);
                        }
                    ?>
                    <td class="total">$<?= $total ?></td>
                    <?php $totalPrice = $totalPrice + $total; ?>
                </tr>
                <?php endforeach; ?>
            </table>

            <div class="cart-total-price">
                <?php
                    $shippingFee = number_format(floor($totalPrice * 0.10), 2, '.', '');
                    $grandTotal = number_format($totalPrice + $shippingFee, 2);
                ?>
                <table>
                    <tr>
                        <td>Sub Total</td>
                        <td>$<?= number_format($totalPrice, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Shipping</td>
                        <td>$<?= $shippingFee ?></td>
                    </tr>
                    <tr>
                        <td>Grand Total</td>
                        <td class="total">$<?= $grandTotal ?></td>
                    </tr>
                    <tr>
                        <td style="padding-top: 40px;" colspan="2"><a href="checkout.php" class="btn-checkout">Check Out</a></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <?php require_once 'includes/footer.php' ?>

    <!-- Confirmation Modal -->
    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <div class="confirmation-message" id="confirmationMessage">Remove from cart?</div>
            <div class="confirmation-buttons">
                <button class="confirmation-btn cancel-btn" id="cancelRemove">Cancel</button>
                <button class="confirmation-btn confirm-btn" id="confirmRemove">Remove</button>
            </div>
        </div>
    </div>

</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/cart.js"></script>
</html>