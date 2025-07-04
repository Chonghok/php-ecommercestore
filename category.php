<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/product-functions.php';

$categoryID = $_GET['id'] ?? null;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$productsPerPage = 20;

// Get category-specific products
$products = getProductsByCategory($conn, $categoryID, $currentPage, $productsPerPage);
$totalProducts = countProductsByCategory($conn, $categoryID);
$totalPages = ceil($totalProducts / $productsPerPage);

$categoryName = getCategoryInfo($conn, $categoryID);
$categoryLink = getAllCategories($conn);
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
    <link rel="stylesheet" href="assets/css/product.css">
</head>
<body>

    <?php require_once 'includes/header.php' ?>    

    <main>
        <div class="product-banner">
            <img src="images/product-banner.png" alt="">
            <span><?= $categoryName['name'] ?></span>
        </div>

        <div class="category-btn">
            <input type="checkbox" id="category-toggle" class="category-toggle-checkbox">
            <label for="category-toggle" class="category-icon">
                <i class="ri-menu-line"></i> Categories
            </label>
            <div class="category-filter">
                <a href="product.php" class="category-link">All</a>
                <?php foreach ($categoryLink as $category): ?>
                <a href="category.php?id=<?= htmlspecialchars($category['categoryID']) ?>" 
                    class="category-link <?= $category['name'] === $categoryName['name'] ? 'active-page' : '' ?>">
                    <?= htmlspecialchars($category['name']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="space"></div>
        <?php if (empty($products)): ?>
            <div class="no-product">
                <h1>No products in category yet</h1>
            </div>
        <?php else: ?>
        <div class="product">
        <?php
                $counter = 0;
                foreach ($products as $product):
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
                        <a href="product-detail.php?id=<?= htmlspecialchars($product['productID']); ?>" class="btn-viewproduct">View Product</a>
                    </div>       
                </div>
            <?php
            $counter++;
            if ($counter % 4 === 0 || $counter === count($products)): ?>
                </div>
            <?php endif;
            endforeach;
            ?>
        </div>
        <?php if ($totalProducts > 20): ?>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
            <a href="category.php?id=<?= $categoryID ?>&page=<?= $currentPage - 1 ?>">
                <i class="ri-arrow-drop-left-line"></i>
            </a>
            <?php endif; ?>
            <?php
            // Show page numbers (limit to 5 visible pages for cleaner UI)
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $currentPage + 2);
    
            for ($i = $startPage; $i <= $endPage; $i++): ?>
                <a href="category.php?id=<?= $categoryID ?>&page=<?= $i ?>"<?= $i === $currentPage ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages): ?>
            <a href="category.php?id=<?= $categoryID ?>&page=<?= $currentPage + 1 ?>">
                <i class="ri-arrow-drop-right-line"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </main>

    <?php require_once 'includes/footer.php' ?>

</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</html>