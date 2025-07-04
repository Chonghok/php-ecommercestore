<?php
    function isActiveSection($folderName) {
        return strpos($_SERVER['PHP_SELF'], "/admin-panel/$folderName/") !== false ? 'active' : '';
    }
?>

<div class="sidebar">
    <a href="../">
        <i class="ri-home-4-line"></i>&nbsp; Home
    </a>
    <a href="../admin/" class="<?= isActiveSection('admin') ?>">
        <i class="ri-admin-line"></i>&nbsp; Admin
    </a>
    <a href="../category/" class="<?= isActiveSection('category') ?>">
        <i class="ri-menu-search-line"></i>&nbsp; Categories
    </a>
    <a href="../product/" class="<?= isActiveSection('product') ?>">
        <i class="ri-product-hunt-line"></i>&nbsp; Products
    </a>
    <a href="../user/" class="<?= isActiveSection('user') ?>">
        <i class="ri-user-line"></i>&nbsp; Users
    </a>
    <a href="../inventory/" class="<?= isActiveSection('inventory') ?>">
        <i class="ri-suitcase-3-line"></i>&nbsp; Inventory
    </a>
    <a href="../order/" class="<?= isActiveSection('order') ?>">
        <i class="ri-shopping-cart-line"></i>&nbsp; Orders
    </a>

    <button type="button" onclick="window.location.href='../backup-data.php'">Backup Data</button>
</div>



