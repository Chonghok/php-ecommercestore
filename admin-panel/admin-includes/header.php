<?php
$adminName = getAdminByID($conn, $_SESSION['admin_id']);
?>
<header>
    <div class="logo">
        <span class="company-name">LetsGear Admin</span>
    </div>
    <div class="user-dropdown" id="userDropdown">
        <div class="user-info" id="userInfo">
            <span class="username"><?= htmlspecialchars($adminName['username']); ?></span>
            <i class="ri-arrow-down-s-line"></i>
        </div>
        <div class="dropdown-menu" id="dropdownMenu">
            <a href="#">Log Out</a>
        </div>
    </div>
</header>