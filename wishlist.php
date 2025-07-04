<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/product-functions.php';
    
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $wishlistProducts = getWishlistProducts($conn, $_SESSION['userID']);
}
else {
    header("Location: login.php");
    exit;
}
    
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
    <link rel="stylesheet" href="assets/css/wishlist.css">
</head>
<body>
    
    <?php require_once 'includes/header.php' ?>

    <main>
        <?php if (empty($wishlistProducts)): ?>
        <div class="no-product">
            <h1>No items in your wishlist yet</h1>
        </div>
        <?php else: ?>
        <div class="small-title">
            <h1>My Wishlist <span>(<?= count($wishlistProducts) ?> item<?= count($wishlistProducts) > 1 ? 's' : '' ?>)</span></h1>
            <button class="btn-clear" title="Clear all items in wishlist">Clear Wishlist</button>
        </div>
        
        <div class="wishlist-container">
            <?php foreach ($wishlistProducts as $product): ?>
            <div class="wishlist-item">
                <div class="wishlist-image" onclick="window.location.href='product-detail.php?id=<?= htmlspecialchars($product['productID']); ?>'">
                    <img src="images/<?= urlencode($product['categoryName']); ?>/<?= htmlspecialchars($product['mainImage']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                </div>
                <div class="wishlist-detail">
                    <?php if ($product['discount'] > 0): ?>
                    <p><a href="product-detail.php?id=<?= htmlspecialchars($product['productID']); ?>"><?= htmlspecialchars($product['name']); ?></a><span class="wishlist-discount-percentage">(<?= number_format($product['discount'], 2) ?>% Off)</span></p>
                    <?php else: ?>
                    <p><a href="product-detail.php?id=<?= htmlspecialchars($product['productID']); ?>"><?= htmlspecialchars($product['name']); ?></a></p>
                    <?php endif; ?>
                    <p><a href="category.php?id=<?= htmlspecialchars($product['categoryID']); ?>"><?= htmlspecialchars($product['categoryName']); ?></a></p>
                    <?php
                        $discount = floatval($product['discount']);
                        if ($discount > 0):
                    ?>
                    <!-- For Product with discount price -->
                    <p><span>$<?= number_format($product['price'] * (1 - ($discount / 100)), 2) ?></span><strike>$<?= number_format($product['price'], 2); ?></strike></p>
                    <?php else: ?>
                    <!-- For Product with normal price -->
                    <p>$<?= number_format($product['price'], 2) ?></p>
                    <?php endif; ?>
                    <button class="wishlist-remove"><i class="ri-delete-bin-5-fill"></i></button>
                    <?php
                        $inCart = checkIfInCart($conn, $_SESSION['userID'], $product['productID']);
                        $outOfStock = ($product['stock'] === 0);
                    ?>
                    <?php if ($outOfStock): ?>
                    <button class="btn-atc-out-of-stock" disabled>OUT OF STOCK</button>
                    <?php elseif($inCart): ?>
                    <button class="btn-atc-added" disabled><i class="ri-shopping-cart-2-fill"></i> ADDED TO CART</button>
                    <?php else: ?>
                    <button class="btn-atc"><i class="ri-shopping-cart-2-fill"></i> ADD TO CART</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>

    <?php require_once 'includes/footer.php' ?>

    <!-- Confirmation Modal -->
    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <div class="confirmation-message" id="confirmationMessage">Remove from wishlist?</div>
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
<script src="assets/js/wishlist.js"></script>
</html> 

