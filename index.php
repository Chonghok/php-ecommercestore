<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/product-functions.php';

$categories = getAllCategories($conn);
$featuredProducts = getFeaturedProducts($conn, [27, 24, 38, 33, 47, 14, 18, 9]);
$newArrivals = getNewArrivals($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetsGear</title>
    <link rel="icon" href="images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    
    <?php require_once 'includes/header.php' ?>

    <main>
        <div class="banner swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide banner-container">
                    <img src="images/banner1.webp" alt="">
                    <div class="banner-text">
                        <h1>LetsGear</h1>
                        <p>Discover top-tier computer accessories designed for performance, comfort, and style</p>
                        <a href="product.php">Shop Now</a>
                    </div>
                </div>
                <div class="swiper-slide banner-container">
                    <img src="images/banner2.webp" alt="">
                    <div class="banner-text">
                        <h1>Keyboards</h1>
                        <p>Mechanical, wireless, or ergonomic—whatever your typing needs, we have a keyboard for you</p>
                        <a href="category.php?id=1">Shop Now</a>
                    </div>
                </div>
                <div class="swiper-slide banner-container">
                    <img src="images/banner3.jpg" alt="">
                    <div class="banner-text">
                        <h1>Mouse</h1>
                        <p>Ultra-light gaming mice to ergonomic productivity options, find the perfect fit for your hand and workflow</p>
                        <a href="category.php?id=4">Shop Now</a>
                    </div>
                </div>
                <div class="swiper-slide banner-container">
                    <img src="images/banner4.webp" alt="">
                    <div class="banner-text">
                        <h1>Headsets</h1>
                        <p>Experience immersive sound with crystal-clear audio and noise isolation whether you're gaming, in a meeting, or enjoying music</p>
                        <a href="category.php?id=7">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="swiper-button-next">
                <i class="ri-arrow-right-s-line"></i>
            </div>
            <div class="swiper-button-prev">
                <i class="ri-arrow-left-s-line"></i>
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="title">
            <h1>Our <span>Categories</span></h1>
        </div>
            <div class="category swiper">
                <div class="swiper-wrapper">
                    <?php
                        foreach ($categories as $category) {
                    ?>
                    <a href="category.php?id=<?= $category['categoryID'] ?>" class="category-link swiper-slide">
                        <div class="category-container">
                            <img src="images/Categories/<?php echo htmlspecialchars($category['categoryImage']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>">
                            <p><?php echo htmlspecialchars($category['name']); ?></p>
                        </div>
                    </a>
                    <?php 
                    }?>
                </div>
                <div class="swiper-scrollbar"></div>
            </div>

        <div class="title">
            <h1>Featured Products</h1>
        </div>

        <div class="product">
            <?php
                $counter = 0;
                foreach ($featuredProducts as $product):
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
                        <p><a href="category.php?id=<?= htmlspecialchars($product['categoryID']); ?>"><?= htmlspecialchars($product['categoryName']); ?></a></p>
                        <?php if ($discount > 0): ?>
                            <p class="discount-price">$<?= number_format($product['price'] * (1-($discount / 100)), 2) ?> <strike>$<?= number_format($product['price'], 2); ?></strike></p>
                        <?php else: ?>
                            <p>$<?= number_format($product['price'], 2) ?></p>
                        <?php endif; ?>    
                        <a href="product-detail.php?id=<?= htmlspecialchars($product['productID']); ?>" class="btn-viewproduct">View Product</a>
                    </div>       
                </div>
            <?php
            $counter++;
            if ($counter % 4 === 0 || $counter === count($featuredProducts)): ?>
                </div>
            <?php endif;
            endforeach;
            ?>
        </div>

        <div class="banner-promotion">
            <img src="images/banner-promotion.jpg" alt="">
            <h1>New Year, New Gear — Start Fresh with <span>20% Off!</span></h1>
            <a href="promotion.php">
                <div class="explore-more">Explore More</div>
            </a>
        </div>

        <div class="title">
            <h1>New Arrivals</h1>
        </div>

        <div class="product">
            <?php 
                $counter = 0;
                foreach ($newArrivals as $product) {
                    if ($counter % 4 == 0) {
                        echo '<div class="product-row">';
                    }
            ?>
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
                        <p><a href="category.php?id=<?= htmlspecialchars($product['categoryID']); ?>"><?= htmlspecialchars($product['categoryName']); ?></a></p>
                        <?php if ($discount > 0): ?>
                            <p class="discount-price">$<?= number_format($product['price'] * (1-($discount / 100)), 2) ?> <strike>$<?= number_format($product['price'], 2); ?></strike></p>
                        <?php else: ?>
                            <p>$<?= number_format($product['price'], 2) ?></p>
                        <?php endif; ?> 
                        <a href="product-detail.php?id=<?= htmlspecialchars($product['productID']); ?>" class="btn-viewproduct">View Product</a>
                    </div>       
                </div>
            <?php
                $counter++;
                if($counter % 4 == 0){
                    echo '</div>';
                }
            }
            if($counter % 4 != 0){
                echo '</div>';
            }
            ?>
        </div>

    </main>

    <?php require_once 'includes/footer.php' ?>

</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/home.js"></script>
</html>