<?php
session_start();
require_once 'admin-includes/config.php';
require_once 'admin-includes/admin-functions.php';

if (!isset($_SESSION['admin_logged_in'])){
    header("Location: login.php");
    exit;
}
// Dashboard Card Data
$totalRevenue = getTotalRevenue($conn);
$lastOrderTotal = getLastOrderTotal($conn);
$totalOrder = getOrderCount($conn); 
$pendingOrder = getPendingOrderCount($conn);
$productCount = getProductCount($conn);
$totalStock = getStockSum($conn);
$lowStockCount = getLowStockCount($conn);

// Chart Data
$categoryChartData = getCategoryChartData($conn);
$topProductsChartData = getTopSellingProducts($conn);

// Recent Orders
$recentOrders = getRecentOrders($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LetsGear Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin-assets/css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <span class="company-name">LetsGear Admin</span>
        </div>
        <div class="user-dropdown" id="userDropdown">
            <div class="user-info" id="userInfo">
                <span class="username"><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
                <i class="ri-arrow-down-s-line"></i>
            </div>
            <div class="dropdown-menu" id="dropdownMenu">
                <a href="logout.php">Log Out</a>
            </div>
        </div>
    </header>

    <div class="sidebar">
        <a href="index.php" class="active">
            <i class="ri-home-4-line"></i>&nbsp; Home
        </a>
        <a href="admin/">
            <i class="ri-admin-line"></i>&nbsp; Admin
        </a>
        <a href="category/">
            <i class="ri-menu-search-line"></i>&nbsp; Categories
        </a>
        <a href="product/">
            <i class="ri-product-hunt-line"></i>&nbsp; Products
        </a>
        <a href="user/">
            <i class="ri-user-line"></i>&nbsp; Users
        </a>
        <a href="inventory/">
            <i class="ri-suitcase-3-line"></i>&nbsp; Inventory
        </a>
        <a href="order/">
            <i class="ri-shopping-cart-line"></i>&nbsp; Orders
        </a>

        <button type="button" onclick="window.location.href='backup-data.php'">Backup Data</button>
    </div>





    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-cards">
            <div class="card">
                <p class="card-title">Total Revenue</p>
                <h4 class="card-number">$<?= number_format($totalRevenue, 2) ?></h4>
                <p class="card-note">+$<?= number_format($lastOrderTotal, 2) ?> recently</p>
            </div>
            <div class="card">
                <p class="card-title">Total Orders</p>
                <h4 class="card-number"><?= $totalOrder ?></h4>
                <p class="card-note">All time total</p>
            </div>
            <div class="card">
                <p class="card-title">Pending Orders</p>
                <h4 class="card-number"><?= $pendingOrder ?></h4>
                <p class="card-note">Awaiting confirmation</p>
            </div>
            <div class="card">
                <p class="card-title">Total Products</p>
                <h4 class="card-number"><?= $productCount ?></h4>
                <p class="card-note">Available in store</p>
            </div>
            <div class="card">
                <p class="card-title">Stock Units</p>
                <h4 class="card-number"><?= $totalStock ?></h4>
                <p class="card-note">Current available quantity</p>
            </div>
            <div class="card">
                <p class="card-title">Low Stock Items</p>
                <h4 class="card-number"><?= $lowStockCount ?></h4>
                <p class="card-note">Restock needed soon</p>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart">
                <h3>Stock Units By Category</h3>
                <canvas id="chartCategory"></canvas>
            </div>
            <div class="chart">
                <h3>Top 5 Best-Selling Products</h3>
                <canvas id="chartTopProduct"></canvas>
            </div>
        </div>


        <!-- Sample Table -->
        <h3 style="margin: 30px 0px; ">Recent Orders</h3>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentOrders as $order): ?>
                <tr>
                    <td>#<?= $order['orderID'] ?></td>
                    <td><?= $order['fullName'] ?></td>
                    <td><?= $order['status'] ?></td>
                    <td>$<?= number_format($order['totalAmount'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($_SESSION['backup_success'])): ?>
    <div id="toast" class="toast show success">Data has been successfully backed up</div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) toast.classList.remove('show');
        }, 2000); // Hide after 2 seconds
    </script>
    <?php 
        unset($_SESSION['backup_success']);
    endif;
    ?>
    
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="admin-assets/js/home.js"></script>
<script>
    // Category Doughnut Chart
    const chartCategory = new Chart(document.getElementById('chartCategory').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($categoryChartData['labels']) ?>,
            datasets: [{
                label: 'Products per Category',
                data: <?= json_encode($categoryChartData['counts']) ?>,
                backgroundColor: <?= json_encode($categoryChartData['colors']) ?>,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', // top / left / right
                    labels: {
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });

    // Chart: Top 5 Products
    const chartTopProduct = new Chart(document.getElementById('chartTopProduct').getContext('2d'), {
        type: 'bar',
        data: {
        labels: <?= json_encode($topProductsChartData['labels']) ?>,
        datasets: [{
            label: 'Units Sold',
            data: <?= json_encode($topProductsChartData['sales']) ?>,
            backgroundColor: ['#60a5fa', '#34d399', '#fbbf24', '#a78bfa', '#fb7185'],
        }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
                }
            },
            plugins: {
                legend: {
                display: false
                }
            }
        }
    });




    // User dropdown toggle
    const userInfo = document.getElementById('userInfo');
    const userDropdown = document.getElementById('userDropdown');

    userInfo.addEventListener('click', function() {
        userDropdown.classList.toggle('active');
    });



    // Logout confirmation functionality
    const logoutLink = document.querySelector('.dropdown-menu a');
    const confirmationModal = document.getElementById('confirmationModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmBtn = document.getElementById('confirmBtn');

    logoutLink.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default link behavior
        userDropdown.classList.remove('active'); // Close the dropdown
        confirmationModal.classList.add('active'); // Show the modal
    });

    cancelBtn.addEventListener('click', function() {
        confirmationModal.classList.remove('active'); // Hide the modal
    });

    confirmBtn.addEventListener('click', function() {
        window.location.href = 'logout.php'; // Redirect to logout
    });

    // Close modal when clicking outside
    confirmationModal.addEventListener('click', function(e) {
        if (e.target === confirmationModal) {
            confirmationModal.classList.remove('active');
        }
    });
</script>
</html>
