<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/product-functions.php';

$productID = $_GET['id']; 
$productData = getProductDetails($conn, $productID);
$product = $productData['product'] ?? null;
$images = $productData['images'] ?? [];
$relatedProducts = getRelatedProducts($conn, $productID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetsGear</title>
    <link rel="icon" href="images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/product-detail.css">
</head>
<body>
    
    <?php require_once 'includes/header.php' ?>

    <?php if(!$product): ?>
    <main>
        <div class="no-product">
            <h1>Couldn't Find Product</h1>
        </div>
    </main>
    <?php else: ?>
    <main class="main">
        <div class="product-details">
            <div class="product-details-box">
                <div class="product-details-image-container">
                    <div class="swiper big-product-swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="product-details-image">
                                    <img src="images/<?= urlencode($product['categoryName']); ?>/<?= htmlspecialchars($product['mainImage']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                                </div>
                            </div>
                            <?php if (!empty($images)): ?>
                                <?php foreach ($images as $image): ?>
                            <div class="swiper-slide">
                                <div class="product-details-image">
                                    <img src="images/<?= urlencode($product['categoryName']); ?>/<?= htmlspecialchars($image['subImage']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                                </div>
                            </div>
                            <?php endforeach;?>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($images)): ?>
                        <div class="swiper-button-prev">
                            <i class="ri-arrow-left-s-line"></i>
                        </div>
                        <div class="swiper-button-next">
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="swiper small-product-swiper">
                        <?php if (!empty($images)): ?>
                        <div class="swiper-wrapper">
                            <div class="swiper-slide small-image">
                                <img src="images/<?= urlencode($product['categoryName']); ?>/<?= htmlspecialchars($product['mainImage']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                            </div>
                            <?php foreach ($images as $image): ?>
                            <div class="swiper-slide small-image">
                                <img src="images/<?= urlencode($product['categoryName']); ?>/<?= htmlspecialchars($image['subImage']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="swiper-wrapper">
                            <div class="swiper-slide small-image"></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>                                
                <div class="product-details-text"> 
                    <nav><a href="index.php">Home</a> / <a href="category.php?id=<?= $product['categoryID']; ?>"><?= htmlspecialchars($product['categoryName']); ?></a></nav>
                    <!-- Add class favorited to determined it is favorited or not -->
                    <button class="wishlist" 
                            data-product-id="<?= $productID ?>" 
                            aria-label="Add to wishlist">
                        <i class="ri-heart-fill"></i>
                    </button>
                    <h1><?= htmlspecialchars($product['name']); ?></h1>
                    <h3><?= htmlspecialchars($product['shortDesc']); ?></h3>
                    <ul>
                        <?php
                            $details = splitProductDetails($product['productDetail']);
                            foreach ($details as $detail): 
                        ?>
                        <li><?= htmlspecialchars($detail); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="product-details-price">
                        <?php
                            $orignalPrice = $product['price'];
                            $discount = $product['discount'];
                            if($discount > 0):
                                $discountedPrice = $orignalPrice * (1 - ($discount / 100));
                        ?>
                        <span class="discounted-price">$<?= number_format($discountedPrice, 2); ?></span><strike style="margin: 0px 12px;">$<?= number_format(($product['price']), 2); ?></strike>
                            <?php else: ?>
                        <span>$<?= number_format($product['price'], 2); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="qty-cart-container">
                        <div class="quantity-container">
                            <button class="quantity-btn" id="decreaseBtn" disabled>-</button>
                            <input type="number" class="quantity-input" id="quantityInput" value="1" min="1" max="<?= $product['stock'] ?>">
                            <button class="quantity-btn" id="increaseBtn">+</button>
                        </div>
                        <?php
                        $inCart = false;
                        if (isset($_SESSION['userID'])) {
                            $stmt = $conn->prepare("SELECT quantity FROM cart WHERE userID = ? AND productID = ?");
                            $stmt->execute([$_SESSION['userID'], $productID]);
                            $inCart = $stmt->fetch();
                        }

                        if ($product['stock'] === 0 ): ?>
                        <button class="btn-outofstock" disabled>OUT OF STOCK</button>
                        <?php elseif ($inCart): ?>
                        <button class="btn-addtocart-added" disabled>
                            <i class="ri-shopping-cart-2-fill"></i> ADDED TO CART
                        </button>
                        <?php else: ?>
                        <button class="btn-addtocart" data-product-id="<?= $productID ?>">
                            <i class="ri-shopping-cart-2-fill"></i> ADD TO CART
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="title">
            <h1>Related Products</h1>
        </div>

            <div class="product">
                <?php
                    $counter = 0;
                    foreach ($relatedProducts as $product):
                        if ($counter % 4 == 0): ?>
                            <div class="product-row">
                        <?php endif; ?>
                    <div class="product-item">
                        <div class="product-img" onclick="window.location.href='product-detail.php?id=<?= htmlspecialchars($product['productID']); ?>'">
                            <img src="images/<?= urlencode($product['categoryName']); ?>/<?= htmlspecialchars($product['mainImage']); ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <?php if ($product['stock'] === 0 ): ?>
                                <div class="product-out-of-stock">
                                    <div class="product-sold-out">SOLD<br>OUT</div>
                                </div>
                            <?php endif; ?>
                            <?php
                                $discount = floatval($product['discount']);
                                if  ($discount > 0):
                                    $formattedDiscount = fmod($discount, 1) === 0.0 ? number_format($discount, 0) : rtrim(rtrim(number_format($discount, 2, '.', ''), '0'), '.');
                            ?>
                                    <span class="discount-tag">-<?= $formattedDiscount ?>%</span>
                                <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <p><a href="product-detail.php?id=<?= htmlspecialchars($product['productID']); ?>"><?= htmlspecialchars($product['name']); ?></a></p>
                            <p><a href=""><?= htmlspecialchars($product['categoryName']); ?></a></p>
                            <?php if ($discount > 0): ?>
                                <p class="discount-price">$<?= number_format($product['price'] * (1-($discount / 100)), 2) ?> <strike>$<?= $product['price'] ?></strike></p>
                            <?php else: ?>
                                <p>$<?= number_format($product['price'], 2) ?></p>
                            <?php endif; ?>    
                            <a href="" class="btn-viewproduct">View Product</a>
                        </div>       
                    </div>
                <?php
                $counter++;
                if ($counter % 4 === 0 || $counter === count($relatedProducts)): ?>
                    </div>
                <?php endif;
                endforeach;
                ?>
            </div>
        </div>
    </main>
    <?php endif; ?>
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
<script src="assets/js/product-detail.js"></script>
</html>