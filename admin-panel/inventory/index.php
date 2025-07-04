<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

$stocks = getProductStock($conn);
$categories = getCategories($conn);
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
    <link rel="stylesheet" href="../admin-assets/css/inventory.css">
</head>
<body>
    
    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>

    <main>
        <div class="product-table-header">
            <h2>Inventory</h2>
        </div>
        <div class="search-filter">
            <div class="search-bar">
                <i class="ri-search-line"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Search by ID or name..." oninput="toggleClearButton()">
                <button type="button" class="clear-input" onclick="clearSearch()"><i class="ri-close-line"></i></button>
            </div>
            <div class="filter-dropdown">
                <select id="categoryFilter">
                    <option value="">All</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['categoryID'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>   
                </select>
            </div>
        </div>
        <div class="product-table-wrapper">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th style="text-align: center;">Quantity</th>
                    <th style="text-align: center;">Update Stock</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <?php foreach ($stocks as $stock): ?>
                <tr>
                    <td><?= $stock['productID'] ?></td>
                    <td><img src="../../images/<?= $stock['categoryName'] ?>/<?= $stock['mainImage'] ?>" alt="<?= $stock['name'] ?>"></td>
                    <td><?= $stock['name'] ?></td>
                    <?php if ($stock['stock'] === 0 ): ?>
                    <td style="text-align: center; color: red; font-weight: bold;"><?= $stock['stock'] ?></td>
                    <?php else: ?>
                    <td style="text-align: center;"><?= $stock['stock'] ?></td>
                    <?php endif; ?>
                    <td style="text-align: center;">
                    <button class="btn-update" onclick="window.location.href='update-inventory.php?id=<?= $stock['productID'] ?>'">Update</button>
                    </td>
                </tr>
                <?php endforeach; ?>
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
<script>
    const searchInput = document.getElementById('searchInput');
    const clearInput = document.querySelector('.clear-input');
    const categoryFilter = document.getElementById('categoryFilter');

    searchInput.addEventListener('input', fetchFilteredProducts);
    categoryFilter.addEventListener('change', fetchFilteredProducts);

    function toggleClearButton() {
        clearInput.style.display = searchInput.value.length > 0 ? 'block' : 'none';
    }
    function clearSearch() {
        searchInput.value = "";
        toggleClearButton();
        fetchFilteredProducts();
    }
    function fetchFilteredProducts() {
        const search = document.getElementById('searchInput').value;
        const category = document.getElementById('categoryFilter').value;

        fetch(`search-inventory.php?search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}`)
            .then(res => res.text())
            .then(html => {
                document.getElementById('productTableBody').innerHTML = html;
            });
    }
</script>
</html>
