    <header>
        <div class="header-container">
            <div class="logo-menu">
                <a href="index.php">
                    <img src="images/logo-removebg.png" alt="">
                </a>
                <nav>
                    <ul>
                        <li>
                            <a href="index.php" id="<?= 
                                (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">Home
                            </a>
                        </li>
                        <li>
                            <a href="product.php" id="<?= 
                                (basename($_SERVER['PHP_SELF']) == 'product.php' ||
                                 basename($_SERVER['PHP_SELF']) == 'category.php') ? 'active' : '' ?>">Products
                            </a>
                        </li>
                        <li>
                            <a href="promotion.php" id="<?= 
                                (basename($_SERVER['PHP_SELF']) == 'promotion.php') ? 'active' : '' ?>">Promotions
                            </a>
                        </li>
                        <li>
                            <a href="contact.php" id="<?= 
                                (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : '' ?>">Contact
                            </a>
                        </li>
                        <li>
                            <a href="about.php" id="<?= 
                                (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : '' ?>">About
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
            <div class="icon">
                <div class="icon-container">
                    <a href="#" id="searchIcon"><i class="ri-search-line"></i></a>
                    <div class="icon-tooltip"><div class="tooltip-arrow"></div><span>Search</span></div>
                </div>
                <div class="icon-container wishlist-icon">
                    <a href="wishlist.php" class="<?= 
                        (basename($_SERVER['PHP_SELF']) == 'wishlist.php') ? 'active' : '' ?>"><i class="ri-heart-line"></i>
                    </a>
                    <?php
                    $wishlistCount = 0;
                    if (isset($_SESSION['userID'])) {
                        $wishlistCount = getWishlistCount($conn, $_SESSION['userID']);
                    }
                    ?>
                    <div class="wishlist-count"><?= $wishlistCount ?></div>
                    <div class="icon-tooltip"><div class="tooltip-arrow"></div><span>Wishlist</span></div>
                    <div class="mini-toast"><div class="toast-arrow"></div><span>Added to wishlist</span></div>
                </div>
                <div class="icon-container">
                    <a href="cart.php" class="<?= 
                        (basename($_SERVER['PHP_SELF']) == 'cart.php') ? 'active' : '' ?>"><i class="ri-shopping-cart-line"></i>
                    </a>
                    <?php
                    $cartCount = 0;
                    if (isset($_SESSION['userID'])) {
                        $cartCount = getCartCount($conn, $_SESSION['userID']);
                    }
                    ?>
                    <div class="cart-count"><?= $cartCount ?></div>
                    <div class="icon-tooltip"><div class="tooltip-arrow"></div><span>Cart</span></div>
                    <div class="mini-toast-cart"><div class="toast-arrow-cart"></div><span>Added to cart</span></div>
                </div>
                <div class="search-overlay" id="searchOverlay">
                    <div class="search-container">
                        <div class="search-bar">
                            <i class="ri-search-line"></i>
                            <input type="text" placeholder="Search products" id="searchInput">
                        </div>
                    </div>
                    <div class="result-container" id="resultContainer">
                    </div>
                </div>
    
    
                <div class="user-dropdown">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <div class="user-info" id="userInfo">
                        <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="dropdown-menu" id="dropdownMenu">
                        <a href="order-history.php">Orders History</a>
                        <a href="#" onclick="confirmLogout(); return false;">Log Out</a>
                    </div>
                    <?php else: ?>
                    <div class="user-info" id="userInfo">
                        <span class="login-signup">
                            <a href="login.php" class="<?= 
                                (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active' : '' ?>">Log In
                            </a>|
                            <a href="register.php" class="<?= 
                                (basename($_SERVER['PHP_SELF']) == 'register.php') ? 'active' : '' ?>">Register
                            </a>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>