<?php
session_start();
require_once '../admin-includes/config.php';
require_once '../admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: ../login.php");
    exit;
}

$products = getProducts($conn);
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
    <link rel="stylesheet" href="../admin-assets/css/product.css">
</head>
<body>

    <?php require_once '../admin-includes/header.php' ?>

    <?php require_once '../admin-includes/sidebar.php' ?>

    <main>
        <div class="product-table-header">
            <h2>Products</h2>
            <div class="header-button">
                <a href="product-report.php" target="_blank" class="btn-print"><i class="ri-printer-fill"></i> Print Product Report</a>
                <a href="create-product.php" class="btn-create"><i class="ri-add-line"></i> Create new Product</a>
            </div>
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
                        <th>Category</th>
                        <th>Original Price</th>
                        <th>Discount (%)</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <?php foreach ($products as $product): ?>
                    <tr data-productid="<?= $product['productID'] ?>">
                        <td><?= $product['productID'] ?></td>
                        <td><img src="../../images/<?= $product['categoryName'] ?>/<?= $product['mainImage'] ?>" alt="<?= $product['name'] ?>"></td>
                        <td class="word-wrap-cell"><?= $product['name'] ?></td>
                        <td><?= $product['categoryName'] ?></td>
                        <td style="text-align: center;">$<?= number_format($product['price'], 2) ?></td>
                        <td style="text-align: center;"><?= number_format($product['discount'], 2) ?>%</td>
                        <td style="text-align: center;">
                            <button type="button" class="btn-update" onclick="window.location.href='update-product.php?id=<?= $product['productID'] ?>'">Update</button>
                            <button class="btn-delete" data-productid="<?= $product['productID'] ?>" data-category="<?= $product['categoryName'] ?>" data-image="<?= $product['mainImage'] ?>">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div class="delete-modal" id="deleteModal">
        <div class="delete-content">
            <div class="delete-message" id="deleteMessage">Are you sure you want to delete this product?</div>
            <div class="delete-buttons">
                <button class="delete-btn cancelbtn" id="btnCancel">Cancel</button>
                <button class="delete-btn deletebtn" id="btnDelete">Delete</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

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
    // Global variables
    let currentProductId = null;
    let currentCategory = null;
    let currentImage = null;

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const clearInput = document.querySelector('.clear-input');
        const categoryFilter = document.getElementById('categoryFilter');
        const deleteModal = document.getElementById('deleteModal');
        const btnCancel = document.getElementById('btnCancel');
        const btnDelete = document.getElementById('btnDelete');
        const toast = document.getElementById('toast');

        // Search and category filter
        searchInput.addEventListener('input', () => {
            toggleClearButton();
            fetchFilteredProducts();
        });

        categoryFilter.addEventListener('change', fetchFilteredProducts);

        // Show or hide the clear (X) button
        function toggleClearButton() {
            clearInput.style.display = searchInput.value.length > 0 ? 'block' : 'none';
        }

        // Clear search input
        clearInput.addEventListener('click', function () {
            searchInput.value = "";
            toggleClearButton();
            fetchFilteredProducts();
        });

        // Fetch filtered products via AJAX
        function fetchFilteredProducts() {
            const search = searchInput.value;
            const category = categoryFilter.value;

            fetch(`search-product.php?search=${encodeURIComponent(search)}&category=${encodeURIComponent(category)}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('productTableBody').innerHTML = html;
                    attachDeleteHandlers(); // Reattach delete listeners!
                });
        }

        // Show toast messages
        function showToast(message, isSuccess) {
            toast.textContent = message;
            toast.className = 'toast show ' + (isSuccess ? 'success' : 'error');

            setTimeout(() => {
                toast.className = toast.className.replace('show', '');
            }, 3000);
        }

        // Delete product
        function deleteProduct(productId, category, image) {
            const formData = new FormData();
            formData.append('productId', productId);
            formData.append('category', category);
            formData.append('image', image);

            fetch('delete-product.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const row = document.querySelector(`tr[data-productid="${productId}"]`);
                        if (row) row.remove();
                        showToast('Product deleted successfully!', true);
                    } else {
                        showToast('Error: ' + data.message, false);
                    }
                })
                .catch(error => {
                    showToast('Error deleting product: ' + error.message, false);
                    console.error('Error:', error);
                });
        }

        // Handle delete button clicks — this must be called after every fetch
        function attachDeleteHandlers() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    currentProductId = this.getAttribute('data-productid');
                    currentCategory = this.getAttribute('data-category');
                    currentImage = this.getAttribute('data-image');

                    document.getElementById('deleteMessage').textContent =
                        `Are you sure you want to delete product #${currentProductId}?`;

                    deleteModal.style.display = 'flex';
                });
            });
        }

        // Confirm deletion
        btnDelete.addEventListener('click', function () {
            deleteModal.style.display = 'none';
            deleteProduct(currentProductId, currentCategory, currentImage);
        });

        // Cancel deletion
        btnCancel.addEventListener('click', function () {
            deleteModal.style.display = 'none';
        });

        // Close modal on outside click
        deleteModal.addEventListener('click', function (e) {
            if (e.target === deleteModal) {
                deleteModal.style.display = 'none';
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && deleteModal.style.display === 'flex') {
                deleteModal.style.display = 'none';
            }
        });

        // Initial call to attach delete handlers
        attachDeleteHandlers();
    });
</script>
</html>
